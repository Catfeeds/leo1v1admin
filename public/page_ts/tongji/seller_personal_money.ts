/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-seller_personal_money.d.ts" />

$(function(){
    $(".common-table").tbody_scroll_table();

    function load_data(){
        $.reload_self_page ( {
            date_type     : $('#id_date_type').val(),
            opt_date_type : $('#id_opt_date_type').val(),
            start_time    : $('#id_start_time').val(),
            end_time      : $('#id_end_time').val()
        });
    }

    $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    var jump_url_1="/seller_student_new2/seller_edit_log_list";
    $(".distribution_count").on("click",function(){
        var opt_data= $(this).get_row_opt_data();
        $.wopen(jump_url_1+"?"
                +"adminid="+opt_data.adminid
                +"&start_time="+g_args.start_time
                +"&end_time="+g_args.end_time
                +"&date_type="+g_args.date_type
                +"&opt_date_type="+g_args.opt_date_type
               );
    });

    var jump_url_1="/seller_student_new2/seller_edit_log_list";
    $(".no_call_count").on("click",function(){
        var opt_data= $(this).get_row_opt_data();
        $.wopen(jump_url_1+"?"
                +"adminid="+opt_data.adminid
                +"&start_time="+g_args.start_time
                +"&end_time="+g_args.end_time
                +"&date_type="+g_args.date_type
                +"&opt_date_type="+g_args.opt_date_type
                +"&global_tq_called_flag="+0
               );
    });

    $(".common-table").table_admin_level_4_init();
    $('.opt-change').set_input_change_event(load_data);
});
