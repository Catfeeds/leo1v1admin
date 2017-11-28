/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-admin_member_list.d.ts" />

$(function(){
    $(".common-table").tbody_scroll_table();

    function load_data(){
        $.reload_self_page ( {
      date_type:	$('#id_date_type').val(),
      opt_date_type:	$('#id_opt_date_type').val(),
      start_time:	$('#id_start_time').val(),
      end_time:	$('#id_end_time').val()
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
    // $(".common-table").table_admin_level_4_init();
    $(".common-table").table_admin_level_5_init();
    function load_row_data (){
        var row_list = $("#id_tbody .l-5");
        var do_index = 0;
        function do_one() {
            if (do_index < row_list.length ) {
                var $tr      = $(row_list[do_index]);
                var opt_data = $tr.find(".opt-show").get_opt_data();
                $.do_ajax("/user_deal/seller_month_money_info",{
                    "adminid"  : opt_data.adminid ,
                    "start_time" : g_args.start_time,
                    "end_time"   : g_args.end_time,
                },function(data){
                    $tr.find(".suc_first_week").text(data["suc_first_week"]);
                    $tr.find(".suc_second_week").text(data["suc_second_week"]);
                    $tr.find(".suc_third_week").text(data["suc_third_week"]);
                    $tr.find(".suc_fourth_week").text(data["suc_fourth_week"]);
                    $tr.find(".lesson_per").text(data["lesson_per"]);
                    $tr.find(".kpi").text(data["kpi"]);
                    $tr.find(".last_seller_level").text(data["last_seller_level"]);
                    do_index++;
                    do_one();
                });
            }
        };
        do_one();

    };
    load_row_data ();

    $('.opt-change').set_input_change_event(load_data);
});
