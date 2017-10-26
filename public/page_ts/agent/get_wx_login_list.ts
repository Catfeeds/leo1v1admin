/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-get_wx_login_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		to_agentid:	$('#id_to_agentid').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		agent_wx_msg_type:	$('#id_agent_wx_msg_type').val()
    });
}
$(function(){


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

	  $('#id_to_agentid').val(g_args.to_agentid);
	  $('#id_agent_wx_msg_type').val(g_args.agent_wx_msg_type);
	  $.enum_multi_select( $('#id_agent_wx_msg_type'), 'agent_wx_msg_type', function(){load_data();} )


	  $('.opt-change').set_input_change_event(load_data);
});


