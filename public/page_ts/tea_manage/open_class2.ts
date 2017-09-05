/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-open_class2.d.ts" />

$(function(){
    Enum_map.append_option_list("subject", $("#id_subject_list"),true);
    Enum_map.append_option_list("grade", $("#id_grade_list"),true);

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config), 
        onQuery :function() {
            //load_data();
        }
    });

    function get_unix_time(dateStr)
    {
        var newstr = dateStr.replace(/-/g,'/'); 
        var date =  new Date(newstr); 
        var time_str = date.getTime().toString();
        return Number(time_str)/1000;
    }

    function load_data(){
        $.reload_self_page({
            "teacherid"     : $("#id_search_teacher").val(),
            "lesson_status" : $("#id_lesson_status").val(),
            "lesson_type"   : $("#id_search_lesson_type").val(),

            "start_time"    : $("#id_start_time").val(),
            "end_time"      : $("#id_end_time").val(),
            "date_type"     : $("#id_date_type").val(),
            "opt_date_type" : $("#id_opt_date_type").val()
        });
    };

    $("#id_lesson_status").val(g_lesson_status);
    $("#id_search_teacher").val(g_teacherid);
    $("#id_search_lesson_type").val(g_lesson_type);

    
    $("#id_query").on("click",function(){
        load_data();
    });


    
    $.admin_select_user($("#id_search_teacher"),"teacher",function(){
        //load_data();
    });
    
    $("#id_lesson_status").on("change", function(){
        //load_data();
    });
    $("#id_search_lesson_type").on("change", function(){
        //load_data();
    });

    $("#id_add_lesson").on("click", function(){
        var id_courseid      = $("<input/>");
        var id_from_lessonid = $("<input/>");
        var id_teacherid     = $("<span/>");
        var id_lesson_total  = $("<span/>");
        var id_lesson_num    = $("<span/>");
        var id_lesson_intro  = $("<textarea/>");
        var id_start_time    = $("<input/>");
        var id_end_time      = $("<input/>");
        var id_difftime      = $("<input/>"); 
        var cache_difftime   = 0;
        var grade            = 0;
        var subject          = 0;

        var on_change_lesson = function(value ,row_data){
            var difftime   = row_data.lesson_end-row_data.lesson_start;
            cache_difftime = difftime;
            $.ajax({
                url      : '/tea_manage/get_teacherid',
                type     : 'POST',
                dataType : 'json',
                data     : {
                    'lessonid' : row_data.lessonid
			    },
                success : function(data) {
                    console.log(data);
                    id_teacherid.data('teacherid',data);
                    id_teacherid.html(data);
                }
            });
        };
        
        var on_change_course = function( value ,row_data) {
            var difftime = row_data.course_end-row_data.course_start;
            cache_difftime=difftime;

            if(row_data.course_type == "4001"){
                id_from_lessonid.parent().parent().show();
            }else{
                id_from_lessonid.parent().parent().hide();
            }
                        
            $.ajax({
                url: '/tea_manage/get_open_course_simple_info',
                type: 'POST',
                dataType: 'json',
                data: {
                    'courseid': row_data.courseid 
			    },
                success: function(data) {
                    console.log(data);
                    grade   = data.info.lesson_info.grade;
                    subject = data.info.lesson_info.subject;
                    id_lesson_num.html(data.info.lesson_info.lesson_num);
                    id_lesson_num.data("lessonid",data.info.lesson_info.lessonid);
                    id_lesson_total.html(data.info.lesson_total);
                }
            });
        };
        
	    //时间插件
	    id_start_time.datetimepicker({
		    datepicker:true,
		    timepicker:true,
		    format:'Y-m-d H:i',
		    step:30 ,
            onChangeDateTime:  function() {
                var start_date = id_start_time.val();
                var end_time = Number(get_unix_time(start_date))+Number(cache_difftime);
                id_end_time.val(
                    DateFormat(end_time, "hh:mm")
                );
            }
	    });
	    id_end_time.datetimepicker({
		    datepicker:false,
		    timepicker:true,
		    format:'H:i',
		    step:30
	    });

        var arr = [
            [ "课程",  id_courseid] ,
            [ "机器人课程 源课程",  id_from_lessonid] ,
            [ "teacherid",  id_teacherid] ,
            [ "课次",  id_lesson_num] ,
            [ "总课次",  id_lesson_total] ,
            [ "开始时间",   id_start_time] ,
            [ "结束时间",   id_end_time] ,
            [ "内容",   id_lesson_intro] ,
        ];

        $.show_key_value_table("新增课次", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var start_date    = id_start_time.val();
                var end_date      = id_end_time.val();
                var s_date        = start_date.substr(0,10);
                var s_time        = start_date.substr(10);
                var from_lessonid = id_from_lessonid.val();
                $.ajax({
                    url: '/tea_manage/add_open_lesson',
                    type: 'POST',
                    dataType: 'json',
                    data : {
                        'lessonid'      : id_lesson_num.data("lessonid"),
                        'date'          : s_date,
                        'start'         : s_time,
                        'end'           : end_date, 
                        'from_lessonid' : from_lessonid, 
                        'teacherid'     : id_teacherid.data("teacherid"), 
                        'lesson_intro'  : id_lesson_intro.val(),
                        'grade'         : grade,
                        'subject'       : subject
			        },
                    success: function(data) {
                        if(!data.ret){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(data.info);
                        }
                    }
                });
            }
        });
        
        id_from_lessonid.admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/tea_manage/get_open_from_list_for_js",
            select_primary_field   : "lessonid",
            select_display         : "course_name",
            select_no_select_value : 0, 
            select_no_select_title : "[未设置]"  , 
            'field_list' :[
                {
                    title:"lessonid",
                    width :50,
                    field_name:"lessonid"
                },{
                    title:"课程知识点",
                    field_name:"lesson_intro"
                },{
                    title:"课程名称",
                    field_name:"course_name"
                }
            ] , filter_list : [
                [
                    {
                        size_class : "col-md-4" ,
                        title      : "课程类型",
                        type       : "select" ,
                        'arg_name' : "course_type"  ,
                        select_option_list : [ {
                            value : -1 ,
                            text  : "全部" 
                        },{
                            value : 1001 ,
                            text :  "普通公开课(A)" 
                        },{
                            value : 1002 ,
                            text :  "普通公开课(B)" 
                        },{
                            value : 1003 ,
                            text  : "高级公开课" 
                        },{
                            value : 3001 ,
                            text  : "小班课" 
                        }]
                    },{
                        size_class : "col-md-8" ,
                        title      : "课程名",
                        'arg_name' : "search_str"  ,
                        type       : "input" 
                    }
                ] 
            ],
            "auto_close" : true,
            "onChange"   : on_change_lesson,
            "onLoadData" : null
        });

        //===========================================================================
        id_courseid.admin_select_dlg_ajax({
            "opt_type" :  "select", // or "list"
            "url"      : "/tea_manage/get_open_course_list_for_js",
            select_primary_field   : "courseid",
            select_display         : "course_name",
            select_no_select_value : 0  , // 没有选择是，设置的值 
            select_no_select_title : "[未设置]"  , // "未设置"

            //字段列表
            'field_list' : [
                {
                    title:"courseid",
                    width :50,
                    field_name:"courseid"
                },{
                    title:"课程类型",
                    field_name:"course_type_str"
                },{
                    title:"课程名称",
                    field_name:"course_name"
                },{
                    title:"老师",
                    //width :50,
                    field_name:"teacherid"
                }
            ] ,
            //查询列表
            filter_list:[
                [
                    {
                        size_class: "col-md-4" ,
                        title :"课程类型",
                        type  : "select" ,
                        'arg_name' :  "course_type"  ,

                        select_option_list: [ {
                            value : -1 ,
                            text :  "全部" 
                        },{
                            value :  1001 ,
                            text :  "普通公开课(A)" 
                        },{
                            value :  1002 ,
                            text :  "普通公开课(B)" 
                        },{
                            value :  1003 ,
                            text :  "高级公开课" 
                        },{
                            value :  4001,
                            text :  "机器人课程" 
                        }]
                    },{
                        size_class: "col-md-8" ,
                        title :"课程名",
                        'arg_name' :  "search_str"  ,
                        type  : "input" 
                    }
                ] 
            ],
            "auto_close"       : true,
            "onChange"         : on_change_course,
            "onLoadData"       : null
        });
    });
