/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-test_lesson_plan_detail_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			set_lesson_adminid:	$('#id_set_adminid').val(),
			subject:	$('#id_subject').val(),
			grade:	$('#id_grade').val(),
			success_flag:	$('#id_success_flag').val(),
			test_lesson_fail_flag:	$('#id_test_lesson_fail_flag').val(),
			userid:	$('#id_userid').val(),
			require_admin_type:	$('#id_require_admin_type').val(),
			require_adminid:	$('#id_require_adminid').val()
        });
    }

	Enum_map.append_option_list("subject",$("#id_subject")); 
	Enum_map.append_option_list("grade",$("#id_grade")); 
	Enum_map.append_option_list("set_boolean",$("#id_success_flag")); 
	Enum_map.append_option_list("test_lesson_fail_flag",$("#id_test_lesson_fail_flag")); 
	Enum_map.append_option_list("account_role",$("#id_require_admin_type")); 

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
	$('#id_set_adminid').val(g_args.set_lesson_adminid);
	$('#id_subject').val(g_args.subject);
	$('#id_grade').val(g_args.grade);
	$('#id_success_flag').val(g_args.success_flag);
	$('#id_test_lesson_fail_flag').val(g_args.test_lesson_fail_flag);
	$('#id_userid').val(g_args.userid);
	$('#id_require_admin_type').val(g_args.require_admin_type);
	$('#id_require_adminid').val(g_args.require_adminid);
	$('.opt-change').set_input_change_event(load_data);

    $.admin_select_user($('#id_require_adminid'), "admin", load_data );
    $.admin_select_user($('#id_set_adminid'), "admin", load_data );
    $.admin_select_user($('#id_userid'), "student", load_data);



    //下载隐藏
    download_hide();

});

