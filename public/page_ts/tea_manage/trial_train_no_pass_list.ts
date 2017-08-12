/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-trial_train_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			status:	$('#id_status').val(),
			lesson_status:	$('#id_lesson_status').val(),
			grade:	$('#id_grade').val(),
			subject:	$('#id_subject').val(),
			teacherid:	$('#id_teacherid').val(),
			is_test:	$('#id_is_test').val()
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

    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("boolean",$("#id_absenteeism_flag"));
    Enum_map.append_option_list("boolean",$("#id_is_test_user"));

	$('#id_status').val(g_args.status);
	$('#id_lesson_status').val(g_args.lesson_status);
	$('#id_grade').val(g_args.grade);
	$('#id_subject').val(g_args.subject);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_is_test').val(g_args.is_test);


	$('.opt-change').set_input_change_event(load_data);
});

