/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ex-threshold_detail.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
        $.reload_self_page ( {
        start_time:    $('#id_start_time').val(),
        end_time:    $('#id_end_time').val()
        });
}
$(function(){


    $('#id_start_time').val(g_args.start_time);
    $('#id_end_time').val(g_args.end_time);


    $('.opt-change').set_input_change_event(load_data);
});
