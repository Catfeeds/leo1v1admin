
$(function(){
	
	//tab栏
	tab('.nav_tit .upload_mate','.upload_mate','.stu_tab12 td','.load',0);
	
	//按钮
	btn_s('.stu_data','.mesg_alert12');//查看详情
	btn_s('.upload_list .done_r','.mesg_alert18');//查看课件详情
	btn_s('.work_list .done_r','.mesg_alert19');//查看作业批改详情
	btn_s('.text_list .done_r','.mesg_alert20');//试卷作业批改详情

	function load_data($tea_nick,$is_part_time){
		var url="/tea_manage/courseware_info?tea_nick="+$tea_nick+"&is_part_time="+$is_part_time;
		window.location.href=url;
	}
	
	$(".will_change").on("change",function(){
		var is_part_time = $("#id_is_part_time").val();
		var tea_nick = $("#id_teacher_list").val();
		load_data(tea_nick, is_part_time);
	});

	$(".done_r").on("click",function(){
		var teacherid = $(this).parent().data("teacherid");
		var url="/tea_manage/courseware_detail?teacherid="+teacherid;
		window.location.href=url;
	});

})

