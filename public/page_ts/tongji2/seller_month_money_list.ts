/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-seller_month_money_list.d.ts" />

$(function(){

    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
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


    $('.opt-change').set_input_change_event(load_data);


    // $(".common-table" ).table_admin_level_4_init(true);
    $(".common-table" ).table_admin_level_5_init(); // 开发中



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
                    $tr.find(".cur_del_flag_str").text(data["cur_del_flag_str"]);
                    $tr.find(".suc_first_week").text(data["suc_first_week"]);
                    $tr.find(".suc_second_week").text(data["suc_second_week"]);
                    $tr.find(".suc_third_week").text(data["suc_third_week"]);
                    $tr.find(".suc_fourth_week").text(data["suc_fourth_week"]);
                    $tr.find(".suc_all_count").text(data["suc_all_count"]);
                    $tr.find(".dis_suc_all_count").text(data["dis_suc_all_count"]);
                    $tr.find(".fail_all_count").text(data["fail_all_count"]);
                    $tr.find(".test_lesson_count").text(data["test_lesson_count"]);
                    $tr.find(".lesson_per").text(data["lesson_per"]);
                    $tr.find(".kpi").text(data["kpi"]);
                    $tr.find(".last_all_price").text(data["last_all_price"]);
                    $tr.find(".last_seller_level").text(data["last_seller_level"]);
                    $tr.find(".group_kpi").text(data["group_kpi"]);
                    $tr.find(".group_kpi_desc").text(data["group_kpi_desc"]);

                    $tr.find(".order_num").text(data["order_num"]);
                    $tr.find(".all_price").text(data["all_price"]);
                    $tr.find(".base_salary").text(data["base_salary"]);
                    $tr.find(".sup_salary").text(data["sup_salary"]);
                    $tr.find(".per_salary").text(data["per_salary"]);
                    $tr.find(".get_per_salary").text(data["get_per_salary"]);
                    $tr.find(".stage_money").text(data["stage_money"]);
                    $tr.find(".no_stage_money").text(data["no_stage_money"]);
                    $tr.find(".24_hour_all_price").text(data["24_hour_all_price"]);

                    $tr.find(".last_group_all_price").text(data["last_group_all_price"]);
                    $tr.find(".group_all_price").text(data["group_all_price"]);
                    $tr.find(".group_all_stage_price").text(data["group_all_stage_price"]);
                    $tr.find(".group_all_no_stage_price").text(data["group_all_no_stage_price"]);
                    $tr.find(".group_default_money").text(data["group_default_money"]);
                    $tr.find(".require_all_price").text(data["require_all_price"]);

                    $tr.find(".all_price_1").text(data["all_price_1"]);
                    $tr.find(".require_all_price_1").text(data["require_all_price_1"]);
                    $tr.find(".v24_hour_all_price_1").text(data["v24_hour_all_price_1"]);
                    $tr.find(".require_and_24_hour_price_1").text(data["require_and_24_hour_price_1"]);
                    $tr.find(".group_money_add_percent").text(data["group_money_add_percent"]);

                    $tr.find(".cur_month_money").text(data["cur_month_money"]);
                    $tr.find(".three_month_money").text(data["three_month_money"]);

                    $tr.find(".percent").text(data["percent"]);
                    $tr.find(".money").text(data["money"]);
                    $tr.find(".desc").text(data["desc"]);
                    $tr.find(".group_master_money").text(data["group_master_money"]);
                    $tr.find(".group_self_money").text(data["group_self_money"]);
                    $tr.find(".group_month_avg_lesson").text(data["group_month_avg_lesson"]);
                    $tr.find(".group_month_avg_lesson_per").text(data["group_month_avg_lesson_per"]);
                    $tr.find(".group_month_avg_order_per").text(data["group_month_avg_order_per"]);
                    $tr.find(".group_month_avg_leave_per").text(data["group_month_avg_leave_per"]);
                    $tr.find(".new_account_value").text(data["new_account_value"]);
                    do_index++;
                    do_one();
                });
            }
        };
        do_one();
    };
    load_row_data ();

    if(g_account=='龚隽' || g_account=='tom' || g_account=='sherry'){
        download_show();
    }
});
