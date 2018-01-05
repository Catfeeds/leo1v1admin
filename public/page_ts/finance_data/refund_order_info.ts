/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/finance_data-refund_order_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){


    var screen_height=window.screen.availHeight-300;        

    $(".common-table").parent().css({"overflow":"auto"});

	$('.opt-change').set_input_change_event(load_data);
});

