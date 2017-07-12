$(function(){
    function load_data(){
        var lesson_status = $("#id_lesson_status").val();
        var teacherid     = $("#id_search_teacher").val();
        var lesson_type   = $("#id_search_lesson_type").val();
        var start         = $("#id_date_start").val();
        var end           = $("#id_date_end").val();

        var url = "/tea_manage/open_class/?teacherid="+teacherid+"&lesson_status="+lesson_status+"&lesson_type="+lesson_type+"&start="+start+"&end="+end;
        window.location.href = url;
    };

    $("#id_lesson_status").val(g_lesson_status);
    $("#id_search_teacher").val(g_teacherid);
    $("#id_search_lesson_type").val(g_lesson_type);
    $("#id_date_start").val(g_start);
    $("#id_date_end").val(g_end);

	//时间控件
	$('#id_date_start').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		onChangeDateTime :function(){
            load_data();
		}
	});
	
	$('#id_date_end').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
        onChangeDateTime :function(){
            load_data();
		}
	});
	

    $("#id_lesson_status").on("change", function(){
        load_data();
    });

    $("#id_search_teacher").on("change", function(){
        load_data();
    });
    
    $("#id_search_lesson_type").on("change", function(){
        load_data();
    });



    $("#id_add_lesson").on("click", function(){

        var id_courseid = obj_copy_node("#id_courseid")  ;
        var id_lesson_num = $("<span></span>") ;
        var id_lesson_total= $("<span></span>") ;

        var date_node=$("<div><input type=\"text\" style=\"width:120px\" id=\"id_lesson_date\" />&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" style=\"width:80px\"   id=\"id_lesson_date_start_time\" />—<input type=\"text\" style=\"width:80px\"   id=\"id_lesson_date_end_time\" /><div>");
        var id_date= date_node.find("#id_lesson_date");
        var id_start= date_node.find("#id_lesson_date_start_time");
        var id_end= date_node.find("#id_lesson_date_end_time");
        var id_lesson_intro=$("<textarea> </textarea>");

        id_date.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d'
        });

        id_start.datetimepicker({
            datepicker:false,
            format:'H:i',
            step:5
        });
        id_end.datetimepicker({
            datepicker:false,
            format:'H:i',
            step:5
        });

        id_courseid.on("change" ,function (){
            var courseid = $(this).val();
            if(courseid == -1){
                $("#id_lesson_num_addl").html("");
                $("#id_lesson_total_addl").html("");
                return ;
            }
            $.ajax({
                url: '/tea_manage/get_open_course_simple_info',
                type: 'POST',
                dataType: 'json',
                data: {
                    'courseid': courseid 
			    },
                success: function(data) {
                    id_lesson_num.html(data.info.lesson_info.lesson_num);
                    id_lesson_num.data("lessonid", data.info.lesson_info.lessonid);
                    id_lesson_total.html(data.info.lesson_total);
                }
            });
        });




        var arr                = [
            [ "课程",  id_courseid] ,
            [ "课次",  id_lesson_num] ,
            [ "总课次",  id_lesson_total] ,
            [ "时间",   date_node ] ,
            [ "内容",   id_lesson_intro] ,
        ];

        show_key_value_table("新增课次", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.ajax({
                    url: '/tea_manage/add_open_lesson',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'lessonid': id_lesson_num.data("lessonid"),
                        'date' :  id_date.val(),
                        'start' : id_start.val(),
                        'end' :  id_end.val(),
                        'lesson_intro' : id_lesson_intro.val()
			        },
                    success: function(data) {
                        alert(data.info);
                        if(!data.ret){
                            window.location.reload();
                        }
                    }
                });

            }
        });
        

        
    });


    $("#id_add_open_course").on("click", function(){
        var id_name         = $("<input> </input>") ;
        var id_enter_type   = obj_copy_node("#id_enter_type") ;
        var id_lesson_type =  obj_copy_node("#id_lesson_type") ;
        var id_tea_list=  obj_copy_node("#id_tea_list") ;
        var id_lesson_total = $("<input style=\"width:50px\" value=\"10\"> </input>");
        var arr                = [
            [ "名称",  id_name ] ,
            [ "受众",  id_enter_type ] ,
            [ "类型",  id_lesson_type ] ,
            [ "老师",  id_tea_list ] ,
            [ "总课次",  id_lesson_total] ,
        ];

        show_key_value_table("新增课程", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {

                var course_name = $.trim(id_name.val());
                var enter_type = id_enter_type.val();
                var contract_type = id_lesson_type.val(); 
                var teacherid = id_tea_list.val();
                var lesson_total = id_lesson_total.val();

                if(course_name == "" || typeof enter_type == "undefined" || typeof contract_type == "undefined"
                   || teacherid == -1 || lesson_total == "")
                {
                    console.log(course_name);
                    console.log(enter_type);
                    console.log(contract_type);
                    console.log(teacherid);
                    console.log(lesson_total);
                    alert("请输入全部信息");
                    return;
                }
                $.ajax({
                    url: '/tea_manage/add_open_course',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'course_name': course_name,
                        'enter_type': enter_type,
                        'contract_type': contract_type,
                        'teacherid': teacherid,
                        'lesson_total': lesson_total 
			        },
                    success: function(data)


                    {
                        alert(data.info);
                        if(data.ret != -1){
                            window.location.reload();
                        }
                    }
                });
            }
        });
        
        
    });

    $(".opt-change-teacher").on("click", function(){
        var course_id=$(this).parent().data("courseid");
        var id_tea_list=  obj_copy_node("#id_tea_list") ;
        var arr                = [
            [ "老师",  id_tea_list ] ,
        ];

        show_key_value_table("选择老师", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var teacherid = id_tea_list.val();
                if (! (teacherid >0) ) {
                    alert("请选择老师");
                    return; 
                }
                $.ajax({
                    url: '/tea_manage/change_open_teacher',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'courseid'  :  course_id,
                        'teacherid' : teacherid
			        },
                    success: function(data) {
                        alert(data.info);
                        if(data.ret != -1){
                            window.location.reload();
                        }
                    }
                });
            }
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
                alert(data.info);
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

        var html_node       = obj_copy_node("#id_user_list" );
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



    $.each( $(".opt-set-time"),function(i,item)  {
        var $item=$(item);
        var lessonid=$item.get_opt_data("lessonid");
        $item.admin_set_lesson_time({lessonid :lessonid});
    });

});
