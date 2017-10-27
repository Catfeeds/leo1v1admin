/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent_info-members_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            group_id:	$('#id_group').val()
        });
    }
    
    $('#id_group').val(g_args.group_id > 0 ? g_args.group_id:-1);


    $('.opt-change').set_input_change_event(load_data);

});