//----------------------------------------------------------------------
    $("#id_add_open_course").on("click", function(){
        var id_name         = $("<input> </input>") ;
        var id_enter_type   = $.obj_copy_node("#id_enter_type") ;
        var id_lesson_type  = $.obj_copy_node("#id_lesson_type") ;
        var id_tea_list     = $("<input/>");
        var id_lesson_total = $("<input style=\"width:50px\" value=\"10\"> </input>");
        var id_package      = $("<input/>");
        var id_stu_total    = $("<input> </input>") ;
        var id_subject_type = $.obj_copy_node("#id_subject_list") ;
        var id_grade_type   = $.obj_copy_node("#id_grade_list") ;
        var arr             = [
            [ "名称",  id_name ] ,
            [ "受众",  id_enter_type ] ,
            [ "科目",  id_subject_type ] ,
            [ "年级",  id_grade_type ] ,
            [ "类型",  id_lesson_type ] ,
            [ "老师",  id_tea_list ] ,
            [ "课程包管理",  id_package] ,
            [ "总课次",  id_lesson_total] ,
            [ "课程人数",  id_stu_total] ,
        ];

        $.show_key_value_table("新增课程", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var course_name   = $.trim(id_name.val());
                var enter_type    = id_enter_type.val();
                var contract_type = id_lesson_type.val(); 
                var teacherid     = id_tea_list.val();
                var lesson_total  = id_lesson_total.val();
                var stu_total     = id_stu_total.val();
                var subject       = id_subject_type.val(); 
                var grade         = id_grade_type.val(); 
                var packageid     = id_package.val(); 

                if(course_name == "" || typeof enter_type == "undefined" || typeof contract_type == "undefined"
                   || lesson_total == "" || stu_total=="")
                {
                    console.log(course_name);
                    console.log(enter_type);
                    console.log(contract_type);
                    console.log(lesson_total);
                    alert("请输入全部信息");
                    return;
                }
                
                $.ajax({
                    url: '/tea_manage/add_open_course',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'course_name'   : course_name,
                        'enter_type'    : enter_type,
                        'contract_type' : contract_type,
                        'teacherid'     : teacherid,
                        'lesson_total'  : lesson_total,
                        'stu_total'     : stu_total, 
                        'grade'         : grade,
                        'packageid'     : packageid,
                        'subject'       : subject
			        },
                    success: function(data){
                        if(data.ret != -1){
                            window.location.reload();
                        }
                    }
                });
            }
        });
         
        id_lesson_type.on("change",function(){
            var lesson_type = (id_lesson_type.val());
            if(lesson_type == "4001"){
                id_tea_list.hide();
            }else{
                id_tea_list.show();
            }
        });

        id_tea_list.admin_select_user({
            "type"  : "teacher"
            //"value" : "[未设置]"
        });

        id_package.admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/tea_manage/get_open_package_list_for_js",
            "args_ex"  : {
            },

            select_primary_field : "packageid",
            select_display       : "package_name",   //选好的显示类别
            select_no_select_value  :  0  , // 没有选择是，设置的值 
            select_no_select_title  :  "[未设置]"  , // "未设置"
            
            //字段列表
            'field_list' :[
                {
                    title:"packageid",
                    width :50,
                    field_name:"packageid"
                },{
                    title:"课程包类型",
                    field_name:"package_type"
                },{
                    title:"科目",
                    field_name:"subject"
                },{
                    title:"课程包名称",
                    //width :50,
                    field_name:"package_name"
                }
            ] ,
            //查询列表
            filter_list:[
                [
                    {
                        size_class: "col-md-4" ,
                        title :"课程包类型",
                        type  : "select" ,
                        'arg_name' :  "package_type"  ,

                        select_option_list: [ {
                            value : -1 ,
                            text :  "全部" 
                        },{
                            value :  1 ,
                            text :  "1V1试听课" 
                        },{
                            value :  2 ,
                            text :  "1V1定制课" 
                        },{
                            value :  3 ,
                            text :  "1V1自选课"
                        },{
                            value :  1001,
                            text :  "普通公开课" 
                        },{
                            value :  2001,
                            text :  "普通答疑课" 
                        },{
                            value :  3001,
                            text :  "普通小班课" 
                        }]
                    },{
                        size_class: "col-md-8" ,
                        title :"课程包名称/科目",
                        'arg_name' :  "search_str"  ,
                        type  : "input" 
                    }

                ] 
            ],

            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null
        });
    });

    $("#id_change_teacher").on("click", function(){
        var teacherid = $("input[name='public_teacher']:checked").val();
        if(typeof(teacherid)=="undefined"){
            show_message("提示","请选择老师");
            return;
        }
        $.ajax({
            url: '/tea_manage/change_open_teacher',
            type: 'POST',
            dataType: 'json',
            data: {
                'courseid'  : $(this).data("courseid"),
                'teacherid' : teacherid
			},
            success: function(data) {
                if(data.ret != -1){
                    window.location.reload();
                }
            }
        });
    });
    

     
    $(".opt-del").on("click", function(){
        var lessonid=$(this).parent().data("lessonid"); 
        show_message("删除公开课","要删除吗?!" , function(dialog){
            $.ajax({
                url: '/tea_manage/delete_open_lesson',
                type: 'POST',
                dataType: 'json',
                data: {
                    'lessonid':  lessonid
			    },
                success: function(data) {
                    if(data.ret == -1){
                        alert(data.info);
                    }else{
                        window.location.reload();
                    }
                }
            });
        });
    });
    
    
    
    $(".opt-stu-list").on("click",function(){
        var lessonid=$(this).parent().data("lessonid"); 

        var html_node       = $.obj_copy_node("#id_user_list" );
        var id_query_user   = html_node.find("#id_query_user");
        var id_add_user     = html_node.find("#id_add_user");
        var id_user_name    = html_node.find("#id_user_name");
        var id_search_phone = html_node.find("#id_search_phone");
        var id_role         = html_node.find("#id_role");
        var id_tbody        = html_node.find("#id_tbody");

        id_query_user.on("click",function(){
            var phone = id_search_phone.val();
            var role  = id_role.val();
            if(typeof(role) == 'undefined' || phone == ""){
                alert("请输入账号并选择角色");
                return;
            }
            $.ajax({
                url: '/tea_manage/search_role',
                type: 'POST',
                dataType: 'json',
                data: {
                    'role'  : role,
                    'phone' : phone
			    },
                success: function(data) {
                    if(data['ret'] != 0){
                        id_user_name.html(data['info']);
                        id_add_user.data("userid", 0);
                    }else{
                        id_user_name.html( data['info']['nick'] );
                        id_add_user.data("userid", data['info']['userid'] );
                    }
                }
            });
        });
        
        id_add_user.on("click",function(){
            var userid=$(this).data("userid");
            if (!(userid>0)){
                show_message("","还没有查询到用户" );
                return;
            }
            opt_user_list( lessonid ,"add", userid );
        });


        BootstrapDialog.show({
            title: "学生列表",
            message: html_node,
            buttons: [
                {
                    label: '返回',
                    cssClass: 'btn btn-default',
                    action: function(dialog) {
                        dialog.close();
                    }
                }
            ]
        }); 

        var opt_user_list= function( lessonid, opt_type, userid){
            $.ajax({
                url: '/tea_manage/opt_open_lesson_users',
                type: 'POST',
                dataType: 'json',
                data: {
                    'lessonid' : lessonid ,
                    'opt_type' : opt_type ,
                    'userid' :userid 
			    },
                success: function(data) {
                    if (data.ret!=0){
                        show_message("提示", data.info);
                        return;
                    }else{
                        var html_str="";
                        $.each( data.stu_list,function(i, item){
                            html_str+="<tr><td>"+item.userid+ "<td>"+item.nick+ "<td>"+item.phone+"<td><a href=\"javascript:;\" title=\"删除\" class=\"btn fa   fa-trash-o opt-del-user\" data-userid=\""+item.userid+"\"></a> </tr>";
                        });
                        id_tbody.html( html_str);

                        //bind
                        id_tbody.find(".opt-del-user").on("click",function(){
                            var userid=$(this).data("userid");
                            opt_user_list(lessonid, "del", userid );
                        });
                    }
                }
            });
        };
        opt_user_list(lessonid );
    });

    function init_upload(type){
        var opt_up = $(".opt-upload");

        $.each(opt_up, function(i, item){
            var This = $(item);
            var id = 'id_'+This.parent().data('itemid');
            This.attr('id', id);
            var par_id = id+'_par';
            This.parent().attr('id', par_id);
            var uploader = Qiniu.uploader({
		        runtimes: 'html5, flash, html4',
		        browse_button: id , //choose files id
		        uptoken_url: '/upload/private_token',
		        domain: g_qiniu_domain,
		        container: par_id,
		        drop_element: par_id,
		        max_file_size: '30mb',
		        dragdrop: true,
				flash_swf_url: '/js/qiniu/plupload/Moxie.swf',
		        chunk_size: '4mb',
		        unique_names: false,
		        save_key: false,
		        auto_start: true,
		        init: {
			        'FilesAdded': function(up, files) {
				        plupload.each(files, function(file) {
                            var tmp = item.button;
                            console.log('waiting...');
                        });
			        },
			        'BeforeUpload': function(up, file) {
				        console.log('before uplaod the file');
                        
				        if (!check_type(file.type)) {
					        alert('请上传PDF文件');
					        return false;
                        }
			        },
			        'UploadProgress': function(up,file) {
				        console.log('upload progress');
			        },
			        'UploadComplete': function() {
				        console.log('success');
			        },
			        'FileUploaded' : function(up, file, info) {
				        console.log('Things below are from FileUploaded');
                        setComplete(up, info, file, id);
			        },
			        'Error': function(up, err, errTip) {
				        console.log('Things below are from Error');
				        console.log(up);
				        console.log(err);
				        console.log(errTip);
			        },
			        'Key': function(up, file) {
				        var key = "";
				        //generate the key
                        var time = (new Date()).valueOf();
				        return $.md5(file.name) +time+'.pdf';
			        }
		        }
	        });
        });
    };

    init_upload();
    var setComplete = function(up, info, file, id) {
        
        var upload_succ = true;
	    var fileTargetID = file.id;
        var res = $.parseJSON(info);
        var url;
        if (res.url) {
    	    
        } else {
            var domain = up.getOption('domain');
            url = domain + encodeURI(res.key);
            var link = domain + res.key;
            console.log('MILLIONS else Test: ' + info);
            var urlkey    = res.key;
            $.ajax({
            	url: '/tea_manage/upload_open_cw',
            	type: 'POST',
            	data: {'urlkey': urlkey, 'id':id},
		    	dataType: 'json',
		    	success: function(data) {
		    		if (data['ret'] == 0) {
                        alert("上传成功");
                        //reload
                        window.location.reload();
		    		} else {
                        alert(data['info']);
		    		}
		    	}
            }); 
        }
    };


    function check_type(file_type)
    {
	    return file_type == 'application/pdf' ? true : false;
    }


    function get_file_size(file_size)
    {
	    if (file_size > 1024 && file_size < 1024 * 1024) {
		    size = (file_size / 1024).toFixed(2);
		    return size + ' KB';
	    } else if (file_size > 1024 * 1024) {
		    size = ((file_size / 1024) / 1024).toFixed(2);
		    return size + ' MB';
	    } else {
		    return file_size;
	    }
    }

    function get_time()
    {
	    var myDate = new Date();

	    var year   = myDate.getFullYear();
	    var month  = myDate.getMonth();
	    var day    = myDate.getDate();
	    var hour   = myDate.getHours();
	    var mimute = myDate.getMinutes();

	    return year + '-' + month + '-' + day +
		    ' ' + hour + ':' + mimute;
    }
    
	$(".opt-download").on('click',function(){
		var file_url = $(this).parent().data("url");
        if(file_url == ""){
            alert("课件尚未上传!");
            return;
        }
		if(file_url != ""){
			$.ajax({
				type     :"post",
				url      :"/upload/get_download_url/",
				dataType :"json",
				data     :{"file_url":file_url},
				success  : function(result){
					if(result.ret == 0){
						window.open(result.download_url); 
					}
				}
			});
		}
	});

    $('.opt-from-lessonid').on('click', function() {
        
        var courseid = $(this).parent().data('courseid');

        var id_from_lessonid = $("<input >");
        var arr                = [
            [ "请输入",  id_from_lessonid ] ,
        ];

        $.show_key_value_table("设置from_lessonid", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var from_lessonid= id_from_lessonid.val();
                $.do_ajax('/tea_manage/add_from_lessonid', {
                    'courseid'      : courseid,
                    'from_lessonid' : from_lessonid
                },function(data){
                    if (data.ret!=0) {
                        alert(data.info) ;
                    }else{
                        alert("成功") ;
                        window.location.reload();
                    }
			        dialog.close();
                });
            }
        });
    });

    $(".opt-can-set").on("click", function(){
        var lessonid  =$(this).parent().data("lessonid");
        var id_can_set=  $.obj_copy_node("#id_can_set") ;
        var arr                = [
            [ "设置机器课程",  id_can_set ] ,
        ];

        $.show_key_value_table("机器课程", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var can_set = id_can_set.val();
                if (! (can_set >0) ) {
                    alert("请设置是否由权限设置机械课程");
                    return; 
                }
                $.ajax({
                    url: '/tea_manage/can_set_from_lessonid',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'lessonid':  lessonid,
                        'can_set' :  can_set
			        },
                    success: function(data) {
                        if(data.ret != -1){
                            window.location.reload();
                        }
                    }
                });
            }
        });
        
    });

    $.each($(".btn-group"),function(i,item){
        var $item=$(item);
        var lesson_type=$item.data("lesson_type");
        if(lesson_type == "机器人课程"){
            $item.find(".opt-can-set").hide();
        }else{
            $item.find(".opt-can-set").show();
        }


    });

    $.each( $(".opt-set-time"),function(i,item)  {
        var $item=$(item);
        var lessonid=$item.get_opt_data("lessonid");
        $item.admin_set_lesson_time({lessonid :lessonid});
    });

    
    $(".opt-set-course_name").on("click",function(){
        var courseid        = $(this).get_opt_data("courseid");
        var lessonid        = $(this).get_opt_data("lessonid");
        var id_course_name  = $("<input/>");
        var id_lesson_intro1 = $("<input/>");
        var id_lesson_intro2 = $("<input/>");
        $.do_ajax("/tea_manage/get_course_name",{
            "courseid":courseid,
            "lessonid":lessonid
        },function(result){
            id_course_name.val(result.course_name);
            var lesson_intro=result.lesson_intro.split("|");
            //console.log(lesson_intro);
            id_lesson_intro1.val(lesson_intro[0]);
            if(lesson_intro[1]){
                id_lesson_intro2.val(lesson_intro[1]);
            }
            
            var arr = [
                [ "课程名称",  id_course_name], 
                [ "知识点1",  id_lesson_intro1], 
                [ "知识点2",  id_lesson_intro2] 
            ];

            $.show_key_value_table("修改课程名称", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.do_ajax("/tea_manage/set_course_name",{
                        "courseid"      : courseid,
                        "lessonid"      : lessonid,
                        "course_name"   : id_course_name.val(),
                        "lesson_intro1" : id_lesson_intro1.val(),
                        "lesson_intro2" : id_lesson_intro2.val()
                    });
                }
            },function(){
                /*console.log(lesson_intro[1]);
                if(lesson_intro[1]){
                    id_lesson_intro2.val(lesson_intro[1]);
                }else{
                    id_lesson_intro2.parent().parent().hide();
                }*/
            });
        });
    });

    $('.opt-change-teacher').on('click',function(){
        var courseid = $(this).parent().data("courseid");
        $(this).admin_select_user({
            "show_select_flag":true,
            "onChange":function(val){
                var teacherid = val;
                $.ajax({
                    url: '/tea_manage/change_open_teacher',
                    type: 'POST',
                    data: {
                        'teacherid' : teacherid,
                        'courseid'  : courseid
                    },
                    dataType: 'json',
                    success: function(result) {
                        window.location.reload();
                    }
                });
            }
        });
    });

    $("#id_add_robot_lesson").on("click",function(){
        var id_from_lessonid = $("<input/>");
        var id_lesson_start  = $("<input/>");
        var id_lesson_end    = $("<input/>");
        var id_lesson_num    = $("<input/>");
        var cache_difftime   = 0;
        var arr = [
            ["源课程",id_from_lessonid],
            ["开始时间",id_lesson_start],
            ["连排天数",id_lesson_num],
        ];

        $.show_key_value_table("添加机器人课程",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.ajax({
                    url: '/tea_manage/add_robot_lesson',
                    type: 'POST',
                    dataType: 'json',
                    data : {
                        'from_lessonid' : id_from_lessonid.val(),
                        'lesson_start'  : id_lesson_start.val(),
                        'lesson_num'    : id_lesson_num.val()
			        },success: function(data) {
                        if(!data.ret){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(data.info);
                        }
                    }
                });
            }
        });

        id_from_lessonid.admin_select_dlg_ajax({
            "opt_type" : "select", 
            "url"      : "/tea_manage/get_open_from_list_for_js",
            select_primary_field   : "lessonid",
            select_display         : "course_name",
            select_no_select_value : 0, 
            select_no_select_title : "[未设置]", 
            'field_list'           : [{
                    title:"lessonid",
                    width :50,
                    field_name:"lessonid"
                },{
                    title:"课程知识点",
                    field_name:"lesson_intro"
                },{
                    title:"课程名称",
                    field_name:"course_name"
                }
            ] , filter_list : [
                [{
                    size_class : "col-md-4" ,
                    title      : "课程类型",
                    type       : "select" ,
                    'arg_name' : "course_type"  ,
                    select_option_list : [ {
                        value : -1 ,
                        text  : "全部" 
                    },{
                        value : 1001 ,
                        text :  "普通公开课(A)" 
                    },{
                        value : 1002 ,
                        text :  "普通公开课(B)" 
                    },{
                        value : 1003 ,
                        text  : "高级公开课" 
                    },{
                        value : 3001 ,
                        text  : "小班课" 
                    }]
                },{
                    size_class : "col-md-8" ,
                    title      : "课程名",
                    'arg_name' : "search_str"  ,
                    type       : "input" 
                }] 
            ],
            "auto_close" : true,
            "onLoadData" : null
        });


	    //时间插件
	    id_lesson_start.datetimepicker({
		    datepicker : true,
		    timepicker : true,
		    format     : 'Y-m-d H:i',
		    step       : 30 
	    });
    });

    $("#id_add_many_lesson").on("click",function(){
        var id_courseid     = $("<input/>");
        var id_lesson_start = $("<input/>");
        var id_lesson_end   = $("<input/>");
        var id_date         = $("<div/>");
        var arr = [
            ["源课程",id_courseid],
            ["开始时间",id_lesson_start],
            ["结束时间",id_lesson_end],
            ["排课周期",id_date],
        ];
        Enum_map.append_checkbox_list("week",id_date,"week_day",[1,2,3,4,5,6,7]);

        $.show_key_value_table("添加多堂公开课",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.ajax({
                    url: '/tea_manage/add_many_lesson',
                    type: 'POST',
                    dataType: 'json',
                    data : {
			        },success: function(data) {
                        if(!data.ret){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(data.info);
                        }
                    }
                });
            }
        });

        id_courseid.admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/tea_manage/get_open_from_list_for_js",
            select_primary_field   : "lessonid",
            select_display         : "course_name",
            select_no_select_value : 0, 
            select_no_select_title : "[未设置]"  , 
            'field_list' :[
                {
                    title:"lessonid",
                    width :50,
                    field_name:"lessonid"
                },{
                    title:"课程知识点",
                    field_name:"lesson_intro"
                },{
                    title:"课程名称",
                    field_name:"course_name"
                }
            ] , filter_list : [
                [
                    {
                        size_class : "col-md-4" ,
                        title      : "课程类型",
                        type       : "select" ,
                        'arg_name' : "course_type"  ,
                        select_option_list : [ {
                            value : -1 ,
                            text  : "全部" 
                        },{
                            value : 1001 ,
                            text :  "普通公开课(A)" 
                        },{
                            value : 1002 ,
                            text :  "普通公开课(B)" 
                        },{
                            value : 1003 ,
                            text  : "高级公开课" 
                        },{
                            value : 3001 ,
                            text  : "小班课" 
                        }]
                    },{
                        size_class : "col-md-8" ,
                        title      : "课程名",
                        'arg_name' : "search_str"  ,
                        type       : "input" 
                    }
                ] 
            ],
            "auto_close" : true,
            "onLoadData" : null
        });


	    //时间插件
	    id_lesson_start.datetimepicker({
		    datepicker : true,
		    timepicker : true,
		    format     : 'Y-m-d H:i',
		    step       : 30 
	    });
	    id_lesson_end.datetimepicker({
            minTime    : "7:00",
            maxTime    : "23:00",
		    datepicker : true,
		    timepicker : true,
		    format     : 'H:i',
		    step       : 30 
	    });
    });

    //实例化一个plupload上传对象
    var uploader = new plupload.Uploader({
        browse_button : 'id_add_lesson_by_excel', //触发文件选择对话框的按钮，为那个元素id
        url : '/tea_manage_new/add_open_class_by_xls', //服务器端的上传页面地址
        flash_swf_url       : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        filters: {
            mime_types : [
                { title : "xls files", extensions : "xls" },
                { title : "xlsx files", extensions : "xlsx" }
            ],
            max_file_size      : '40m', //最大只能上传400kb的文件
            prevent_duplicates : true //不允许选取重复文件
        }
    });

    uploader.init();
    uploader.bind('FilesAdded',function(up, files) {
        uploader.start();
    });
});
