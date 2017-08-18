/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-student_school_score_stat.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      username:	$('#id_username').val(),
			      grade:	$('#id_grade').val(),
			      semester:	$('#id_semester').val(),
			      stu_score_type:	$('#id_stu_score_type').val()
        });
    }
    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("semester",$("#id_semester"));
    Enum_map.append_option_list("stu_score_type",$("#id_stu_score_type"));


	  $('#id_username').val(g_args.username);
	  $('#id_grade').val(g_args.grade);
	  $('#id_semester').val(g_args.semester);
	  $('#id_stu_score_type').val(g_args.stu_score_type);
	  $('.opt-change').set_input_change_event(load_data);
});
