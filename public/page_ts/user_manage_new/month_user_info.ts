/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-month_user_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			year:	$('#id_year').val(),
			month:	$('#id_month').val()
        });
    }

	$('#id_year').val(g_args.year);
	$('#id_month').val(g_args.month);


	$('.opt-change').set_input_change_event(load_data);
});


