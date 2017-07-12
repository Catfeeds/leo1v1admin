$(function(){
    Enum_map.append_option_list("grade", $("#id_grade"));
    Enum_map.append_option_list("test_user", $("#id_test_user"));
    Enum_map.append_option_list("stu_origin", $("#id_originid"));
    Enum_map.td_show_desc("grade", $(".td-grade"));
    Enum_map.td_show_desc("relation_ship", $(".td-parent-type"));

	$("#id_grade").val(g_args.grade);
	$("#id_test_user").val(g_args.test_user);
	$("#id_originid").val(g_args.originid);
	$("#id_user_name").val(g_args.user_name);
	$("#id_phone").val(g_args.phone);
	$("#id_seller_adminid").val(g_args.seller_adminid);
	$("#id_order_type").val(g_args.order_type);

    $("#id_teacherid").val(g_args.teacherid);
    $("#id_assistantid").val(g_args.assistantid);

    admin_select_user($("#id_teacherid"), "teacher",function(){
        load_data();
    });
    
    admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });
    admin_select_user($("#id_seller_adminid"), "admin",function(){
        load_data();
    });

    
	$(".stu_sel" ).on( "change",function(){
		load_data();
	});
	$(".opt-change" ).on( "change",function(){
		load_data();
	});


	$(".for_input").on ("keypress",function(e){
		if (e.keyCode==13){
			var field_name=$(this).data("field");
			var value=$(this).val();
			if (field_name=="user_name" ){
				load_data();
			}else{
				load_data();
			}
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
		var userid = $(this).parent().data("userid");
		var nick   = $(this).parent().data("stu_nick");
        window.open(
            '/stu_manage?sid='+ userid+"&return_url="+ encodeURIComponent(window.location.href)
        );
	});
	
    //点击进入排课页面
    $('.opt-lesson').on('click',function(){
		var userid=$(this).parent().data("userid");
        wopen('/stu_manage/lesson_plan_edit?sid='+userid +"&return_url="+ encodeURIComponent(window.location.href));
    });
	
	function load_data(){
        reload_self_page({
            test_user   : $("#id_test_user").val(),
            originid    : $("#id_originid").val(),
            grade       : $("#id_grade").val(),
            user_name   : $("#id_user_name").val(),
            phone       : $("#id_phone").val(),
            teacherid   : $("#id_teacherid").val(),
            assistantid : $("#id_assistantid").val(),
            order_type : $("#id_order_type").val(),
            seller_adminid : $("#id_seller_adminid").val()
        });
	}

    //设置是否为测试用户
    Enum_map.append_option_list("test_user",$("#id_set_channel"),true);
    $(".opt-test-user").on("click", function(){
		var userid = $(this).parent().data("userid");
		var nick   = $(this).parent().data("nick");
        
        var html_node=$('<div></div>').html(dlg_get_html_by_class('cl_dlg_change_type'));
        html_node.find("#id_set_channel").val();
        
        BootstrapDialog.show({
            title: '设置测试用户',
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
		            var user_type     = html_node.find("#id_set_channel").val();
		            $.ajax({
			            type     :"post",
			            url      :"/user_manage/set_test_user",
			            dataType :"json",
			            data     :{
                            "userid":userid,
                            "type":user_type
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
    


    $(".opt-set-spree").on("click", function(){
        var studentid = $(this).parent().data("userid");

        var id_spree = $("<input />");
        var arr = [
            [ "设置大礼包",id_spree] 
        ];

        show_key_value_table("设置大礼包", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                do_ajax("/user_manage/set_spree", {
                    'studentid' : studentid,
                    'spree'     : id_spree.val()
                });

            }
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
    //设置是否为渠道来源
    Enum_map.append_option_list("stu_origin",$("#id_stu_origin"),true);
    $(".opt-stu-origin").on("click", function(){
        var id_stu_origin    = obj_copy_node("#id_stu_origin");
        var id_origin_userid = $("<input/>");
        var id_origin_str    = $("<input/>");
        var userid           = $(this).get_opt_data('userid');
       
        var arr = [
            [ "设置渠道", id_stu_origin ] ,
            [ "渠道(2)", id_origin_str ] ,
            [ "userid", id_origin_userid] ,
        ];

        id_stu_origin.on("change",function(){
            var val=id_stu_origin.val();
           if(val == 1){
               id_origin_userid.parent().parent().show();
           }else{
               id_origin_userid.parent().parent().hide();
           }

        });

       show_key_value_table("设置渠道", arr ,{
           label: '确认',
           cssClass: 'btn-warning',
           action: function(dialog) {
                var originid      = id_stu_origin.val();
                var origin        = id_origin_str.val();
                var origin_userid = id_origin_userid.val();
                $.ajax({
                    url: '/user_manage/set_stu_origin',
                    type: 'POST',
                    dataType: 'json',
                    data : {
                        'originid'      : originid,
                        'origin'        : origin,
                        'origin_userid' : origin_userid,
                        'userid'        : userid
			        },
                    success: function(data) {
                        if(data.ret != -1){
                            window.location.reload();
                        }
                    }
                });

            }
       },function(){
           if(id_stu_origin.val() == 2){
               id_origin_userid.parent().parent().show();
           }else{
               id_origin_userid.parent().parent().hide();
           }

       });
        id_origin_userid.admin_select_dlg_ajax({
            "opt_type" :  "select", // or "list"
            "url"          : "/user_manage/get_user_manage_list_for_js",
            //其他参数
            "args_ex" : {
                //type  :  "student"
            },

            select_primary_field : "userid",
            select_display       : "nick",
            select_no_select_value  :  0  , // 没有选择是，设置的值 
            select_no_select_title  :  "[未设置]"  , // "未设置"

            //字段列表
            'field_list' :[
                {
                    title:"userid",
                    width :50,
                    field_name:"userid"
                },{
                    title:"学生姓名",
                    field_name:"nick"
                },{
                    title:"手机号码",
                    field_name:"phone"
                }
            ] ,
            //查询列表
            filter_list : [
               
            ],

            "auto_close" : true,
            //选择
            "onChange"   : null,
            "onLoadData" : null

        });



	});


    // 设置学生临时密码
    $(".opt-modify").on("click", function(){
        var html_node =$("<div></div>").html(dlg_get_html_by_class('dlg_set_dynamic_passwd'));

        html_node.find(".stu_phone").text($(this).parents("td").siblings(".user_phone").text());
        html_node.find(".stu_nick").text($(this).parents("td").siblings(".user_nick").text());
        html_node.find(".dynamic_passwd").val("123456");
        
        BootstrapDialog.show({
            title: '设置家长登陆密码',
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

    




    
});
