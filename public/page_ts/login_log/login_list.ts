/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/login_log-login_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            account:	$('#id_account').val(),
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
    $('#id_account').val(g_args.account);
    $('.opt-change').set_input_change_event(load_data);

    $("#id_add").on("click",function(){
        var $account= $("<input/>" );
        var $server_ip= $("<input/>" );
        var $login_ip= $("<input/>" );
        var $login_succ_flag= $("<select/>" );
        var arr=[
             ["用户" ,$account  ],
             ["server_ip", $server_ip],
             ["login_ip", $login_ip],
             ["成功/失败", $login_succ_flag],
        ] ;
        Enum_map.append_option_list("islogin",$login_succ_flag,true);
          $.show_key_value_table("新增", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/login_log/login_add",{
                    "account" : $account.val(),
                    "server_ip" :$server_ip.val(),
                    "login_ip" :$login_ip.val(),
                    "login_succ_flag" :$login_succ_flag.val()
                });
            }
        });

    });
    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var $account= $("<input/>" );
        var $server_ip= $("<input/>" );
        var $login_ip= $("<input/>" );
        var $login_succ_flag= $("<select/>" );
        Enum_map.append_option_list("islogin", $login_succ_flag, true);
        var arr=[
            ["用户" ,$account  ],
            ["server_ip" ,$server_ip  ],
            ["login_ip" ,$login_ip  ],
            ["失败/成功" ,$login_succ_flag  ],
        ] ;
        $account.val(opt_data.account );
        $server_ip.val(opt_data.server_ip );
        $login_ip.val(opt_data.login_ip );
        $login_succ_flag.val(opt_data.login_succ_flag );

        $.show_key_value_table("修改", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/login_log/login_edit",{
                    "id" : opt_data.id,
                    "account" : $account.val(),
                    "server_ip" : $server_ip.val(),
                    "login_ip" : $login_ip.val(),
                    "login_succ_flag" : $login_succ_flag.val()
                });
            }
        });

    });

   /* $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var arr=[
        ] ;

        $.show_key_value_table("删除",arr,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/login_log/login_del",{
                    "id" : opt_data.id
                });
            }
        });

        });*/

    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除? 用户:" + opt_data.account,
            function(val){
                if (val) {
                    $.do_ajax("/login_log/login_del",{
                    "id" : opt_data.id
                   });
                }
            });
    });


});
