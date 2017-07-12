/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-dean_teacher.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {		
        });
    }


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

    show_top( $("#id_per_count_list > tr")) ;

	$('.opt-change').set_input_change_event(load_data);
});



