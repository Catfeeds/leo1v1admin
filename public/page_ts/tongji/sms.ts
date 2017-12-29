
/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-sms.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      date_type:	$('#id_date_type').val(),
			      opt_date_type:	$('#id_opt_date_type').val(),
			      start_time:	$('#id_start_time').val(),
			      end_time:	$('#id_end_time').val(),
			      is_succ:	$('#id_is_succ').val(),
			      type:	$('#id_type').val()
        });
    }

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

    Enum_map.append_option_list("sms_type",$("#id_type"));
	  $('#id_is_succ').val(g_args.is_succ);
	  $('#id_type').val(g_args.type);

	  $('.opt-change').set_input_change_event(load_data);
});


