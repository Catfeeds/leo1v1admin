$(function(){
    function load_data( sid, start_time,end_time , lesson_time){
		var url="/stu_manage/lesson_cancel?sid="+sid+"&start_time="+start_time+"&end_time="+end_time+"&lesson_time="+lesson_time;
		window.location.href=url;
	}

	//时间控件
	$('#datetimepicker8').datetimepicker({
		lang:'ch',
		timepicker:false,
        onChangeDateTime :function(){
			load_data(
                g_sid,
				$("#datetimepicker8").val(),
				$("#datetimepicker9").val(),
                $("#id_lesson_time").val()
			);
		},
		format:'Y-m-d'
	});
		
	$('#datetimepicker9').datetimepicker({
		lang:'ch',
		timepicker:false,
        onChangeDateTime :function(){
			load_data(
                g_sid,
				$("#datetimepicker8").val(),
				$("#datetimepicker9").val(),
                $("#id_lesson_time").val()
			);
		},
		format:'Y-m-d'
	});

    $(".lesson_change").on("change",function(){
        load_data(
            g_sid,
			$("#datetimepicker8").val(),
			$("#datetimepicker9").val(),
            $("#id_lesson_time").val()
		);
    });
});
