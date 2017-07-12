
/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-admin_group_edit.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ({
			main_type:	$('#id_main_type').val(),
			groupid:	$('#id_groupid').val()
        });
    }

    Enum_map.append_option_list("account_role", $('#id_main_type'),true );

	$('#id_main_type').val(g_args.main_type);
	$('#id_groupid').val(g_args.groupid);

    
    $("#id_edit_group").on("click",function(){
	    var id_group_name=$("<input/>");
	    var id_master_adminid=$("<input/>");
        id_master_adminid.val($("#id_group_master").data("master_adminid"));


        var  arr=[
            ["组名" ,  id_group_name],
            ["助长" ,  id_master_adminid]
        ];
       
        id_group_name.val( $("#id_groupid").find( "option:selected") .text()  );
        
        $.show_key_value_table("修改分组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_group_edit",{
                    "groupid" : g_args.groupid,
                    "group_name" : id_group_name .val(),
                    "master_adminid" : id_master_adminid.val()
                });
            }
        },function(){
            $.admin_select_user(
            id_master_adminid ,
            "admin", null,true, {
                "main_type": 2 //分配用户
            }
        );

        });

    });

    $("#id_add_group").on("click",function(){
	    var id_group_name=$("<input/>");
        var  arr=[
            ["组名" ,  id_group_name]
        ];
        
        $.show_key_value_table("新增分组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_group_add",{
                    "main_type" : g_args.main_type,
                    "group_name" : id_group_name .val()
                });
            }
        });

    });

    $("#id_del_group").on("click",function(){
        
        BootstrapDialog.confirm(
            "要删除分组:"+ $("#id_groupid").find( "option:selected") .text()   + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/admin_group_del", {
                        "groupid": g_args.groupid
                    }) ;
               }
            }
        );
    });


    $("#id_add_group_user").on("click",function(){
        $.admin_select_user( $("<div></div>") , "admin" , function (adminid){
            if (adminid>0) {
                $.do_ajax("/user_deal/admin_group_user_add",{
                    "groupid" : g_args.groupid,
                    "main_type" : g_args.main_type,
                    "adminid" :  adminid
                });
            }
        });
    });

    
    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        
        BootstrapDialog.confirm(
            "要删除:"+ opt_data.admin_nick  + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/admin_group_user_del", {
                        "groupid": g_args.groupid,
                        "adminid": opt_data.adminid
                    }) ;
               }
            }
        );

	    
    });

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
	    var id_assign_percent=$("<input/>");
        var  arr=[
            ["比例(请输入整数,如10%,输入10即可)" , id_assign_percent]
        ];
        
        $.show_key_value_table("配置比例", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/update_admin_assign_percent",{
                    "groupid": g_args.groupid,
                    "adminid": opt_data.adminid,
                    "assign_percent" : id_assign_percent.val()
                });
            }
        });

    });


	$('.opt-change').set_input_change_event(load_data);
});


