/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-admin_card_date_log_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            adminid:	$('#id_adminid').val()
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
    $('#id_adminid').val(g_args.adminid);
    $.admin_select_user( $("#id_adminid"),"admin", load_data  );


    $('.opt-change').set_input_change_event(load_data);

    if ($.get_action_str()=="admin_card_date_log_list_self" ) {
        $("#id_adminid").parent().parent().hide();
    }

    download_show();


});
