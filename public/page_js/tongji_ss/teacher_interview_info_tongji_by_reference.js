/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-teacher_interview_info_tongji_by_reference.d.ts" />
function load_data(){
    $.reload_self_page ( {
		order_by_str: g_args.order_by_str,
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		subject:	$('#id_subject').val(),
        teacher_account:	$('#id_teacher_account').val(),
		reference_teacherid:	$('#id_reference_teacherid').val(),
		identity:	$('#id_identity').val()
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
    Enum_map.append_option_list("subject", $("#id_subject") );
	$('#id_subject').val(g_args.subject);
    Enum_map.append_option_list("identity", $("#id_identity"),false,[0,1,2] );

	$('#id_identity').val(g_args.identity);

    $('#id_teacher_account').val(g_args.teacher_account);
    $('#id_reference_teacherid').val(g_args.reference_teacherid);

    $.admin_select_user($("#id_reference_teacherid"),"teacher",load_data);

    $.admin_select_user($("#id_teacher_account"), "interview_teacher", load_data);


	$('.opt-change').set_input_change_event(load_data);
});








