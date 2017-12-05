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
    var whole_data = new Array();
    var do_index = 0;
    function load_row_data (){
        var row_list = $("#id_tbody .l-5");
        function do_one() {
            if (do_index < row_list.length ) {
                var $tr      = $(row_list[do_index]);
                var opt_data = $tr.find(".opt-show").get_opt_data();
                whole_data[do_index] = opt_data.adminid;
                $.do_ajax("/seller_student_new2/seller_test_lesson_info",{
                    "adminid"    : opt_data.adminid,
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
            }
        };
        do_one();
    };

    load_row_data ();

    function pushData(obj,data){
        obj.find(".test_lesson_count").text(data["test_lesson_count"]);
        obj.find(".succ_all_count_for_month").text(data["succ_all_count_for_month"]);
        obj.find(".suc_lesson_count_one").text(data["suc_lesson_count_one"]);
        obj.find(".suc_lesson_count_two").text(data["suc_lesson_count_two"]);
        obj.find(".suc_lesson_count_three").text(data["suc_lesson_count_three"]);
        obj.find(".suc_lesson_count_four").text(data["suc_lesson_count_four"]);
        obj.find(".fail_all_count_for_month").text(data["fail_all_count_for_month"]);
        obj.find(".lesson_per").text(data["lesson_per"]);
        obj.find(".kpi").text(data["kpi"]);
        obj.find(".order_per").text(data["order_per"]);
    }

    function superAdd(className,nextName){
        $("#id_tbody ."+className).each(function(){
            var thisItem = $(this).index();
            var nextItem = $('#id_tbody tr:gt('+thisItem+').'+className).index();
            if(nextItem == undefined){
                nextItem = $('#id_tbody .'+nextName+':last').index() + 1;
            }

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

    if(g_account=='龚隽' || g_account=='sherry'){
        download_show();
    }
    $('.opt-change').set_input_change_event(load_data);
});
