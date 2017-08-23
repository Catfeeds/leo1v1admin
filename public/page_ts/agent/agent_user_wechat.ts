
/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_user_wechat.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      phone:	$('#id_phone').val(),
			      id:	$('#id_id').val()
        });
    }


	$('#id_phone').val(g_args.phone);
	$('#id_id').val(g_args.id);


	$('.opt-change').set_input_change_event(load_data);
});

