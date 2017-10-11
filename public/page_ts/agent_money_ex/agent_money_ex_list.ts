/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_luki-test_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
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


    $('.opt-change').set_input_change_event(load_data);

    $("#id_add").on("click",function(){
        var $agent_money_ex_type= $("<select/>" );
        Enum_map.append_option_list("agent_money_ex_type", $agent_money_ex_type,true);

        // var $agent_money_ex_type = $("<input/>");
        var $agent_id = $("<input/>");
        var $money = $("<input/>");
        var arr=[
            ["说明" ,$agent_money_ex_type ],
            ["用户id" ,$agent_id ],
            ["金额[元]" ,$money ]
        ] ;

        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/agent_money_ex/agent_add",{
                    "agent_money_ex_type" : $agent_money_ex_type.val(),
                    "agent_id" : $agent_id.val(),
                    "money" : $money.val(),
                });
            }
        });

    });

    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除用户:" + opt_data.phone+ opt_data.agent_money_ex_type_str+"金额："+opt_data.money+"元的记录吗？"  ,
            function(val){
                if (val) {
                     $.do_ajax("/agent_money_ex/agent_money_ex_del",{
                         "id" : opt_data.id
                     });

                }
            });

    });

});
