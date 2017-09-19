/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-stu_all_teacher_all.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            assistantid:	$('#id_assistantid').val(),
        });
    }


    $('#id_assistantid').val(g_args.assistantid);
	  $.admin_select_user( $('#id_assistantid'), "assistant",load_data );
	  $('.opt-change').set_input_change_event(load_data);
});
