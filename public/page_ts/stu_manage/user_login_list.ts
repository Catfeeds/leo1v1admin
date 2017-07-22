///<reference path="../common.d.ts" />
///<reference path="../g_args.d.ts/stu_manage-todo_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            sid:	$('#id_sid').val()
        });
    }

    $('.opt-change').set_input_change_event(load_data);
});
