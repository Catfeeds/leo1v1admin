/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/assistant_performance-get_ass_stu_lesson_month.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		    date_type_config:	$('#id_date_type_config').val(),
		    date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val(),
		    assistantid:	$('#id_assistantid').val(),
        studentid         : $("#id_studentid").val(),
        sys_operator      : $("#id_sys_operator").val(),      
        teacherid         : $('#id_teacherid').val(),
		    adminid            : $('#id_adminid').val(),
        origin_userid     : $('#id_origin_userid').val()

    });
}
$(function(){


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
       
    $("#id_sys_operator").val(g_args.sys_operator);
    $('#id_assistantid').val(g_args.assistantid);
    $('#id_origin_userid').val(g_args.origin_userid);
    $('#id_teacherid').val(g_args.teacherid);
    $('#id_studentid').val(g_args.studentid);
    $('#id_adminid').val(g_args.adminid);
    $.admin_select_user($('#id_studentid'),"student", load_data);
    $.admin_select_user($('#id_origin_userid'),"student", load_data);
    $.admin_select_user($('#id_teacherid'),"teacher", load_data);
    $.admin_select_user($('#id_adminid'),"admin", load_data);
    $.admin_select_user($('#id_assistantid'),"assistant", load_data);




	  $('.opt-change').set_input_change_event(load_data);
});


