// SWITCH-TO:   ../../template/student/teaching_plan.html  

$(function(){
	$("#id_btn_edit").on("click",function(){
		//设置select
		var lesson_list=$("#id_teaching_list tr");
		$.each( lesson_list ,function(i,item ){
			var opt_tr= $(item);
			var lessonid=opt_tr.data("lessonid");
			var value_list=opt_tr.find("input");
			$(value_list[0]).val( $(value_list[0]). siblings().text() );
			$(value_list[1]).val( $(value_list[1]). siblings().text() );
		});
	});

	$("#id_btn_save").on("click",function(){
		var lesson_list=$("#id_teaching_list tr");
		var i=0;
		var lesson_data_list=[];
        var courseid_list=[];
		$.each( lesson_list ,function(i,item ){
			var opt_tr= $(item);
			var lessonid=opt_tr.data("lessonid");
			var value_list=opt_tr.find("input");
            var courseid = opt_tr.find("td:first-child").html();
            if($.inArray(courseid, courseid_list) == -1){
                courseid_list.push(courseid); 
            }
			lesson_data_list.push( {
				"lessonid":lessonid ,
				"lesson_intro":  $(value_list[0]).val()+"|"+$(value_list[1]).val()
			});

		});

		var str=JSON.stringify(lesson_data_list);
        var courseid_str = JSON.stringify(courseid_list);
		//post
		//返回可排课程列表调取新数据
		$.ajax({
            url: '/stu_manage/lesson_set_intro_list',
            type: 'POST',
            data: { "sid":g_sid  ,"list_data":str, "courseid_list":courseid_str },
            dataType: 'json',
            success: function(data) {
                if (data['ret'] != 0) {
                    alert(data['info']);
                }
                window.location.reload();
            }
        });//返回可排课程列表调取新数据over

	});

});
