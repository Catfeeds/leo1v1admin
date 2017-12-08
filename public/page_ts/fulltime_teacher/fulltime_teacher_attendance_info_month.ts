/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/fulltime_teacher-fulltime_teacher_attendance_info_month.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		teacherid:	$('#id_teacherid').val(),
		adminid:	$('#id_adminid').val(),
		fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
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
    $("#id_opt_date_type").hide();

    Enum_map.append_option_list("fulltime_teacher_type", $("#id_fulltime_teacher_type"),false,[1,2]);
	  $('#id_teacherid').val(g_args.teacherid);
	  $('#id_adminid').val(g_args.adminid);
	  $('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);
	  $('#id_adminid').val(g_args.adminid);
    $.admin_select_user($('#id_teacherid'),
                        "teacher", load_data);
    $.admin_select_user(
        $('#id_adminid'),
        "admin", load_data,false,{"main_type":5});



	$('.opt-change').set_input_change_event(load_data);
});







