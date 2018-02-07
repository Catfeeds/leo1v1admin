/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_order_channel_from_orderno_info.d.ts" />

function load_data(){
	  if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		    date_type_config:	$('#id_date_type_config').val(),
		    date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val(),
		    contract_type:	$('#id_contract_type').val(),
		    channel_origin:	$('#id_channel_origin').val(),
		    channel:	$('#id_channel').val(),
		    name_str:	$('#id_name_str').val()
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
    Enum_map.append_option_list( "contract_type", $("#id_contract_type"));
	  $('#id_contract_type').val(g_args.contract_type);
	  $('#id_channel_origin').val(g_args.channel_origin);
	  $('#id_channel').val(g_args.channel);
	  $('#id_name_str').val(g_args.name_str);


    if(g_account=="zero"  ){
        download_show();
    }

	  $('.opt-change').set_input_change_event(load_data);
});
