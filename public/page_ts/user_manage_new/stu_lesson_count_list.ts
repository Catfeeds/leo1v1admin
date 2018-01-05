/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-stu_lesson_count_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      date_type:	$('#id_date_type').val(),
			      opt_date_type:	$('#id_opt_date_type').val(),
			      start_time:	$('#id_start_time').val(),
			      end_time:	$('#id_end_time').val(),
		        page_number:	$('#id_page_number').val(),
			      assistantid:	$('#id_assistantid').val()
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
	$('#id_assistantid').val(g_args.assistantid);

    $.admin_select_user( $('#id_assistantid'), "assistant", load_data );


	$('#id_page_number').val(g_args.page_number);

	$('.opt-change').set_input_change_event(load_data);

    $(".opt-show-lesson-list").on("click",function(){
	    //
        var opt_data=$(this).get_opt_data();
	    
//1v1.com/tea_manage/lesson_list?start_date=2016-05-17&end_date=2016-05-21&lesson_type=-2&confirm_flag=-1&subject=-1&studentid=-1&teacherid=58833&seller_adminid=-1&assistantid=-1&is_with_test_user=-1
        $.wopen( "/tea_manage/lesson_list?start_time="+ g_args.start_time
               +"&end_time="+g_args.end_time
               +"&opt_date_type="+g_args.opt_date_type
               +"&date_type="+g_args.date_type
               +"&lesson_type=-2"
               +"&studentid="+opt_data.userid
               +"&is_with_test_user=0"
             );
    });

});

