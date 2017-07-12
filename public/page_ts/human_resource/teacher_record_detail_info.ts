/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_record_detail_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val(),
			type:	$('#id_type').val(),
			add_time:	$('#id_add_time').val()
        });
    }


	$('#id_teacherid').val(g_args.teacherid);
	$('#id_type').val(g_args.type);
	$('#id_add_time').val(g_args.add_time);


	$('.opt-change').set_input_change_event(load_data);


});
