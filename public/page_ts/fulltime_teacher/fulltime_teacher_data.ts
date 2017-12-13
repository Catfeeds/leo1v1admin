/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/fulltime_teacher-fulltime_teacher_data.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
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

    var config_list=["apply_num","arrive_num","arrive_through","second_through","enter_num",
                     "arrive_num_per","arrive_through_per","second_through_per","enter_num_per",
                     "leave_num","leave_per"];
    
    $.each( config_list,  function(){
        var config_type=this; 
        $.do_ajax("/ajax_deal2/fulltime_teacher_data_with_type",{
			      start_time: g_args.start_time	,
			      end_time: g_args.end_time	,
            "type" :  config_type,
        } ,function(resp){
            $("#id_"+config_type).text(resp.value) ;
        } );

    } );

	$('.opt-change').set_input_change_event(load_data);
});

