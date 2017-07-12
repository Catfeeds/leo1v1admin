 /// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-assistant.d.ts" />

$(function(){
   
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
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

    function show_top( $person_body_list) {
        
        $($person_body_list[0]).find("td").css(
            {
                "color" :"red"
            } 
        );
        $($person_body_list[1]).find("td").css(
            {
                "color" :"orange"
            } 
        );

        $($person_body_list[2]).find("td").css(
            {
                "color" :"blue"
            } 
        );

    }

    show_top( $("#id_lesson_count_list > tr")) ;
    show_top( $("#id_assistant_renew_list > tr")) ;



	$('.opt-change').set_input_change_event(load_data);
});

