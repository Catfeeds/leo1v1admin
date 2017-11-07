/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_money-teacher_salary_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		    date_type_config:	$('#id_date_type_config').val(),
		    date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val()
    });
}

$(function(){
    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery          : function() {
            load_data();
        }
    });
    Enum_map.append_option_list("teacher_money_type",$("#id_teacher_money_type"));
    Enum_map.append_option_list("teacher_type",$("#id_teacher_type"));
    Enum_map.append_option_list("level",$("#id_level"));

	$('.opt-change').set_input_change_event(load_data);
});
