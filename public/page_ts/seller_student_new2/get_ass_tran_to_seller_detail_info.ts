/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-get_ass_tran_to_seller_detail_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			assistantid:	$('#id_assistantid').val(),
			campus_id:	$('#id_campus_id').val(),
			groupid:	$('#id_groupid').val(),
			leader_flag:	$('#id_leader_flag').val()
        });
    }


	$('#id_assistantid').val(g_args.assistantid);
	$('#id_campus_id').val(g_args.campus_id);
	$('#id_groupid').val(g_args.groupid);
	$('#id_leader_flag').val(g_args.leader_flag);
    $.admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });



	$('.opt-change').set_input_change_event(load_data);
});









