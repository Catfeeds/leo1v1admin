/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_total_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      date_type_config      :	$('#id_date_type_config').val(),
			      date_type             :	$('#id_date_type').val(),
			      opt_date_type         :	$('#id_opt_date_type').val(),
			      start_time            :	$('#id_start_time').val(),
			      end_time              :	$('#id_end_time').val(),
			      teacherid             :	$('#id_teacherid').val(),
			      teacher_money_type    :	$('#id_teacher_money_type').val(),
			      level                 :	$('#id_level').val(),
			      is_test_user          :	$('#id_is_test_user').val(),
			      train_through_new     :	$('#id_train_through_new').val(),
			      trial_lecture_is_pass :	$('#id_trial_lecture_is_pass').val()
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

    Enum_map.append_option_list( "teacher_money_type", $("#id_teacher_money_type"));
    Enum_map.append_option_list( "level", $("#id_level"));
    Enum_map.append_option_list( "boolean", $("#id_is_test_user"));
	  $('#id_teacherid').val(g_args.teacherid);
	  $('#id_teacher_money_type').val(g_args.teacher_money_type);
	  $('#id_level').val(g_args.level);
	  $('#id_is_test_user').val(g_args.is_test_user);
	  $('#id_train_through_new').val(g_args.train_through_new);
	  $('#id_trial_lecture_is_pass').val(g_args.trial_lecture_is_pass);

    $.admin_select_user( $("#id_teacherid"),"teacher", load_data);

	  $('.opt-change').set_input_change_event(load_data);
});
