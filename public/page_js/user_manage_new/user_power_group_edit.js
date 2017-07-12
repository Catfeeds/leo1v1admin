/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-user_power_group_edit.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			role:	$('#id_role').val(),
			groupid:	$('#id_groupid').val()
        });
    }

	$('#id_role').val(g_args.role);
	$('#id_groupid').val(g_args.groupid);

	$('.opt-change').set_input_change_event(load_data);

    $("#id_del_group").on("click",function(){
        BootstrapDialog.confirm("要删除当前角色?!",function(ret){
            if (ret){
                $.do_ajax( "/user_manage_new/power_group_del",{
                    groupid : g_args.groupid
                });
            }
        });
    });

    $("#id_edit_group").on("click",function(){
        var v=$("#id_groupid").find("option:selected").text(); 

        $.show_input("修改角色名",  v, function(val){
            val=$.trim(val);
            if (!val) {
                alert("名称不能为空");
            }else{
                $.do_ajax( "/user_manage_new/power_group_set_name",{
                    "groupid" : g_args.groupid,
                    "group_name" : val
                });
            }
        });
    });

    $("#id_add_group").on("click",function(){
        BootstrapDialog.confirm("要新增角色?!",function(ret){
            if (ret){
                $.do_ajax( "/user_manage_new/power_group_add",{
                });
            }
        });
    });

    $("#id_add_user").on("click",function(){
        $.admin_select_user($("#id_add_user"), "admin",function(val){
            $.do_ajax("/user_manage_new/opt_accont_group",{
                "uid" : val ,
                "groupid" : g_args.groupid ,
                "opt_type" :"add"
            });
        });
    });

});
