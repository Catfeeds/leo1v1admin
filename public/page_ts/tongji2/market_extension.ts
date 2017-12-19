/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-market_extension.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        order_by_str : g_args.order_by_str,
        type:	$('#id_type').val(),
        date_type_config:	$('#id_date_type_config').val(),
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val()
    });
}
$(function(){
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

    Enum_map.append_option_list("market_gift_type",$("#id_type"));
    $('#id_type').val(g_args.type);

    $('#id_add').on("click", function (g_adminid_right) {
        var opt_data=$(this).get_opt_data();

        var $main_type_name = $("<select/>");
        var $title = $("<textarea/>");
        var $describe = $("<textarea/>");

        Enum_map.append_option_list("market_gift_type", $main_type_name,true);

        //处理key
        $.do_ajax("/user_deal/seller_init_group_info", {
        }, function (ret) {
        });

        var arr = [
            ["礼品类型", $main_type_name],
            ["标题", $title],
            ["活动描述", $describe],
            ["活动图片", $img],
        ];

        $.show_key_value_table("添加推广活动", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {

                $.do_ajax("/ss_deal/add_refund_complaint",{
                    // 'apply_time' : opt_data.apply_time
                });
            }
        },function(){

        });
    });




    $('.opt-change').set_input_change_event(load_data);
});
