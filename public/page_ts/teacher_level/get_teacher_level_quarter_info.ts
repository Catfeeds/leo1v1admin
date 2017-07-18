/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_teacher_level_quarter_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacher_money_type:	$('#id_teacher_money_type').val()
        });
    }

    Enum_map.append_option_list("teacher_money_type", $("#id_teacher_money_type"),true,[1,4,5]);

	$('#id_teacher_money_type').val(g_args.teacher_money_type);


	$('.opt-change').set_input_change_event(load_data);
});







