/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-get_check_textbook_tea_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
        });
    }

    $(".fa-download").hide();


	  $('.opt-change').set_input_change_event(load_data);
});
