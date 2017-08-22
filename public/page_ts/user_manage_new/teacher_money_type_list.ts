/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-teacher_money_type_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      teacher_money_type : $('#id_teacher_money_type').val(),
			      level              : $('#id_level').val()
        });
    }
    Enum_map.append_option_list("teacher_money_type",$("#id_teacher_money_type"),true);

    var level_map = "level";
    if(g_args.teacher_money_type==6){
        level_map = "new_level";
    }
    Enum_map.append_option_list(level_map,$("#id_level"),true);

	  $('#id_teacher_money_type').val(g_args.teacher_money_type);
	  $('#id_level').val(g_args.level);

	  $('.opt-change').set_input_change_event(load_data);

    $("#id_add_teacher_money_type").on("click",function(){
	      var teacher_money_type = $("#id_teacher_money_type").val();
	      var level = $("#id_level").val();

        if(teacher_money_type!=6){
            
        }
    });



});

