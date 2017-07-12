/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-todo_list.d.ts" />


$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            todo_type:	$('#id_todo_type').val(),
            todo_status:	$('#id_todo_status').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
    $('#id_todo_type').val(g_args.todo_type);
    $.enum_multi_select( $('#id_todo_type'), 'todo_type', function(){load_data();} )
    $('#id_todo_status').val(g_args.todo_status);
    $.enum_multi_select( $('#id_todo_status'), 'todo_status', function(){load_data();} )


    $('.opt-change').set_input_change_event(load_data);


    $(".opt-jump").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen(opt_data["jump_url"] );
    });

    $(".opt-reset").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/ajax_deal/todo_reset",{
            "todoid": opt_data["todoid"],
        });

    });

});
