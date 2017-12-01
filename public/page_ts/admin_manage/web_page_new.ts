/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-seller_count.d.ts" />

function load_data(){
    $.reload_self_page ( {
        order_by_str: g_args.order_by_str,
    });
}

$(function(){

    $(".common-table").tbody_scroll_table();

    $('.opt-change').set_input_change_event(load_data);

    $(".common-table" ).table_admin_level_4_init();



});
