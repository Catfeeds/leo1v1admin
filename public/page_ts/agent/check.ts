/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-assign_sub_adminid_list.d.ts" />
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

    // $(".common-table").table_admin_level_4_init();

    $('.opt-change').set_input_change_event(load_data);

    $(".common-table" ).table_admin_level_5_init(); // 开发中

    function load_row_data (){
        var row_list = $("#id_tbody .l-5");
        var do_index = 0;

        function do_one() {
            if (do_index < row_list.length ) {
                var $tr      = $(row_list[do_index]);
                var opt_data = $tr.find(".opt-show").get_opt_data();
                $.do_ajax("/seller_student_new2/get_item_list",{
                    "adminid"  : opt_data.adminid ,
                    "start_time" : g_args.start_time,
                    "end_time"   : g_args.end_time,
                },function(data){
                    $tr.find(".called_times").text(data["called_times"]);
                    $tr.find(".no_called_times").text(data["no_called_times"]);
                    $tr.find(".suc_test_lesson_cout").text(data["suc_test_lesson_cout"]);
                    $tr.find(".require_lesson_count").text(data["require_lesson_count"]);
                    $tr.find(".order_count").text(data["order_count"]);
                    $tr.find(".refund_count").text(data["refund_count"]);
                    $tr.find(".11_level").text(data["11_level"]);
                    $tr.find(".12_level").text(data["12_level"]);

                    do_index++;
                    do_one();
                });
            }
        };
        do_one();
    };
    load_row_data ();

});
