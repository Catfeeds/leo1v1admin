/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ex-test_lesson_order_detail_list.d.ts" />
function load_data(){
    $.reload_self_page ( {
			  date_type_config:	$('#id_date_type_config').val(),
			  date_type:	$('#id_date_type').val(),
			  opt_date_type:	$('#id_opt_date_type').val(),
			  start_time:	$('#id_start_time').val(),
			  end_time:	$('#id_end_time').val(),
			  cur_require_adminid:	$('#id_cur_require_adminid').val(),
			  teacherid:	$('#id_teacherid').val(),
			  origin_ex:	$('#id_origin_ex').val(),
			  group_by_field:	$('#id_group_by_field').val()
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
	  $('#id_cur_require_adminid').val(g_args.cur_require_adminid);
	  $('#id_teacherid').val(g_args.teacherid);
	  $('#id_origin_ex').val(g_args.origin_ex);
	  $('#id_group_by_field').val(g_args.group_by_field);

    $.admin_select_user(
        $('#id_cur_require_adminid'),
        "admin", load_data);

    $.admin_select_user(
        $('#id_teacherid'),
        "teacher", load_data);



	  $('.opt-change').set_input_change_event(load_data);
});
