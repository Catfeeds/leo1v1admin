
function load_data(){
    reload_self_page({
        start_time         : $("#id_start_time").val(),
        end_time           : $("#id_end_time").val(),
        teacher_money_type : $("#id_teacher_money_type").val()
    });
}

$(function(){
    Enum_map.append_option_list("teacher_money_type",$("#id_teacher_money_type"));

    //init  input data
	$("#id_start_time").val(g_args.start_time);
	$("#id_end_time").val(g_args.end_time);
	$("#id_teacher_money_type").val(g_args.teacher_money_type);

    $("#id_teacher_money_type").on("change",function(){
        load_data();
    });

	//时间控件
	$('#id_start_time').datetimepicker({
		lang       : 'ch',
		format     : 'Y-m-d',
		timepicker : false,
        onChangeDateTime :function(){
            load_data();
		}
	});
	
	$('#id_end_time').datetimepicker({
		lang       : 'ch',
		format     : 'Y-m-d',
		timepicker : false,
        onChangeDateTime :function(){
            load_data(
			);
		}
	});

    
    $(".opt-show-lesson-list").on("click",function(){
        var teacherid=$(this).get_opt_data("teacherid");
	    
//1v1.com/tea_manage/lesson_list?start_date=2016-05-17&end_date=2016-05-21&lesson_type=-2&confirm_flag=-1&subject=-1&studentid=-1&teacherid=58833&seller_adminid=-1&teacherid=-1&is_with_test_user=-1
        wopen( "/tea_manage/lesson_list?start_date="+ g_args.start_time
               +"&end_date="+g_args.end_time
               +"&lesson_type=-2"
               +"&teacherid="+teacherid
               +"&is_with_test_user=0"
             );
    });


});
