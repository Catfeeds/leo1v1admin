$(function(){
    $("#id_year").val(g_args.year);
    $("#id_month").val(g_args.month);

	function load_data($year, $month, $name){
		url = "/human_resource/performance?name="+$name+"&year="+$year+"&month="+$month;
		window.location.href = url;
	}

	$(".per_change").on("change", function(){
		var year = $("#id_year").val();
		var month = $("#id_month").val();
		load_data(year, month, "");
	});

	$(".stu_search").on("click",function(){
		var name = $("#id_manager_name").val();
		if(name ==""){
			alert('请输入教师姓名');
		}else{
			url = "/human_resource/performance?name="+name;
			window.location.href = url;
		}
	});
});
