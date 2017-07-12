/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-tongji_seller_test_lesson_paper_order_info.d.ts" />

function load_data(){
    $.reload_self_page ( {
        origin_ex : $("#id_origin_ex").val(),
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val(),
        monthtime_flag:	$('#id_monthtime_flag').val(),
    });
}

$(function(){

   // $(".common-table").tbody_scroll_table();

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

	$('#id_monthtime_flag').val(g_args.monthtime_flag);
    $('.opt-change').set_input_change_event(load_data);



    $(".common-table" ).table_admin_level_4_init();



});
