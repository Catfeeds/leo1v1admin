/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-get_month_money_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			year:	$('#id_year').val()
        });
    }


	$('#id_year').val(g_args.year);


	$('.opt-change').set_input_change_event(load_data);
});
