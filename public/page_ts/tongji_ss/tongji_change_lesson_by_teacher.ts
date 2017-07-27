/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-tongji_change_lesson_by_teacher.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			lesson_cancel_reason_type :	$('#id_lesson_cancel_reason_type ').val()
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

    Enum_map.append_option_list('lesson_cancel_reason_type',$('#id_lesson_cancel_reason_type'),false,[2,12]);
	  $('#id_lesson_cancel_reason_type ').val(g_args.lesson_cancel_reason_type );


	$('.opt-change').set_input_change_event(load_data);
});

