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

        var month_finish_persent = Math.floor(g_data.month_finish_persent * 100) / 100+'%';
        var aver_money = Math.floor(g_data.aver_money *100)/100;
        var aver_count = Math.floor(g_data.aver_count *100)/100;
        var seller_num = g_data.new_department+g_data.one_department+g_data.two_department+g_data.three_department+g_data.train_department;
        var referral_money_rate = Math.floor(g_data.referral_money_rate*100)/100+'%';
        var high_school_money_rate = Math.floor(g_data.high_school_money_rate*100)/100+'%';
        var junior_money_rate = Math.floor(g_data.junior_money_rate*100)/100+'%';
        var primary_money_rate = Math.floor(g_data.primary_money_rate*100)/100+'%';
        var invit_month_rate = Math.floor(g_data.invit_month_rate*100)/100+'%';
        var test_plan_month_rate = Math.floor(g_data.test_plan_month_rate*100)/100+'%';
        var lesson_succ_month_rate = Math.floor(g_data.lesson_succ_month_rate*100)/100+'%';
        var trans_month_rate = Math.floor(g_data.trans_month_rate*100)/100+'%';

        var list_data=[
            ["月度目标收入",g_data.seller_target_income],
            ["月完成金额",g_data.formal_info],
            ["完成率",month_finish_persent],
            ["缺口金额",g_data.month_left_money],

            ["下单总人数",g_data.order_num],
            ["入职完整月人员人数",g_data.formal_num],
            ["平均人效",aver_money],
            ["平均单笔",aver_count],

            ["cc总人数",seller_num],
            ["咨询一部",g_data.one_department],
            ["咨询二部",g_data.two_department],
            ["咨询三部",g_data.three_department],
            ["新人营",g_data.new_department],
            ["培训中",g_data.train_department],
            ["转介绍金额占比",referral_money_rate],
            ["高中金额占比",high_school_money_rate],
            ["初中金额占比",junior_money_rate],
            ["小学金额占比",primary_money_rate],

            ["试听邀约数",g_data.seller_invit_num],
            ["试听排课数",g_data.seller_schedule_num],
            ["试听成功数",g_data.test_succ_num],
            ["签单数",g_data.new_order_num],
            ["月邀约率",invit_month_rate],
            ["月排课率",test_plan_month_rate],
            ["月到课率",lesson_succ_month_rate],
            ["月试听转化率",trans_month_rate],




        ];


        $.do_ajax ( "/page_common/upload_xls_data",{
            xls_data :  JSON.stringify(list_data )
        },function(data){
            window.location.href= "/common_new/download_xls";
        });

    });




    $('.opt-change').set_input_change_event(load_data);
});
