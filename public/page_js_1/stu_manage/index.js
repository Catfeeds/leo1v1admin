$(function(){

    Enum_map.td_show_desc("subject", $(".td-subject"));
    $("#id_user_tmp_passwd" ).on("click",function(){
	        
        var html_node =$("<div></div>").html(dlg_get_html_by_class('dlg_set_dynamic_passwd'));

        html_node.find(".stu_phone").text( g_phone );
        html_node.find(".stu_nick").text(g_nick);
        html_node.find(".dynamic_passwd").val("123456");

        BootstrapDialog.show({
            title: '设置学生动态登陆密码',
            message : html_node,
            closable: true, 
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        var phone = html_node.find(".stu_phone").text();
                        var passwd = html_node.find(".dynamic_passwd").val();

		                $.ajax({
			                type     :"post",
			                url      :"/user_manage/set_dynamic_passwd",
			                dataType :"json",
			                data     :{"phone":phone, "passwd": passwd, "role": 1 },
			                success  : function(result){
                                BootstrapDialog.alert(result['info']);
			                }
                        });
                        dialog.close();
                    }
                },
                {
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });
    });

    $('#id_add_user_parent').on('click', function(){
        var studentid = $(this).parent().data('studentid');
        var $phone=$("<input/>");
        do_ajax("/stu_manage/get_stu_parent",{
            'studentid':studentid
        },function(result){
            if(result.phone!=0){
                $phone.val(result.phone);
            }

            var arr=[
                ["家长电话",$phone]
            ];

            show_key_value_table("绑定家长", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.ajax({
                        url: '/stu_manage/set_stu_parent',
                        type: 'POST',
                        dataType: 'json',
                        data : {
                            'studentid' : studentid,
                            'phone'     : $phone.val()
			            },
                        success: function(data) {
                            if(data.ret==0){
                                window.location.reload();
                            }else{
                                BootstrapDialog.alert(data.info);
                            }
                        }
                    });
                }
            });
        });
    });

	$(' .opt-select-teacher').on('click',function(){
        var courseid =  $(this).parent().data("courseid");
        $(this).admin_select_user({
            "type":"teacher",
            "show_select_flag":true,
            "onChange":function(val){
                var id = val;
                do_ajax( '/stu_manage/change_manage_id',
                    {
				        'courseid': courseid, 
				        'opt_type': "teacher",
                        'userid'  : g_sid,
				        'opt_id': id 
			        });
            }
        });
    });


	$('.opt-select-assistant  ').on('click',function(){
        var courseid =  $(this).parent().data("courseid");
        $(this).admin_select_user({
            "show_select_flag":true,
            "type":"assistant",
            "onChange":function(val){
                var id = val;
                do_ajax( '/stu_manage/change_manage_id',
                    {
				        'courseid': courseid, 
				        'opt_type': "assistant",
                        'userid'  : g_sid,
				        'opt_id': id 
			        },function(){
                        
                    });
            }
        });
	});



    
    $(".opt-show-more").on("click",function(){
	    //
	    
    });




	$('#id_save_stu_info').on('click',function(){
        var stu_nick     = $('#user_info').find('.edit_b').eq(0).val();  //昵称
        var parent_name  = $('#user_info').find('.edit_b').eq(1).val();  //家长姓名
        var parent_phone = $('#user_info').find('.edit_b').eq(2).val();  //家长电话
        var stu_phone    = $('#user_info').find('.edit_b').eq(3).val();  //学生电话
        var address      = $('#user_info').find('.edit_b').eq(4).val();  //地址
        var school       = $('#user_info').find('.edit_b').eq(5).val();  //学校
        var region       = $('#user_info').find('.edit_b').eq(6).val();  //地区

        var parent_type = $("#relationship").val();    //关系
        var grade = $("#gradeId").val();    //关系
        var editionid    = $("#textbook").val();                //教材版本
        var textbook = $("#textbook").find("option:selected").text();
        var sexy = $("#sexy").val();                
        $.ajax({
            url: '/stu_manage/change_stu_info',
            type: 'POST',
            data: {'studentid': g_sid,'stu_nick': stu_nick,'parent_name': parent_name,'parent_phone': parent_phone,'stu_phone': stu_phone ,'address': address,'school': school,'parent_type': parent_type,'textbook':textbook,'editionid': editionid , "sexy":sexy, "region":region, "grade":grade },
            dataType: 'json',
            success: function(data) {
                if (data['ret'] != 0) {
                    alert(data['info']);
                }else{
                    window.location.reload();
                }
                
            }
        });

	});

    $("#id_set_user").on("click",function(){
        var html_node = dlg_need_html_by_id("id_dlg_set_user" );
        html_node.find("#id_name").val($(".d-name").html());
        html_node.find("#id_gender").val($(".d-gender").data("value"));
        html_node.find("#id_parent_name").val($(".d-parent-name").html());
        html_node.find("#id_birth").val($(".d-birth").html());
        html_node.find("#id_parent_type").val($(".d-parent-type").data("value"));
        html_node.find("#id_parent_phone").val($(".d-parent-phone").html());
        html_node.find("#id_stu_phone").val($(".d-stu-phone").html());
        html_node.find("#id_address").val($(".d-address").html());
        html_node.find("#id_school").val($(".d-school").html());
        html_node.find("#id_grade").val($(".d-grade").data("value"));
        html_node.find("#id_textbook").val($(".d-textbook").data("value"));
        html_node.find("#id_region").val($(".d-region").html());
        
        html_node.find("#id_birth").datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
	    });
        
        BootstrapDialog.show({
            title: '修改用户数据',
            message :html_node ,
            closable: false, 
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var stu_nick     = dlg_get_val_by_id("id_name") ;  //昵称
                    var parent_name  = dlg_get_val_by_id("id_parent_name") ;  //家长姓名
                    var parent_phone = dlg_get_val_by_id("id_parent_phone") ; //家长电话
                    var stu_phone    = dlg_get_val_by_id("id_stu_phone") ;//学生电话
                    var address      = dlg_get_val_by_id("id_address") ;  //地址
                    var school       = dlg_get_val_by_id("id_school") ;//学校
                    var region       = dlg_get_val_by_id("id_region") ; //地区
                    var parent_type  = dlg_get_val_by_id("id_parent_type");   //关系
                    var grade        = dlg_get_val_by_id("id_grade");  //关系
                    var editionid    = dlg_get_val_by_id("id_textbook");             //教材版本
                    var textbook     = dlg_get_item("#id_textbook").find("option:selected").text();
                    var sexy         = dlg_get_val_by_id("id_gender");  
                    var birth        = dlg_get_val_by_id("id_birth");
                    $.ajax({
                        url: '/stu_manage/change_stu_info',
                        type: 'POST',
                        data: {'studentid': g_sid,'stu_nick': stu_nick,'parent_name': parent_name,'parent_phone': parent_phone,'stu_phone': stu_phone ,'address': address,'school': school,'parent_type': parent_type,'textbook':textbook,'editionid': editionid , "sexy":sexy, "region":region, "grade":grade, 'birth':birth },
                        dataType: 'json',
                        success: function(data) {
                            if (data['ret'] != 0) {
                                alert(data['info']);
                            }else{
                                window.location.reload();
                            }
                            
                        }
                    });
                }
            }]
        }); 
    });


	
	
    $(".opt-lesson-require").on("click", function(){
        var orderid = $(this).parent().data('orderid');
        $.ajax({
            url: '/stu_manage/get_arrange_require',
            type: 'POST',
            data: {
				'orderid':orderid
			},
            dataType: 'json',
            success: function(data) {
                if (data['ret'] == 0) {

                    BootstrapDialog.show({
                        title: '排课要求',
                        message : $("<textarea id=\"id_note\" class=\"form-control\" style=\"height:150px\" />").val( data['requirement']),
                        closable: false, 
                        buttons: [{
                            label: '返回',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }, {
                            label: '确认',
                            cssClass: 'btn-warning',
                            action: function(dialog) {
		                        var note = $.trim(dlg_get_val_by_id("id_note") );
                                $.ajax({
                                    url: '/stu_manage/set_arrange_require',
                                    type: 'POST',
                                    data: {
				                        'orderid':orderid,
                                        'requirement':note
			                        },
                                    dataType: 'json',
                                    success: function(data) {
                                        if (data['ret'] == 0) {
                                            window.location.reload();
                                        }
                                    }
                                });
                            }
                        }]
                    }); 

                }
            }
        });
    });
    

    
    $(".opt-lesson-bell").on("click", function(){
        var orderid = $(this).parent().data('orderid');
        BootstrapDialog.show({
            title: '课前音频',
            message : "确认插入课前视频?!",
            closable: false, 
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.ajax({
                        url: '/stu_manage/set_course_begin_audio',
                        type: 'POST',
                        data: {
				            'orderid':orderid
			            },
                        dataType: 'json',
                        success: function(data) {
                            if (data['ret'] == 0) {
                                window.location.reload();
                            }else{
                                alert(data['info']);
                                window.location.reload();
                            }
                        }
                    });
                }
            }]
        }); 


    });

    $(".opt-set-server").on("click", function(){

        var courseid=$(this).get_opt_data("courseid");
        $.ajax({
            url: '/stu_manage/get_course_server',
            type: 'POST',
            data: {
				'courseid':courseid
			},
            dataType: 'json',
            success: function(data) {
                if(data['ret'] == 0){
                    var html_node=dlg_need_html_by_id( "id_dlg_set_server") ;
                    html_node.find("#id_region").val(data['info'][0]);
                    html_node.find("#id_server").val(data['info'][1]);
                    BootstrapDialog.show({
                        title: '选择服务器',
                        message : html_node, 
                        buttons: [{
                            label: '返回',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }, {
                            label: '确认',
                            cssClass: 'btn-warning',
                            action: function(dialog) {
                                var region = html_node.find("#id_region").val();
                                var server = html_node.find("#id_server").val();
                                if(region == -1 || server == -1){
                                    alert("请选择地区以及服务器!");
                                    return;
                                }
                                $.ajax({
                                    url: '/stu_manage/set_course_server',
                                    type: 'POST',
                                    data: {
				                        'courseid':courseid,
                                        'region'  :region,
                                        'id'  :server
			                        },
                                    dataType: 'json',
                                    success: function(data) {
                                        if (data['ret'] == 0) {
                                            window.location.reload();
                                        }else{
                                            alert(data['info']);
                                        }
                                    }
                                });

                            }
                        }]
                    }); 


                }
            }
        });
    });

    
    $(".opt-set-course-name").on("click",function(){

        var courseid=$(this).get_opt_data("courseid");
	    //
        show_input( "设置课程名称" , ""  , function(val){
            do_ajax('/course_manage/set_course_name', {
                'courseid': courseid,
                'course_name': val 
            });
        });
    });


    $("#id_set_seller_adminid").on("click",function(){
        $(this).admin_select_user({
            "show_select_flag":true,
            "type":"admin",
            "onChange":function(val){
                var id = val;
                do_ajax( '/user_deal/student_set_seller',
                    {
                        'studentid'  : g_sid,
				        'seller_adminid': id 
			        });
            }
        });

        
    }) ;

    $("#id_set_assistantid").on("click",function(){
        $(this).admin_select_user({
            "show_select_flag":true,
            "type":"assistant",
            "onChange":function(val){
                var id = val;
                do_ajax( '/stu_manage/set_assistantid',
                    {
                        'sid'  : g_sid,
				        'assistantid': id
			        });
            }
        });

        
    }) ;

    
    $(".opt-set-status").on("click",function(){
        var orderid = $(this).get_opt_data("orderid");
        var contract_status = $(this).get_opt_data("contract_status");
        if ( contract_status ==1 || contract_status ==2  ) {
            
            /*
 		     1 => "执行中",
		     2 => "已结束",
             */
            var $contract_status=$("<select/>");

            Enum_map.append_option_list("contract_status",$contract_status,true,[1,2] );
            var arr=[
                ["合同id" , orderid] ,
                ["状态" , $contract_status] ,
            ];
            $contract_status.val(contract_status);

            show_key_value_table("课程状态", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    do_ajax( "/user_deal/course_set_status", {
                        "contract_status" : $contract_status.val(),
                        'orderid': orderid
                    },function(data){
                        if (data.ret !=0 ) {
                            alert(data.info);
                        }else{
                            alert("成功");
                            window.location.reload();
                        }
                    }) ;
                }
            });


        }else{
            alert("不是正式合同");
        }
        
	    
    });
    
    
});

