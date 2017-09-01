
/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ex-show_sys_error_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			id:	$('#id_id').val()
        });
    }


	$('#id_id').val(g_args.id);


	$('.opt-change').set_input_change_event(load_data);
});

