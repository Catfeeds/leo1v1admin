
/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-tea_manage_all_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      lessonid:	$('#id_lessonid').val(),
			      date_type_config:	$('#id_date_type_config').val(),
			      date_type:	$('#id_date_type').val(),
			      opt_date_type:	$('#id_opt_date_type').val(),
			      start_time:	$('#id_start_time').val(),
			      end_time:	$('#id_end_time').val(),
			      studentid:	$('#id_studentid').val(),
			      teacherid:	$('#id_teacherid').val(),
			      confirm_flag:	$('#id_confirm_flag').val(),
			      seller_adminid:	$('#id_seller_adminid').val(),
			      lesson_status:	$('#id_lesson_status').val(),
			      assistantid:	$('#id_assistantid').val(),
			      grade:	$('#id_grade').val(),
			      test_seller_id:	$('#id_test_seller_id').val(),
			      has_performance:	$('#id_has_performance').val(),
			      lesson_user_online_status:	$('#id_lesson_user_online_status').val(),
			      lesson_type:	$('#id_lesson_type').val(),
			      subject:	$('#id_subject').val(),
			      lesson_count:	$('#id_lesson_count').val(),
			      lesson_cancel_reason_type:	$('#id_lesson_cancel_reason_type').val(),
			      has_video_flag:	$('#id_has_video_flag').val(),
			      is_with_test_user:	$('#id_is_with_test_user').val()
        });
    }

	Enum_map.append_option_list("set_boolean",$("#id_lesson_user_online_status"));
	Enum_map.append_option_list("boolean",$("#id_has_video_flag"));

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
	$('#id_lessonid').val(g_args.lessonid);
	$('#id_studentid').val(g_args.studentid);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_confirm_flag').val(g_args.confirm_flag);
	$.enum_multi_select( $('#id_confirm_flag'), 'confirm_flag', function(){load_data();} )
	$('#id_seller_adminid').val(g_args.seller_adminid);
	$('#id_lesson_status').val(g_args.lesson_status);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_grade').val(g_args.grade);
	$.enum_multi_select( $('#id_grade'), 'grade', function(){load_data();} )
	$('#id_test_seller_id').val(g_args.test_seller_id);
	$('#id_has_performance').val(g_args.has_performance);
	$('#id_lesson_user_online_status').val(g_args.lesson_user_online_status);
	$('#id_lesson_type').val(g_args.lesson_type);
	$('#id_subject').val(g_args.subject);
	$('#id_lesson_count').val(g_args.lesson_count);
	$('#id_lesson_cancel_reason_type').val(g_args.lesson_cancel_reason_type);
	$('#id_has_video_flag').val(g_args.has_video_flag);
	$('#id_is_with_test_user').val(g_args.is_with_test_user);


	$('.opt-change').set_input_change_event(load_data);
});

