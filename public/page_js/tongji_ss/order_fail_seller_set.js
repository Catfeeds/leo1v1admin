/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-order_fail_seller_set.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			origin_userid_flag:	$('#id_origin_userid_flag').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_origin_userid_flag")); 

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
	$('#id_origin_userid_flag').val(g_args.origin_userid_flag);


	$('.opt-change').set_input_change_event(load_data);
});

