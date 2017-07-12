/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-valid_user_money_info.d.ts" />

function load_data(){
    $.reload_self_page ( {
			  date_type             :	$('#id_date_type').val(),
			  opt_date_type         :	$('#id_opt_date_type').val(),
			  start_time            :	$('#id_start_time').val(),
			  end_time              :	$('#id_end_time').val(),

			  date_type_1             :	$('#id_date_type_1').val(),
			  opt_date_type_1         :	$('#id_opt_date_type_1').val(),
			  start_time_1            :	$('#id_start_time_1').val(),
			  end_time_1              :	$('#id_end_time_1').val(),

        origin_ex             :	$('#id_origin_ex').val(),
			  competition_flag      :	$('#id_competition_flag').val(),
			  check_field_id:	$('#id_check_field_id').val(),
			  grade                 :	$('#id_grade').val(),

			  origin_from_user_flag :	$('#id_origin_from_user_flag').val(),
			  phone_location        :	$('#id_phone_location').val(),
			  subject               :	$('#id_subject').val()
    });
}

$(function(){

	  Enum_map.append_option_list("subject",$("#id_subject"));
	  Enum_map.append_option_list("boolean",$("#id_origin_from_user_flag"));
	  Enum_map.append_option_list("boolean",$("#id_competition_flag"));

	  Enum_map.append_option_list("grade",$("#id_grade"));
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
    $('#id_date_range_1').select_date_range({
        "field_index" :1,
        'date_type' : g_args.date_type_1,
        'opt_date_type' : g_args.opt_date_type_1,
        'start_time'    : g_args.start_time_1,
        'end_time'      : g_args.end_time_1,
        date_type_config : JSON.parse( g_args.date_type_config_1),
        onQuery :function() {
            load_data();
        }
    });


    $('#id_origin_ex').val(g_args.origin_ex);
	  $('#id_grade').val(g_args.grade);
	  $('#id_check_field_id').val(g_args.check_field_id);
	  $('#id_phone_location').val(g_args.phone_location);
	  $('#id_subject').val(g_args.subject);
	  $('#id_origin_from_user_flag').val(g_args.origin_from_user_flag);
	  $('#id_competition_flag').val(g_args.competition_flag);


	  $('.opt-change').set_input_change_event(load_data);

    $(".common-table").table_group_level_4_init();


});

