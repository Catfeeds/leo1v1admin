///<reference path="../common.d.ts" />
///<reference path="../g_args.d.ts/stu_manage-todo_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type     :	$('#id_date_type').val(),
            opt_date_type :	$('#id_opt_date_type').val(),
            start_time    :	$('#id_start_time').val(),
            end_time      :	$('#id_end_time').val(),
            sid           :	$('#id_sid').val()
        });
    }

    Enum_map.append_option_list("boolean",$("#id_dymanic_flag"));
    $("#id_dymanic_flag").val(g_args.dymanic_flag);

    $('.opt-change').set_input_change_event(load_data);
});
