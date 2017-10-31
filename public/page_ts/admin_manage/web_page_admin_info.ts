/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-web_page_admin_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		web_page_id:	$('#id_web_page_id').val()
    });
}
$(function(){


	$('#id_web_page_id').val(g_args.web_page_id);


	$('.opt-change').set_input_change_event(load_data);
});

