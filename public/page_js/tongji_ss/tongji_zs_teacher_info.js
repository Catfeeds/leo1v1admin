/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-tongji_zs_teacher_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
            month_test_lesson_num:	$('#id_month_test_lesson_num').val(),
			except_test_lesson_num:	$('#id_except_test_lesson_num').val()
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

    //alert(g_args.month_test_lesson_num);
	$('#id_month_test_lesson_num').val(g_args.month_test_lesson_num);
	$('#id_except_test_lesson_num').val(g_args.except_test_lesson_num);

	$('.opt-change').set_input_change_event(load_data);
});




