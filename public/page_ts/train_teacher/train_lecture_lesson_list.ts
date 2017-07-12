/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/train_teacher-train_lecture_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config : $('#id_date_type_config').val(),
			date_type        : $('#id_date_type').val(),
			opt_date_type    : $('#id_opt_date_type').val(),
			start_time       : $('#id_start_time').val(),
			end_time         : $('#id_end_time').val(),
			teacherid        : $('#id_teacherid').val(),
			lesson_status    : $('#id_lesson_status').val(),
			lesson_type      : $('#id_lesson_type').val(),
			lessonid         : $('#id_lessonid').val(),
			lesson_sub_type  : $('#id_lesson_sub_type').val(),
			train_type       : $('#id_train_type').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

	$('#id_teacherid').val(g_args.teacherid);
	$('#id_lesson_status').val(g_args.lesson_status);
	$('#id_lesson_type').val(g_args.lesson_type);
	$('#id_lessonid').val(g_args.lessonid);
	$('#id_lesson_sub_type').val(g_args.lesson_sub_type);
	$('#id_train_type').val(g_args.train_type);


	$('.opt-change').set_input_change_event(load_data);
});
