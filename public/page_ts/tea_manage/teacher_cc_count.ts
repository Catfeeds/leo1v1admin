/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-teacher_cc_count.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      date_type_config:	$('#id_date_type_config').val(),
			      date_type:	$('#id_date_type').val(),
			      opt_date_type:	$('#id_opt_date_type').val(),
			      start_time:	$('#id_start_time').val(),
			      end_time:	$('#id_end_time').val(),
            subject       : $('#id_subject').val(),
            grade_part_ex : $("#id_grade_part_ex").val(),
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
    Enum_map.append_option_list("grade_part_ex",$("#id_grade_part_ex"));
    $("#id_subject").val(g_args.subject);
    $("#id_grade_part_ex").val(g_args.grade_part_ex);
    $.admin_select_user($("#id_teacherid"), "teacherid",function(){
        load_data();
    });
	  $('.opt-change').set_input_change_event(load_data);
});
