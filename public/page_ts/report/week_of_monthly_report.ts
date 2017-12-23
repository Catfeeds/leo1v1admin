/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-tongji_cr.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            history:	$('#id_is_history_data').val()
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

    $('#id_is_history_data').val(g_args.history);

    $("#download_data").on("click",function(){

       /* var list_data=[];
        var $tr_list=$(this).parent().parent().find("table").find("tr" );
        $.each($tr_list ,function(i,tr_item )  {
            var row_data= [];
            var $td_list= $(tr_item ).find("td");
            $.each(  $td_list, function( i, td_item)  {
                if ( i>0 && i< $td_list.length-1 ) {
                    row_data.push( $.trim( $(td_item).text()) );
                }
            });
            list_data.push(row_data);
            });*/
        var lesson_per = g_data.student_arrive_per+"% ("+g_data.student_arrive+"/"+g_data.lesson_plan+")";
        var list_data=[
            ["月度目标收入",g_data.target],
            ["完成金额",g_data.total_price],
            ["完成率",g_data.kpi_per+"%"],
            ["缺口金额",g_data.gap_money],
            ["现金总收入",g_data.total_income],
            ["下单总人数",g_data.person_num],
            ["入职完整月人员签单额",g_data.total_price_thirty],
            ["入职完整月人员人数",g_data.person_num_thirty],
            ["平均人效",g_data.person_num_thirty_per],
            ["平均单笔",g_data.contract_per],
            ["月KPI完整率",g_data.month_kpi_per+"%"],
            ["CR总人数",g_data.cr_num],
            ["结课学员数",g_data.finish_num],
            ["退费总人数",g_data.refund_num],
            ["课时系数目标量",g_data.lesson_target],
            ["在读学生数量",g_data.read_num],
            ["上课学生数量",g_data.total_student],
            ["课时消耗目标数量","节点"],
            ["课时消耗实际数量",g_data.lesson_consume],
            ["老师请假课时",g_data.teacher_leave],
            ["学生请假课时",g_data.student_leave],
            ["其他原因未上课时",g_data.other_leave],
            ["课时完成率","节点"],
            ["学生到课率",lesson_per],
            ["课时收入",g_data.lesson_income],
            ["预计结课学生数量",g_data.expect_finish_num],
            ["计划内续费学生数量",g_data.plan_renew_num],
            ["计划外续费学生数量",g_data.other_renew_num],
            ["实际续费学生数量",g_data.real_renew_num],
            ["续费金额",g_data.total_renew],
            ["平均单笔",g_data.renew_num_per],
            ["月续费率",g_data.renew_per+"%"],
            ["月预警续费率",g_data.finish_renew_per+"%"],
            ["转介绍至CC例子量",g_data.tranfer_phone_num],
            ["转介绍至CC例子签单量",g_data.tranfer_total_num],
            ["转介绍至CC例子签单金额",g_data.tranfer_total_price],
            ["月转介绍至CC签单率",g_data.tranfer_success_per+"%"],
            ["转介绍成单数量",g_data.tranfer_num],
            ["转介绍总金额",g_data.total_tranfer],
            ["平均单笔",g_data.tranfer_num_per],
            ["扩课试听数量",g_data.total_test_lesson_num],
            ["扩课成单数量",g_data.success_num],
            ["扩科待跟进数量",g_data.wait_num],
            ["扩科未成单数量",g_data.fail_num],
            ["月扩课成功率",g_data.kk_success_per+"%"],
            ["家长投诉数量",""],
            ["非正常退费事件数量",""],
            ["非正常退费金额",""],
            ["不可抗力退费数量",""],
            ["不可抗力退费金额",""],
            ["退费总额",""],
        ];


        $.do_ajax ( "/page_common/upload_xls_data",{
            xls_data :  JSON.stringify(list_data )
        },function(data){
            window.location.href= "/common_new/download_xls";
        });

    });



    $('.opt-change').set_input_change_event(load_data);
});
