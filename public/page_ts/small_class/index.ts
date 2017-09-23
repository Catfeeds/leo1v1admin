/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/small_class-index.d.ts" />

$(function(){
    var load_data=function(){
        var start_date  = $("#id_date_start").val();
        var end_date    = $("#id_date_end").val();
        var teacherid   = $("#id_teacherid").val();
        var assistantid = $("#id_assistantid").val();
        var del_flag    = $("#id_del_flag").val();

	    var url= window.location.pathname+"?start_time="+start_date+"&end_time="+end_date+"&teacherid="+teacherid+"&assistantid="+assistantid+"&del_flag="+del_flag;
	    window.location.href=url;
    };
    
    $("#id_teacherid").val(g_args.teacherid);
    $("#id_assistantid").val(g_args.assistantid);
    $("#id_date_start").val(g_args.start_time);
    $("#id_date_end").val(g_args.end_time);
    $("#id_del_flag").val(g_args.del_flag);

    $("#id_teacherid").admin_select_user({
        "type"   : "teacher",
        "onChange": function(){
            load_data();
        }
    });

    $("#id_assistantid").admin_select_user({
        "type"   : "assistant",
        "onChange": function(){
            load_data();
        }
    });
    
    $("#id_del_flag").on("change", function(){
        load_data();
    });
    
	//TODO
	//时间控件
	$('#id_date_start, #id_date_end').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d ',
	    onChangeDateTime :function(){
		    load_data();
        }
	});
    
    $('#add_small_class_course').on('click', function(){
        var id_course_name  = $("<input>");
        var id_grade        = $("<select/>");
        var id_subject      = $("<select/>");
        var id_lesson_count = $("<input>");
        var id_stu_total    = $("<input>");
        var id_packageid    = $("<input>");
        
        Enum_map.append_option_list("grade", id_grade,true);
        Enum_map.append_option_list("subject", id_subject,true);
        
        var arr = [
            [ "名称",  id_course_name] ,
            [ "年级",  id_grade] ,
            [ "科目",  id_subject] ,
            [ "课次",  id_lesson_count] ,
            [ "人数",  id_stu_total ] ,
            [ "课程包",  id_packageid] ,
        ];
        show_key_value_table("新增小班课", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var course_name  = id_course_name.val();
                var lesson_total = id_lesson_count.val();
                var grade        = id_grade.val();
                var subject      = id_subject.val();
                var stu_total    = id_stu_total.val();
                var packageid    = id_packageid.val();
                if (!course_name) {
                    BootstrapDialog.alert('课程名称不可以为空');
                    html_node.find('.course_name').addClass('warning');
                    return;
                }
                if (parseInt(lesson_total) <= 0 || isNaN(parseInt(lesson_total))) {
                    BootstrapDialog.alert('课次总数不可以为零');
                    return;
                }
                if (parseInt(stu_total) <= 0 || isNaN(parseInt(stu_total))) {
                    BootstrapDialog.alert('学生总数不可以为零');
                    return;
                }

                $.ajax({
                    url  : '/small_class/add_lesson_course',
                    type : 'POST',
                    data : {
                        'grade'        : grade,
                        'subject'      : subject,
                        'lesson_total' : lesson_total,
                        'course_name'  : course_name,
                        'packageid'    : packageid,
                        'stu_total'    : stu_total
                    },
                    dataType: 'json',
                    success:   ajax_default_deal_func
                });
			    dialog.close();
            }
        });

        id_packageid.admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/tea_manage/get_open_package_list_for_js",
            //其他参数
            "args_ex" : {
                //type  :  "student"
            },

            select_primary_field   : "packageid",
            select_display         : "package_name",
            select_no_select_value : 0,
            select_no_select_title : "[未设置]",

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
            "auto_close" : true,
            "onChange"   : null,
            "onLoadData" : null
        });
        
    });
    
    
    $('.opt-alloc-teacher').on('click',function(){
        var courseid = $(this).parent().data("courseid");
        $(this).admin_select_user({
            "show_select_flag":true,
            "onChange":function(val){
                var teacherid = val;
                $.ajax({
                    url: '/small_class/set_course_teacher',
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

    $('.opt-alloc-assistant').on('click',  function(){
        var courseid =  $(this).parent().data("courseid");
        $(this).admin_select_user({
            "show_select_flag" : true,
            "type"             : "assistant",
            "onChange"         : function(val){
                $.ajax({
                    url: '/small_class/set_course_assistant',
                    type: 'POST',
                    data: {
                        'assistantid' : val,
                        'courseid'    : courseid
                    },
                    dataType: 'json',
                    success: function(result) {
                        window.location.reload();
                    }
                });
            }
        });
    });

    $('.opt-list-student').on('click',  function(){
        var courseid =  $(this).get_opt_data("courseid");
        
        show_ajax_table({
            "title" : "学生列表",
            "bind"  : function($id_body ) {
                $id_body.find(".opt-delete-student").on("click",function(){
                    var userid=$(this).data("userid");
                    var student_nick= $(this).data("student_nick");
                    BootstrapDialog.confirm("要删除["+student_nick+"]?!",function(ret){
                        if (ret){
                            $.do_ajax( "/user_manage_new/small_class_del_student",{
                                courseid : courseid,
                                userid   : userid
                            },function(result){
                                if(result.ret!=0){
                                    BootstrapDialog.alert(result.info);
                                }else{
                                    window.location.reload();
                                }
                            });
                        }
                    });
                });
            },
            "field_list" : [{
                "name"  : "userid",
                "title" : "id"
            },{
                "name"  : "student_nick",
                "title" : "学生",
                "render" :function(val,item){
                    return "<a  href=\"/stu_manage?sid="+item["userid"]+"\">"+val+" </a>" ;
                }

            },{
                "name"  : "join_time",
                "title" : "加入时间"
            },{
                "title" : "操作",
                "render" :function(val,item){
                    return "<a   data-student_nick=\""+item["student_nick"]+"\"  data-userid=\""+item["userid"]+"\" class=\"opt-delete-student\" >删除</a>" ;
                    
                }
            }],
            "request_info" : {
                "url"  : "/small_class/get_small_class_user_list" ,
                "data" : {
                    "courseid" : courseid
                }
            }
        });
    });
    
    $('.opt-lesson-open').on('click', function(){
        var courseid       = $(this).get_opt_data("courseid");
        var id_lesson_open = $("<input>");
        var lesson_open    = $(this).get_opt_data("lesson_open");

        id_lesson_open. datetimepicker({
            format: "Y-m-d H:i",
            autoclose: true,
            todayBtn: true
        });
        
        id_lesson_open.val(lesson_open);

        var arr                = [
            [ "开课时间",  id_lesson_open] ,
        ];

        show_key_value_table("安排课程时间", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var lesson_open = id_lesson_open.val();
                //alert(courseid);
                do_ajax('/small_class/set_lesson_open', {
                    'courseid': courseid,
                    'lesson_open':lesson_open
                },function(){
                    alert('设置成功' );
                });
			    dialog.close();
            }
        });
    });

    $('.opt-set-lesson-time').on('click', function(){
        var courseid = $(this).parent().data('courseid');
        dp_get_lesson_time(courseid);
    });
    var dp_get_lesson_time = function(courseid) {
        $.getJSON('/small_class/get_lesson_time', {
            'courseid': courseid
        }, function(result){
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
            } else {
                dp_set_lesson_time(courseid, result['lesson_time_list']);
            }
        });
    };

    var set_lesson_time_option = function(lesson_time) {
        var $lesson_time = $('<div></div>').html('<option value="0"> 周日 </option>'+
                              '<option value="1"> 周一 </option>'+
                              '<option value="2"> 周二 </option>'+
                              '<option value="3"> 周三 </option>'+
                              '<option value="4"> 周四 </option>'+
                              '<option value="5"> 周五 </option>'+
                              '<option value="6"> 周六 </option>');
        $lesson_time.find('option').each(function(){
            if (lesson_time.localeCompare($(this).val() ) == 0) {
                $(this).attr('selected', 'selected');
            }
        });

        return $lesson_time.html();

    };
    
    var dp_set_lesson_time = function(courseid, lesson_time_list) {
        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg_set_lesson_time'));
        if (lesson_time_list != null && lesson_time_list.length != 0 ) {
            for (var i=0; i<lesson_time_list.length; i++) {
                lesson_time_item = '<tr class="lesson_time"><td><select class="form-control dayofweek">'+
                    set_lesson_time_option(lesson_time_list[i][0]) +
                    '</select></td><td><input class="form-control start_time" value="'+lesson_time_list[i][1]+'" ></td>'+
                    '<td><input class="form-control end_time" value="'+lesson_time_list[0][2]+'"></td>'+
                    '<td><button class="btn btn-warning fa fa-close delete_time"></button></td></tr>';
                html_node.find('.lesson_time_tab').append(lesson_time_item);
            }
        }
        BootstrapDialog.show({
	        title: '修改可能上课时间',
	        message : function(dialog) {

                html_node.find('.lesson_time_tab').on('click', '.add_new_time', function(){
                    var time_str = '<tr class="lesson_time"><td><select class="form-control dayofweek">'+
                            '<option value="0"> 周日 </option>'+
                            '<option value="1"> 周一 </option>'+
                            '<option value="2"> 周二 </option>'+
                            '<option value="3"> 周三 </option>'+
                            '<option value="4"> 周四 </option>'+
                            '<option value="5"> 周五 </option>'+
                            '<option value="6"> 周六 </option>'+
                            '</select></td><td><input class="form-control start_time"></td>'+
                            '<td><input class="form-control end_time" ></td>'+
                            '<td><button class="btn btn-warning fa fa-close delete_time"></button></td></tr>';
                    $(this).parents('tr').after(time_str);
                });

                html_node.find('.lesson_time_tab').on('click', '.delete_time', function(){
                    $(this).parents('tr').remove();
                });

                return html_node;
            },
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    
                    var lesson_time = '';
                    html_node.find('.lesson_time').each(function(){
                        lesson_time += $(this).find('.dayofweek').val() + '|' +
                            $(this).find('.start_time').val() + '|' +
                            $(this).find('.end_time').val() + ',';
                    });

                    $.getJSON('/small_class/set_lesson_time', {
                        'courseid': courseid, 'lesson_time': lesson_time
                    }, function(result) {
                        BootstrapDialog.alert(result['info']);
                    });
			        dialog.close();
		        }
	        }]
        });
    };

    $('.opt-add-extra-lesson').on('click', function() {
        
        var courseid = $(this).parent().data('courseid');

        var id_incr_lesson_num = $("<input>");
        var arr                = [
            [ "添加小班课课次数目",  id_incr_lesson_num ] ,
        ];

        show_key_value_table("添加小班课", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var incr_lesson = id_incr_lesson_num.val();
                //alert(courseid);
                do_ajax('/small_class/add_extra_small_lessons', {
                    'courseid': courseid,
                    'incr_lesson': incr_lesson
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
    
    $('.opt-del-lesson-info').on('click', function() {
        var config_courseid = $(this).parent().data('courseid');
        BootstrapDialog.show({
	        title: "删除改管理",
	        message : "确认是否删除" + config_courseid,
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action : function(dialog) {
                do_ajax( "/small_class/get_config_courseid",{
                'courseid' : config_courseid
                },function(data){
                    if(data.ret != 0){
                        alert(data.ret);
                    }else{
                if (data.count >0 ) {
                    alert("相关合同没有删除");
                }else{
                    do_ajax("/small_class/del_lesson",{
                        'courseid' : config_courseid
                    },function(data){
                        alert('删除成功');
                        window.location.reload();
                    });
                };
            }
        });  
                                       
		        }
	        }]
        });
      });

    if ( window.location.pathname =="/small_class/index_ass" ) {
        $("#id_assistantid").parent().parent().hide();
    }

    $(".opt-add_student").on("click",function(){
        var courseid        = $(this).parent().data("courseid");
        var id_userid       = $("<input/>");
        var id_change       = $("<select/>");
        var id_old_courseid = $("<input/>");

        var change_html="<option value=\"0\">否</option>"
            +"<option value=\"1\">是</option>";
        id_change.append(change_html);

        id_change.on("change",function(){
            if(id_change.val()==0){
                id_old_courseid.parents("tr").hide();
            }else{
                id_old_courseid.parents("tr").show();
            }
        });

        var arr = [
            ["用户id",id_userid],
            ["是否换课",id_change],
            ["原小班课id",id_old_courseid],
        ];

       $.show_key_value_table("添加/更换学生",arr,{
           label    : "确认",
           cssClass : "btn-warning",
           action   : function(dialog) {
               $.do_ajax("/small_class/add_small_student",{
                   "courseid"     : courseid,
                   "userid"       : id_userid.val(),
                   "is_change"    : id_change.val(),
                   "old_courseid" : id_old_courseid.val()
               },function(result){
                   if(result.ret!=0){
                       BootstrapDialog.alert(result.info);
                   }else{
                       window.location.reload();
                   }
               });
           }
       },function(){
           id_old_courseid.parents("tr").hide();
       });
    });
});
