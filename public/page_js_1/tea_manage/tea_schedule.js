$(function(){
	
	function load_data( $is_part_time,$tea_nick){
	 var url="/tea_manage?tea_nick="+$tea_nick+"&is_part_time="+$is_part_time;
	 window.location.href=url;
	}

	$(".will_change").on("change",function(){
		var is_part_time = $("#id_is_part_time").val();
		var tea_nick = $("#id_teacher").val();
		load_data(is_part_time, tea_nick);
	});

	$(".stu_data").on("click",function(){
		var lessonid = $(this).data("lessonid");
		$.ajax({
			type     :"post",
			url      :"/lesson_manage/get_lesson_by_lessonid",
			dataType :"json",
			data     :{"lessonid":lessonid},
			success  : function(result){
				if(result.ret == 0){
					$("#id_student_name").html(result.lesson_info.nick);
					$("#id_lesson_interval").html(result.lesson_info.lesson_time);
					$("#id_lesson_num").html(result.lesson_info.lesson_num);
					$("#id_lesson_type").html(result.lesson_info.lesson_type);
					$("#id_lesson_intro").html(result.lesson_info.lesson_intro);
				}
			}
		});

		$(".mesg_alertCont").show();
	});

	$("#id_close_alert12").on("click",function(){
		$(".mesg_alert12").hide();
	});

	$("#id_to_teacher_students").on("click",function(){
		window.location.href="/tea_manage/get_teacher_students";
	});
});
