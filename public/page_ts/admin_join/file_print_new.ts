/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_join-file_print_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			phone:	$('#id_phone').val()
        });
    }


	$('#id_phone').val(g_args.phone);


	$('.opt-change').set_input_change_event(load_data);
});
