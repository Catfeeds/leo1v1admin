/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-teacher_switch_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page({
			      teacher_money_type : $('#id_teacher_money_type').val(),
			      teacherid          : $('#id_teacherid').val(),
			      batch              : $('#id_batch').val(),
			      status             : $('#id_status').val()
        });
    }

    Enum_map.append_option_list("teacher_money_type",$("#id_teacher_money_type"));
    Enum_map.append_option_list("switch_status",$("#id_status"));
	  $('#id_teacher_money_type').val(g_args.teacher_money_type);
	  $('#id_teacherid').val(g_args.teacherid);
	  $('#id_batch').val(g_args.batch);
	  $('#id_status').val(g_args.status);

	  $('.opt-change').set_input_change_event(load_data);
});
