$(function(){
    function load_data(is_part_time, year, month, tea_nick ){
		var url="/human_resource/teacher_salary?is_part_time="+is_part_time+"&year="+year+"&month="+month+"&tea_nick="+tea_nick;
		window.location.href=url;
	}

    $(".sal_change").on("change",function(){
        var is_part_time = $('#id_is_part_time').val();
        var year = $('#id_year').val();
        var month = $('#id_month').val();
        load_data(is_part_time, year, month, "");
    });

    $("#id_search_teacher").on("click",function(){
        var tea_nick = $("#id_tea_name").val();
        if(tea_nick == ""){
            alert("请输入教师姓名");
        }else{
		    var url="/human_resource/teacher_salary?tea_nick="+tea_nick;
		    window.location.href=url;
        }
    });

});
