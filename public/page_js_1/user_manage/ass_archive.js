$(function(){
    Enum_map.append_option_list("grade", $("#id_grade"));
    Enum_map.td_show_desc("grade", $(".td-grade"));
    Enum_map.td_show_desc("relation_ship", $(".td-parent-type"));
    Enum_map.append_option_list("student_type", $("#id_student_type"));

    //$(td-grade)

	//init  input data
	$("#id_grade").val(g_args.grade);
	$("#id_status").val(g_args.status);
	$("#id_gift_sent").val(g_args.gift_sent);
	$("#id_lesson_left").val(g_args.lesson_left);
	$("#id_user_name").val(g_args.user_name);
	$("#id_phone").val(g_args.phone);
    $("#id_assistantid").val(g_args.assistantid);

    $("#id_student_type").val(g_args.student_type);
    $("#id_assistantid").val(g_args.assistantid);

    admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });

	//btn_s('.done_b','.mesg_alert03');//录入回访  dfa
	$(".stu_sel" ).on( "change",function(){
		load_data();  
	});
	$(".for_input").on ("keypress",function(e){
		if (e.keyCode==13){
			var field_name=$(this).data("field");
			var value=$(this).val();
			load_data();
		}
	});
    
    $("#id_search_user").on("click",function(){
        var value=$("#id_user_name").val();
		load_data();
    });

    $("#id_search_tel").on("click",function(){
        var value=$("#id_phone").val();
		load_data();
    });

	//点击进入个人主页
	$('.opt-user').on('click',function(){
		var userid=$(this).parent().data("userid");
		var nick=$(this).parent().data("stu_nick");
        window.open(
            '/stu_manage?sid='+ userid+"&return_url="+ encodeURIComponent(window.location.href)
        );
	});
	
    //点击进入排课页面
    $('.opt-lesson').on('click',function(){
		var userid=$(this).parent().data("userid");
        wopen('/stu_manage/lesson_plan?sid='+userid +"&return_url="+ encodeURIComponent(window.location.href));
    });
	
	function load_data(){
        reload_self_page ( {
            "assistantid"  : $("#id_assistantid").val(),
            "grade"        : $("#id_grade").val(),
            "student_type" : $("#id_student_type").val(),
            "userid"       : $("#id_user_name").val(),
            "phone"        : $("#id_phone").val()
        });
    }


    // 设置学生临时密码
    $(".opt-modify").on("click", function(){
        var html_node =$("<div></div>").html(dlg_get_html_by_class('dlg_set_dynamic_passwd'));

        html_node.find(".stu_phone").text($(this).parents("td").siblings(".user_phone").text());
        html_node.find(".stu_nick").text($(this).parents("td").siblings(".user_nick").text());
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

    $('.opt-test-room').on('click', function(){

        var phone = $(this).parents("td").siblings(".user_phone").text();
        
		$.ajax({
			type     :"post",
			url      :"/user_manage/get_test_room",
			dataType :"json",
			data     :{"phone":phone },
			success  : function(result){
                if (result['ret'] != 0) {
                    BootstrapDialog.alert(result['info']);
                } else {
                    var msg = '';
                    if (result['test_room'] == '') {
                        msg = '是否设置试音室';
                    } else {
                        msg = '是否取消试音室'+result['test_room'];
                    }
                    BootstrapDialog.show({
                        title: '设置试音室',
                        message : msg,
                        closable: true, 
                        buttons: [
                            {
                                label: '确认',
                                cssClass: 'btn-primary',
                                action: function(dialog) {

		                            $.ajax({
			                            type     :"post",
			                            url      :"/user_manage/set_test_room",
			                            dataType :"json",
			                            data     :{"phone":phone },
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
                    
                }
			}
        });
        
    });


    Enum_map.append_option_list("student_type",$("#id_set_channel"),true);
    $(".opt-change-type").on("click", function(){
		var userid = $(this).parent().data("userid");
		var nick   = $(this).parent().data("nick");
        
        var html_node=$('<div></div>').html(dlg_get_html_by_class('cl_dlg_change_type'));
        html_node.find("#id_set_channel").val();
        
        BootstrapDialog.show({
            title: '设置学员类型',
            message : html_node,
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
		            var stu_type     = html_node.find("#id_set_channel").val();
		            $.ajax({
			            type     :"post",
			            url      :"/user_manage/set_stu_type",
			            dataType :"json",
			            data     :{
                            "userid":userid,
                            "type":stu_type
                        },
			            success  : function(result){
                            if(result['ret'] != 0){
                                alert(result['info']);
                            }else{
                                window.location.reload();
                            }
			            }
		            });
                }
            }]
        }); 
	});
     $(".opt-left-time").on("click", function(){
        var studentid = $(this).parent().data("userid");
        $.ajax({
            url: '/user_deal/reset_lesson_count',
            type: 'POST',
            dataType: 'json',
            data:{
                'studentid' : studentid
            },
            success: function(data) {
                if(data.ret != -1){
                    window.location.reload();
                }
            }
        });
        
    });
   

});
