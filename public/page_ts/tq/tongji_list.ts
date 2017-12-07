/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tq-tongji_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      callerid:	$('#id_callerid').val(),
			      account_role:	$('#id_account_role').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val()
        });
    }

	  Enum_map.append_option_list("account_role",$("#id_account_role"));

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
	  $('#id_account_role').val(g_args.account_role);
	  $('#id_callerid').val(g_args.callerid);

    $.admin_select_user($('#id_callerid'), "admin", load_data);



    $('.opt-change').set_input_change_event(load_data);

    $(".opt-show").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen( "/tq/get_list?" +
                 '&date_type=' + g_args.date_type+
                 '&opt_date_type=' + g_args.opt_date_type+
                 '&start_time='    + g_args.start_time+
                 '&end_time='      + g_args.end_time+
                 '&uid='      +  opt_data.adminid
               );
    });

});
