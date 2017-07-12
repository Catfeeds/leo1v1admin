/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_teacher_test_lesson_info_old.d.ts" />
function load_data(){
        $.reload_self_page ( {
		    order_by_str: g_args.order_by_str,
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			teacherid:	$('#id_teacherid').val(),
            teacher_money_type:	$('#id_teacher_money_type').val(),
            identity:	$('#id_identity').val(),
			subject:	$('#id_subject').val()
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
    Enum_map.append_option_list("teacher_money_type", $("#id_teacher_money_type") );
    Enum_map.append_option_list("subject", $("#id_subject") );
    Enum_map.append_option_list("identity", $("#id_identity") );

	$('#id_teacherid').val(g_args.teacherid);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_subject').val(g_args.subject);
	$('#id_identity').val(g_args.identity);

    $.admin_select_user( $("#id_teacherid"), "teacher", load_data);


	$('.opt-change').set_input_change_event(load_data);
});







