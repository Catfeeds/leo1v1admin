/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_teacher_money_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacher_money_type:	$('#id_teacher_money_type').val(),
			level:	$('#id_level').val()
        });
    }
    Enum_map.append_option_list("teacher_money_type", $('#id_teacher_money_type') );
    Enum_map.append_option_list("level", $('#id_level') );


	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_level').val(g_args.level);


	$('.opt-change').set_input_change_event(load_data);
});


