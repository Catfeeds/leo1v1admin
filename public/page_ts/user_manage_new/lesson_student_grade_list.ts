/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-lesson_student_grade_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
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

    
    $(".opt-lesson-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/tea_manage/lesson_list?opt_date_type=0&start_time="+g_args.start_time + "&end_time=" + g_args.end_time + "&studentid=" + opt_data.userid  ,false);
	    
    });


	$('.opt-change').set_input_change_event(load_data);
});

