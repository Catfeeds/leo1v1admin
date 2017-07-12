
$(function(){

    $("#id_user_info").val(g_args.user_info);
    $("#id_has_question_user").val(g_args.has_question_user);
    
    $("#id_search_user").on("click",function(){
        var user_info = $("#id_user_info").val();
        if(user_info==""){
            alert("请输入查找用户名称!");
        }else{
            var url = "/authority/manager_list?user_info="+user_info;
            window.location.href=url;
        }
    });

    $("#id_user_info").on("keypress",function(e){
        if(e.keyCode == 13){
            var user_info = $("#id_user_info").val();
            if(user_info==""){
                alert("请输入查找用户名称!");
            }else{
                var url = "/authority/manager_list?user_info="+user_info;
                window.location.href=url;
            }
        }
    });
    
    $("#id_has_question_user").on("change",function(){
	    //
        var url = "/authority/manager_list?has_question_user="+ $("#id_has_question_user").val() ;
        window.location.href=url;
	    
    });


    $("#id_add_manager").on("click",function(){
        var account = $("#id_account").val();
        var name = $("#id_real_name").val();
        var email = $("#id_email").val();
        var phone = $("#id_phone").val();
        var passwd = $("#id_passwd").val();
        $.ajax({
            url: '/authority/add_manager',
            type: 'POST',
            data: {
				'account' : account, 
				'name'    : name,
				'email'   : email,
				'phone'   : phone,
                'passwd'  : passwd
				},
            dataType: 'json',
            success: function(data) {
                if (data['ret'] == 0) {
                    alert("插入成功");
                    window.location.reload();
                }else if(data['ret'] != 0){
                    alert(data['info']);
                }
            }
        });
    });

    $(".done_t").on("click",function(){
        var account = $(this).parent().data("account");
        var name = $(this).parent().data("name");
        BootstrapDialog.show({
            title: '删除管理员',
            message : "确认从系统删除管理员"+name+"("+account+")",
            closable: true, 
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn btn-warning',
                    action: function(dialog) {
                        $.ajax({
                            url: '/authority/del_manager',
                            type: 'POST',
                            data: {
				                'account': account
				            },
                            dataType: 'json',
                            success: function(data) {
                                if (data['ret'] == 0) {
                                    window.location.reload();
                                }else if(data['ret'] != 0){
                                    alert(data['info']);
                                }
                            }
                        });
                        dialog.close();
                    }
                },
                {
                    label: '取消',
                    cssClass: 'btn btn-primary',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });
    });

    $(".opt-set-passwd").on("click", function(){
        var $passwd=$("<input/>");
        var account=$(this).get_opt_data("account");
        var arr =[
            ["account", account ] ,
            ["passwd", $passwd] 
        ];
        $passwd.val(account);

        show_key_value_table("新增小班课", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax('/authority/set_passwd', {
				    'account': account,
                    'passwd': $passwd.val() 
				});
            }
        });
    });

    $("#id_fix_passwd").on("click",function(){
        var account = $(this).data("account");
        var new_passwd = $("#id_new_passwd").val();

        if(new_passwd == ""){
            alert("请输入新密码");
        }else{
             $.ajax({
                 url: '/authority/set_passwd',
                 type: 'POST',
                 data: {
				     'account': account,
                     'passwd': new_passwd
				 },
                 dataType: 'json',
                 success: function(data) {
                     if(data['ret'] != 0){
                         alert(data['info']);
                     }else{
                         window.location.href = "/authority/manager_list";
                     }
                 }
             });
        }
    });

    $(".add_player").on("click", function(){
        var html_node = $("<div></div>").html(dlg_get_html_by_class("dlg_add_manager"));

        BootstrapDialog.show({
            title: '新增用户',
            message : html_node,
            closable: true, 
            buttons: [
                {
                    label: '提交',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        var account = html_node.find(".username").val();
                        var name    = html_node.find(".realname").val();
                        var email   = html_node.find(".email").val();
                        var phone   = html_node.find(".phone").val();
                        var passwd  = html_node.find(".password").val();
                        $.ajax({
                            url: '/authority/add_manager',
                            type: 'POST',
                            data: {
				                'account': account, 
				                'name': name,
				                'email': email,
				                'phone': phone,
                                'passwd': passwd
				            },
                            dataType: 'json',
                            success: function(data) {
                                if (data['ret'] == 0) {
                                    alert("插入成功");
                                    window.location.reload();
                                }else if(data['ret'] != 0){
                                    alert(data['info']);
                                }
                            }
                        });
                    }
                },
                {
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }
            ]});
    });
    
    //编辑lala
    $(".edit-manage").on("click",function(){
        var uid= $(this).parent().data("uid");
        $.ajax({
            type     :"post",
			url      :"/authority/get_show_manage_info",
			dataType :"json",
			data     :{
                "uid" : uid
            },
            success: function(result){
                var item = result.ret_info; 
                var $phone=$("<input/> ").val(item.phone);
                var $email=$("<input/>").val(item.email);
                var $name=$("<input/>").val(item.name);
                var arr=[
                    ["uid",item.uid] ,
                    ["account",item.account] ,
                    ["姓名", $name],
                    ["电话",$phone] ,
                    ["邮件",$email] 
                ];
                
                show_key_value_table("修改用户信息", arr ,{
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        do_ajax('/user_deal/update_admin_info', {
				            'uid': item.uid,
				            'phone': $phone.val(),
				            'name': $name.val(),
				            'email': $email.val()
				        });
                    }
                });

                
            }
        });
    });

  // ===================== 
   
    $(".set-account-role").on("click", function(){
        var account = $(this).get_opt_data("account");
        do_ajax ( "/authority/get_account_role", {
            "account" : account
        },function(result){
            var id_update_account_role   = $("<select/>");

            Enum_map.append_option_list("account_role",id_update_account_role);

            var arr               = [
                [ "角色", id_update_account_role] ,
            ];
            id_update_account_role.val(result.ret_info);

            show_key_value_table("设置角色", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var role = id_update_account_role.val();

                    $.ajax({
                        url: '/authority/set_account_role',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            'account'      : account,
                            'account_role' : role
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




    });

});
