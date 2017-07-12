$(function(){
	function load_data( $tea_nick,$is_part_time){
	 var url="/tea_manage/get_teacher_students?tea_nick="+$tea_nick+"&is_part_time="+$is_part_time;
	 window.location.href=url;
	}
	
	$(".will_change").on("change",function(){
		var is_part_time = $("#id_is_part_time").val();
		var tea_nick = $("#id_teacher_list").val();
		load_data(tea_nick, is_part_time);
	});

	$("#id_teacher_search").on("click",function(){
		var is_part_time = $("#id_is_part_time").val();
		var tea_nick = $("#id_tea_name").val();
		if(tea_nick == ""){
			alert("请输入老师姓名");
		}else{
			load_data(tea_nick, is_part_time);
		}
	});

	$("#id_to_teacher_time").on("click",function(){
		window.location.href = "/tea_manage";
	})

});
