/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-seller_time_income_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }

    $('.opt-change').set_input_change_event(load_data);
});

