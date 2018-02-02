/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-job_list.d.ts" />

function load_data(){
	if (window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		    query_text:	$('#id_query_text').val()
		});
}
$(function(){
	$('#id_query_text').val(g_args.query_text);


	$('.opt-change').set_input_change_event(load_data);
});
