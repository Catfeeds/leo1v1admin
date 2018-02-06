/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_tongji-month_tongji_report.d.ts" />

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
    var whole_data = new Array();
    var do_index = 0;
    function load_row_data (){
        var row_list = $("#id_tbody .l-5");
        function do_one() {
            if (do_index < row_list.length ) {
                var $tr      = $(row_list[do_index]);
                var opt_data = $tr.find(".opt-show").get_opt_data();
                whole_data[do_index] = opt_data['adminid'];
                $.do_ajax("/seller_student_new2/seller_test_lesson_info",{
                    "adminid"    : opt_data['adminid'],
                    "start_time" : g_args.start_time,
                    "end_time"   : g_args.end_time,
                },function(data){
                    pushData($tr,data)
                    do_index++;
                    do_one();
                });
            }
            if (do_index == row_list.length ) {
                superAdd('l-4','l-5');
                superAdd('l-3','l-4');
                superAdd('l-2','l-3');
                superAdd('l-1','l-2');
                superAdd('l-0','l-1');
                successAndFail();
            }
        };
        do_one();
    };

    load_row_data ();

    function pushData(obj,data){
        obj.find(".test_lesson_count").text(data["test_lesson_count"]);
        obj.find(".succ_all_count_for_month").text(data["succ_all_count_for_month"]);
        obj.find(".dis_succ_all_count_for_month").text(data["dis_succ_all_count_for_month"]);
        obj.find(".suc_lesson_count_one").text(data["suc_lesson_count_one"]);
        obj.find(".suc_lesson_count_two").text(data["suc_lesson_count_two"]);
        obj.find(".suc_lesson_count_three").text(data["suc_lesson_count_three"]);
        obj.find(".suc_lesson_count_four").text(data["suc_lesson_count_four"]);
        obj.find(".fail_all_count_for_month").text(data["fail_all_count_for_month"]);
        obj.find(".lesson_per").text(data["lesson_per"]);
        obj.find(".kpi").text(data["kpi"]);
        obj.find(".order_per").text(data["order_per"]);
    }

    function job_tongji(className,nextName){
        $("#id_tbody ."+className).each(function(){
            var thisItem = $(this).index();
            var nextItem = $('#id_tbody tr:gt('+thisItem+').'+className).index();
            
            if(nextItem < 0){
                nextItem = $('#id_tbody .'+nextName+':last').index() + 1;
            }
            var at_job = 0;
            var leave_job = 0;

            if( nextItem >= thisItem ){
                $('#id_tbody tr:lt('+nextItem+'):gt('+thisItem+').'+nextName).each(function(){
                    var field_1 = $(this).find('.at_job').text() == '' ? 0 : parseInt($(this).find('.at_job').text());
                    var field_2 = $(this).find('.leave_job').text() == '' ? 0 : parseInt($(this).find('.leave_job').text());
                    at_job += field_1;
                    leave_job += field_2;
                })
            }
            $(this).find('.at_job').text(at_job);
            $(this).find('.leave_job').text(leave_job);
        })
    }

    job_tongji('l-2','l-3');
    job_tongji('l-1','l-2');
    job_tongji('l-0','l-1');

    function superAdd(className,nextName){
        $("#id_tbody ."+className).each(function(){
            var thisItem = $(this).index();
            var nextItem = $('#id_tbody tr:gt('+thisItem+').'+className).index();
            
            if(nextItem < 0){
                nextItem = $('#id_tbody .'+nextName+':last').index() + 1;
            }
            //console.log(nextItem);
            var test_lesson_count = 0 ;
            var succ_all_count_for_month = 0;
            var suc_lesson_count_one = 0;
            var suc_lesson_count_two = 0;
            var suc_lesson_count_three = 0;
            var suc_lesson_count_four = 0;
            var fail_all_count_for_month = 0;

            if( nextItem >= thisItem ){
                $('#id_tbody tr:lt('+nextItem+'):gt('+thisItem+').'+nextName).each(function(){
                   
                    var field_1 = $(this).find('.test_lesson_count').text() == '' ? 0 : parseInt($(this).find('.test_lesson_count').text());
                    var field_2 = $(this).find('.succ_all_count_for_month').text() == '' ? 0 : parseInt($(this).find('.succ_all_count_for_month').text());
                    var field_3 = $(this).find('.suc_lesson_count_one').text() == '' ? 0 : parseInt($(this).find('.suc_lesson_count_one').text());
                    var field_4 = $(this).find('.suc_lesson_count_two').text() == '' ? 0 : parseInt($(this).find('.suc_lesson_count_two').text());
                    var field_5 = $(this).find('.suc_lesson_count_three').text() == '' ? 0 : parseInt($(this).find('.suc_lesson_count_three').text());
                    var field_6 = $(this).find('.suc_lesson_count_four').text() == '' ? 0 : parseInt($(this).find('.suc_lesson_count_four').text());
                    var field_7 = $(this).find('.fail_all_count_for_month').text() == '' ? 0 : parseInt($(this).find('.fail_all_count_for_month').text());
                   
                    test_lesson_count += field_1;
                    succ_all_count_for_month += field_2;
                    suc_lesson_count_one += field_3;
                    suc_lesson_count_two += field_4;
                    suc_lesson_count_three += field_5;
                    suc_lesson_count_four += field_6;
                    fail_all_count_for_month += field_7;
                })
            }
            $(this).find('.test_lesson_count').text(test_lesson_count);
            $(this).find('.succ_all_count_for_month').text(succ_all_count_for_month);
            $(this).find('.suc_lesson_count_one').text(suc_lesson_count_one);
            $(this).find('.suc_lesson_count_two').text(suc_lesson_count_two);
            $(this).find('.suc_lesson_count_three').text(suc_lesson_count_three);
            $(this).find('.suc_lesson_count_four').text(suc_lesson_count_four);
            $(this).find('.fail_all_count_for_month').text(fail_all_count_for_month);
        })
    }

    //统计签约率和取消率
    function successAndFail(){
        $('#id_tbody tr[class!="l-5"]').each(function(){
            var fail_all_count_for_month = $(this).find('.fail_all_count_for_month').text() == '' ? '0' : $(this).find('.fail_all_count_for_month').text();
            var test_lesson_count = $(this).find('.test_lesson_count').text() == '' ? '0' : $(this).find('.test_lesson_count').text();

            var fail_all_count = parseInt(fail_all_count_for_month);
            var test_count = parseInt(test_lesson_count);

            var success_all_count_for_month = $(this).find('.success_all_count_for_month').text() == '' ? '0' : $(this).find('.success_all_count_for_month').text();
            var succ_all_count_for_month = $(this).find('.succ_all_count_for_month').text() == '' ? '0' : $(this).find('.succ_all_count_for_month').text();

            var success_count = parseInt(success_all_count_for_month);
            var succ_count = parseInt(succ_all_count_for_month);

            if(fail_all_count != 0 && test_count != 0){
                var lesson_per = (fail_all_count/test_count)*10000;
		            lesson_per = Math.round(lesson_per);
		            lesson_per = lesson_per/100+'%';
            }else{
                var lesson_per = '0%';
            }

            var order_per = 0;
            if(success_count != 0 && succ_count != 0){
                var order_per = (success_count/succ_count)*10000;
                order_per = Math.round(order_per);
		            order_per = (order_per/100).toString() + '%';

            }else{
                var order_per = '0%'
            }

            $(this).find('.lesson_per').text(lesson_per);
            $(this).find('.order_per').text(order_per);
        })
    }
    if(g_account=='龚隽' || g_account=='sherry' || g_account=='班洁' || g_account=='孙瞿'  || g_account=='tom'){
        download_show();
    }
    $('.opt-change').set_input_change_event(load_data);
});
