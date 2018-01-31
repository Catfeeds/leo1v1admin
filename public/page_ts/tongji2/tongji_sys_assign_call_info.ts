/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-tongji_sys_assign_call_info.d.ts" />

function load_data(){
  if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
    date_type_config:	$('#id_date_type_config').val(),
    date_type:	$('#id_date_type').val(),
    opt_date_type:	$('#id_opt_date_type').val(),
    start_time:	$('#id_start_time').val(),
    end_time:	$('#id_end_time').val(),
    adminid:	$('#id_adminid').val(),
    userid:	$('#id_userid').val()
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
    }});

  $('#id_adminid').admin_select_user_new({
    "user_type"    : "account",
    "select_value" : g_args.adminid,
    "onChange"     : load_data,
    "th_input_id"  : "th_adminid",
    "only_show_in_th_input"     : false,
    "can_select_all_flag"     : true
  });
  $('#id_userid').admin_select_user_new({
    "user_type"    : "student",
    "select_value" : g_args.userid,
    "onChange"     : load_data,
    "th_input_id"  : "th_userid",
    "only_show_in_th_input"     : false,
    "can_select_all_flag"     : true
  });


  $('.opt-change').set_input_change_event(load_data);
});
