/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-check.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }

    // function get_condition() {
    //     alert('a');
    // }
    // get_condition();
    // setInterval(get_condition, 3000);
            // });

    $('.opt-change').set_input_change_event(load_data);
});
