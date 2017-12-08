/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-select_teacher_for_test_lesson.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page({
		    identity:	$('#id_identity').val(),
		    gender:	$('#id_gender').val(),
		    age:	$('#id_age').val(),
		    require_id:	$('#id_require_id').val(),
		    refresh_flag:	$('#id_refresh_flag').val()
    });
}

$(function(){
	  $('#id_identity').val(g_args.identity);
	  $('#id_gender').val(g_args.gender);
	  $('#id_age').val(g_args.age);
	  $('#id_require_id').val(g_args.require_id);
	  $('#id_refresh_flag').val(g_args.refresh_flag);
	  $('.opt-change').set_input_change_event(load_data);



});
