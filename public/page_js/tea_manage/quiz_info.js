$(function(){
     $("#id_is_part_time").val(g_args.is_part_time);
     $("#id_teacher_list").val(g_args.tea_nick);

	function load_data($tea_nick,$is_part_time){
		var url="/tea_manage/quiz_info?tea_nick="+$tea_nick+"&is_part_time="+$is_part_time;
		window.location.href=url;
	}
	
	$(".will_change").on("change",function(){
		var is_part_time = $("#id_is_part_time").val();
		var tea_nick = $("#id_teacher_list").val();
		load_data(tea_nick, is_part_time);
	});

	$("#id_search_teacher").on("click",function(){
		var tea_nick = $("#id_tea_name").val();
		if(tea_nick == ""){
			alert("请输入老师姓名");
		}else{
			var is_part_time = $("#id_is_part_time").val();
			load_data(tea_nick, is_part_time);
		}
	});

	$(".done_r").on("click", function(){
		var teacherid = $(this).parent().data("teacherid");
		var url = "/tea_manage/quiz_detail?teacherid="+teacherid;
		window.location.href=url;
	});
});
