/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-stu_all_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			student_type:	$('#id_student_type').val(),
			teacherid:	$('#id_teacherid').val()
        });
    }

    Enum_map.append_option_list("student_type", $("#id_student_type"));
	$('#id_student_type').val(g_args.student_type);
	$('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user($("#id_teacherid"),"teacher",load_data);


	$('.opt-change').set_input_change_event(load_data);
});



