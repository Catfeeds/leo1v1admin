/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-test_lesson_plan_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			grade:	$('#id_grade').val(),
			subject:	$('#id_subject').val(),
			seller_student_status:	$('#id_seller_student_status').val(),
			userid:	$('#id_userid').val(),
			teacherid:	$('#id_teacherid').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade")); 
	Enum_map.append_option_list("subject",$("#id_subject")); 
	Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status")); 

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

	$('#id_grade').val(g_args.grade);
	$('#id_subject').val(g_args.subject);
	$('#id_seller_student_status').val(g_args.seller_student_status);
	$('#id_userid').val(g_args.userid);
	$('#id_teacherid').val(g_args.teacherid);

    $.admin_select_user(
        $('#id_userid'),
        "student", load_data);

    $.admin_select_user($('#id_teacherid'),
                        "teacher", load_data);




	$('.opt-change').set_input_change_event(load_data);
});

