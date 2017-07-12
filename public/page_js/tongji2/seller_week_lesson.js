/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-seller_count.d.ts" />

function load_data(){
    $.reload_self_page ( {
        order_by_str: g_args.order_by_str,
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val(),
        groupid:	$('#id_groupid').val(),
        main_type_str:g_args.main_type_str,
        up_group_name:g_args.up_group_name,
        group_name:g_args.group_name,
        account:g_args.account,
        seller_groupid_ex:$('#id_seller_groupid_ex').val(),
    });
}

$(function(){

    $(".common-table" ).tbody_scroll_table();
    $(".common-table" ).table_admin_level_4_init();


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

    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
    $("#id_seller_groupid_ex").init_seller_groupid_ex(g_adminid_right);

    $('.opt-change').set_input_change_event(load_data);

});
