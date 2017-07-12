/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-teacher_test_lesson_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			test_lesson_flag:	$('#id_test_lesson_flag').val(),
			l_1v1_flag:	$('#id_l_1v1_flag').val(),
			tutor_subject:	$('#id_tutor_subject').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_test_lesson_flag")); 
	Enum_map.append_option_list("boolean",$("#id_l_1v1_flag")); 
	Enum_map.append_option_list("subject",$("#id_tutor_subject")); 

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
	$('#id_test_lesson_flag').val(g_args.test_lesson_flag);
	$('#id_l_1v1_flag').val(g_args.l_1v1_flag);
	$('#id_tutor_subject').val(g_args.tutor_subject);


	$('.opt-change').set_input_change_event(load_data);
});

