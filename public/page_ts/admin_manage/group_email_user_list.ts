/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-group_email_user_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            groupid: g_args.groupid,
            adminid:	$('#id_adminid').val()
        });
    }

    $('#id_adminid').val(g_args.adminid);



    $.admin_select_user(
        $('#id_adminid'),
        "admin", load_data);


    $('.opt-change').set_input_change_event(load_data);


    $("#id_add").on("click",function(){
        var $adminid=$("<input/>");
        var arr=[
            ["账号", $adminid ] ,
        ];


        $.show_key_value_table("添加组邮件", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal2/email_group_user_add" , {
                    "groupid" : g_args.groupid,
                    adminid: $adminid.val()
                } );
            }
        },function(){
            $.admin_select_user($adminid, "admin" );
        });
    });


    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();

        BootstrapDialog.confirm( "要删除 " + opt_data.account + ":" + opt_data.email +"?", function(val){
            if (val)  {
                $.do_ajax("/ajax_deal2/email_group_user_del" , {
                    "groupid" : g_args.groupid,
                    adminid: opt_data.adminid
                } )

            }
        });

    });


    $("#id_sync").on("click",function(){

        $.do_ajax("/ajax_deal2/sync_group_email" , {
            "groupid" : g_args.groupid
        } );

        alert("等待30秒");


    });

});
