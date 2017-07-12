/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-order_tmk_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      adminid:	$('#id_adminid').val(),
			      start_time:	$('#id_start_time').val(),
			      end_time:	$('#id_end_time').val()
        });
    }


	$('#id_adminid').val(g_args.adminid);
	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);


	$('.opt-change').set_input_change_event(load_data);
});

