/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-get_seller_total_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            history_data:	$('#id_history_data').val()
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

    $('#id_history_data').val(g_args.history_data);


    $("#download_data").on("click",function(){
        console.log(g_data);

        var lesson_per = g_data.student_arrive_per+"("+g_data.student_arrive+"/"+g_data.lesson_plan+")";
        var month_finish_persent = g_data.month_finish_persent.toFixed(2);
        var aver_money = g_data.aver_money.toFixed(2);

        var list_data=[
            ["月度目标收入",g_data.seller_target_income],
            ["月完成金额",g_data.formal_info],
            ["完成率",month_finish_persent],
            ["缺口金额",g_data.month_left_money],

            ["下单总人数",g_data.order_num],
            ["入职完整月人员人数",g_data.formal_num],

        ];


        $.do_ajax ( "/page_common/upload_xls_data",{
            xls_data :  JSON.stringify(list_data )
        },function(data){
            window.location.href= "/common_new/download_xls";
        });

    });




    $('.opt-change').set_input_change_event(load_data);
});
