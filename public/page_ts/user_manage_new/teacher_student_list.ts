/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-teacher_student_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val()
        });
    }


	$('#id_teacherid').val(g_args.teacherid);

    $.admin_select_user($("#id_teacherid"), "teacher", function(){
        load_data();
    });

	$('.opt-change').set_input_change_event(load_data);
});

