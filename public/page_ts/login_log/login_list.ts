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
        var $serverip= $("<input/>" );
        var $type= $("<input/>" );
        var arr=[
             ["用户" ,$account  ],
             ["serverip", $serverip],
             ["登录状态", $type],
        ] ;
          $.show_key_value_table("新增", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/login_log/login_add",{
                    "account" : $account.val(),
                    "serverip" :$serverip.val(),
                    "type" :$type.val()
                });
            }
        });

    });
    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var $account= $("<input/>" );
        var $serverip= $("<input/>" );
        var $type= $("<input/>" );
        //var $type= $("<select/>" );
        Enum_map.append_option_list("type", $type, true);

        var arr=[
            ["用户" ,$account  ],
            ["serverip" ,$serverip  ],
            ["type" ,$type  ],
        ] ;
        $account.val(opt_data.account );
        $serverip.val(opt_data.serverip );
        $type.val(opt_data.type );

        $.show_key_value_table("修改", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax_t("/login_log/login_edit",{
                    "id" : opt_data.id,
                    "account" : $account.val(),
                    "serverip" : $serverip.val(),
                    "type" : $type.val()
                });
            }
        });

    });

    $(".opt-del").on("click",function(){
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

      });


});
