/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-kaoqin_machine_adminid.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            machine_id:	$('#id_machine_id').val(),
            adminid:	$('#id_adminid').val(),
            auth_flag:	$('#id_auth_flag').val()
        });
    }

    Enum_map.append_option_list("boolean",$("#id_auth_flag"));

    $('#id_machine_id').val(g_args.machine_id);
    $('#id_adminid').val(g_args.adminid);
    $('#id_auth_flag').val(g_args.auth_flag);

    $.admin_select_user( $('#id_adminid'), "admin", load_data );

    $('.opt-change').set_input_change_event(load_data);



    $("#id_add").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if ( g_args.machine_id== -1  ) {
            alert("请选择考勤机");
            return;
        }

        var $adminid=$("<input/>");
        var arr=[
            ["账号" ,  $adminid],
        ];


        $.show_key_value_table("修改", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/add_kaoqin_machine_adminid",{
                    "machine_id" : g_args.machine_id ,
                    "adminid" : $adminid.val()
                });
            }
        },function(){
            $.admin_select_user( $adminid, "admin");
        });



    });


    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var $auth_flag =$("<select/>");
        Enum_map.append_option_list( "boolean", $auth_flag, true );

        var arr=[
            ["机器" ,  opt_data.title ],
            ["账号" ,  opt_data.admin_nick ],
            ["管理员" ,  $auth_flag ],
        ];
        $auth_flag.val( opt_data.auth_flag );


        $.show_key_value_table("修改", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/set_kaoqin_machine_adminid",{
                    "machine_id" : opt_data.machine_id ,
                    "adminid" :  opt_data.adminid,
                    "auth_flag"  :  $auth_flag.val()
                });
            }
        },function(){

        });


    });


    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm("要删除:"+ opt_data.title  + "-" + opt_data.admin_nick , function(val){
            if (val) {
                $.do_ajax("/user_deal/del_kaoqin_machine_adminid",{
                    "machine_id" : opt_data.machine_id ,
                    "adminid" :  opt_data.adminid,
                });
            }
        });

    });


    $(".opt-unlock").on("click",function(){
        var opt_data=$(this).get_opt_data();

    });


    $("#id_sync").on("click",function(){
        $.do_ajax("/ajax_deal2/sync_kaoqin_machine",{
            "machine_id" : $("#id_machine_id").val()
        });

    });


});
