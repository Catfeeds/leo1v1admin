/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-seller_origin_info.d.ts" />
    function load_data(){
        $.reload_self_page ( {
			      origin_level:	$('#id_origin_level').val(),
			      date_type_config:	$('#id_date_type_config').val(),
			      date_type:	$('#id_date_type').val(),
			      opt_date_type:	$('#id_opt_date_type').val(),
			      start_time:	$('#id_start_time').val(),
			      end_time:	$('#id_end_time').val(),
			      origin_ex:	$('#id_origin_ex').val(),
			      tmk_student_status:	$('#id_tmk_student_status').val()
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
	  $('#id_origin_ex').val(g_args.origin_ex);

	$('#id_origin_level').val(g_args.origin_level);

	  $.enum_multi_select( $('#id_origin_level'), 'origin_level', function(){load_data();},null, {
        "T类" : [90],
        "非T类" : [0,1,2,3,4,100],
    }  );

	  $('#id_tmk_student_status').val(g_args.tmk_student_status);
	  $.enum_multi_select( $('#id_tmk_student_status'), 'tmk_student_status', function(){load_data();},null, {

        "有效" : [3],
        "其它" : [0,1,2],
    });

	  $('.opt-change').set_input_change_event(load_data);

    $(".common-table" ).table_admin_level_4_init();
});
