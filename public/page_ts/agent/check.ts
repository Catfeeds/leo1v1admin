/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-check.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }

    var c=0;
    var t;
    function timedCount(){
        $('#txt').attr('value',c);
        c=c+1;
        t=setTimeout("timedCount()",1000);
    }
    $('.opt-change').set_input_change_event(load_data);
});
