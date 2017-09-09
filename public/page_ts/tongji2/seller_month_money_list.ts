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


    $(".common-table" ).table_admin_level_4_init(true);



    function load_row_data (){

        var row_list = $("#id_tbody .l-4");
        var do_index = 0;

        function do_one() {
            if (do_index < row_list.length ) {
                var $tr      = $(row_list[do_index]);
                var opt_data = $tr.find(".opt-show").get_opt_data();
                $.do_ajax("/user_deal/seller_month_money_info",{
                    "adminid"  : opt_data.adminid ,
                    "start_time" : g_args.start_time,
                    "end_time"   : g_args.end_time
                },function(data){
                    $tr.find(".all_price").text(data["all_price"]);
                    $tr.find(".24_hour_all_price").text(data["24_hour_all_price"]);


                    $tr.find(".group_all_price").text(data["group_all_price"]);
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
                    $tr.find(".new_account_value").text(data["new_account_value"]);
                    $tr.find(".create_time").text(data["create_time"]);
                    do_index++;
                    do_one();
                });
            }
        };
        do_one();

    };
    load_row_data ();
});
