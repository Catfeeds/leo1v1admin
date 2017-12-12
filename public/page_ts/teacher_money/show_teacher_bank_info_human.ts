/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_money-show_teacher_bank_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
 			      teacherid:	$('#id_teacherid').val(),
            is_bank:    $("#id_is_bank").val()
        });
    }


	  $('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user( $("#id_teacherid"), "teacher",load_data);

    $('#id_is_bank').val(g_args.is_bank);


	$('.opt-change').set_input_change_event(load_data);
});
