/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-check.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }

    setInterval ("showTime()", 5000);
    function showTime()
    {
        var today = new Date();
        alert("The time is: " + today.toString ());
    }

    $('.opt-change').set_input_change_event(load_data);
});
