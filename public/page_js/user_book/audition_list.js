$(function(){
    $("#id_teacherid").val(g_teacherid);
    $("#id_teacherid").admin_select_user({
        "type"   : "teacher",
        "onChange": function(){
            load_data();
        }
    });

    Enum_map.append_option_list( "contract_type_ex", $("#id_lesson_type"));
    Enum_map.append_option_list( "subject", $("#id_subject"));
    $('#id_start_date').val(g_start_date);
    $('#id_end_date').val(g_end_date);
    $('#id_is_with_test_user').val(g_is_with_test_user);
	$("#id_lesson_type").val(g_args.lesson_type);
	$("#id_subject").val(g_args.subject);
	//TODO
	//时间控件
	$('#id_start_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
	    onChangeDateTime :function(){
		    load_data();
        }
	});
	$('#id_end_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		onChangeDateTime :function(){
		    load_data();
        }
	});
	//时间控件-over
	function load_data( ){
        var start_date=$("#id_start_date").val();
        var end_date=$("#id_end_date").val();
		var lesson_type=$("#id_lesson_type").val();
		var subject=$("#id_subject").val();
        var studentid=0;
        var teacherid=$("#id_teacherid").val();
        var is_with_test_user=$("#id_is_with_test_user").val();

	    var url="/tea_manage/lesson_list?start_date="+start_date+"&end_date="+end_date+
                "&studentid="+studentid+"&teacherid="+teacherid+
                "&lesson_type="+lesson_type+"&subject="+subject+
                "&is_with_test_user="+is_with_test_user;
	    window.location.href=url;
	}

	$(".opt-change").on("change",function(){
		load_data();
	});	

    $(".opt-upload").on("click", function(){
        $(this).addClass('current_opt_lesson_record');
        var html_node   = $('<div></div>').html(dlg_get_html_by_class('dlg_upload'));
        var lesson_info = new Object();
        
        lesson_info.lessonid      = $(this).parent().data("lessonid");
        lesson_info.lesson_status = $(this).parents('td').siblings('.lesson_status').find('.status').val();
        lesson_info.work_status   = $(this).parents('td').siblings('.homework_url').find('.status').val();
        
        html_node.find(".opt-teacher-url").attr('id', 'optid-teacher-url'+lesson_info.lessonid);
        html_node.find(".opt-teacher-url").parent().attr('id', 'optid-teacher-url-parent'+lesson_info.lessonid);
        html_node.find(".opt-student-url").attr('id', 'optid-student-url'+lesson_info.lessonid);
        html_node.find(".opt-student-url").parent().attr('id', 'optid-student-url-parent'+lesson_info.lessonid);
        html_node.find(".opt-homework-url").attr('id', 'optid-homework-url'+lesson_info.lessonid);
        html_node.find(".opt-homework-url").parent().attr('id', 'optid-homework-url-parent'+lesson_info.lessonid);
        // add lesson quiz
        html_node.find(".opt-quiz-url").attr('id', 'optid-quiz-url'+lesson_info.lessonid);
        html_node.find(".opt-quiz-url").parent().attr('id', 'optid-quiz-url-parent'+lesson_info.lessonid);

        html_node.find(".lesson_time").text($(this).parents('td').siblings('.lesson_time').text());
        html_node.find(".tea_nick").text($(this).parents('td').siblings('.tea_nick').text());
        html_node.find(".stu_nick").text($(this).parents('td').siblings('.stu_nick').text());

        var lessonid = $(this).parent().data('lessonid');
        var grade    = $(this).parent().data('grade');
        var subject  = $(this).parent().data('subject');
        
        BootstrapDialog.show({
	        title: "上传本次课的课件或作业",
	        message :  html_node,
            onhide: function(dialogRef){
                $('.current_opt_lesson_record').removeClass('current_opt_lesson_record');
            },
            onshown: function(  dialog )  {
                custom_upload_file( 'optid-teacher-url'+lesson_info.lessonid ,
                                    false ,setCompleteTeacher, lesson_info,
                                    ["pdf","zip"], setProgress );
            },

	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
                    $('.current_opt_lesson_record').removeClass('current_opt_lesson_record');
			        dialog.close();
		        }
	        }, {
		        label    : '添加课堂作业',
		        cssClass : 'btn-warning',
		        action   : function(dialog) {
                    if(grade<200){
                        grade=100;
                    }else if(grade<300){
                        grade=200;
                    }else{
                        grade=300;
                    }

                    var url="/tea_manage/get_homework_list?lessonid="+lessonid+"&grade="+grade+"&subject="+subject;
                    window.location.href=url;
			        dialog.close();
		        }
	        }, {
		        label: '完成',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    $('.current_opt_lesson_record').removeClass('current_opt_lesson_record');
                    window.location.reload();
			        dialog.close();
		        }
	        }]
        });
    });

    $(".opt-small-class-or-open" ).each(function( ){
        var lesson_type= $(this).get_opt_data("lesson_type");
        var lessonid= $(this).get_opt_data("lessonid");
        var courseid = $(this).get_opt_data("courseid");
        var stu_id= $(this).get_opt_data("stu_id");
        if (lesson_type == 3001){
           $(this).attr("attr", "/small_class/index?courseid="+courseid );
        } else if (lesson_type  >= 1000 & lesson_type<2000 ){
            $(this).attr("attr", "/tea_manage/open_class?lessonid="+lessonid);
        } else if (lesson_type  >= 0 & lesson_type<1000 ){
            $(this).attr("attr", "/stu_manage/lesson_plan/?sid="+stu_id);
        }else{
            $(this).hide();
        }
        //alert(lesson_type);
    });

    
    $(".opt-out-link").on("click",function(){
	    var lessonid= $(this).get_opt_data("lessonid");
        do_ajax( "/common/encode_text",{
            "text"  : lessonid 
        }, function(ret){
            BootstrapDialog.alert("对外链接: http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text  );
        });
    });

    $(".for_input").on ("keypress", function( e){
		if (e.keyCode==13){
		    var id_lesson = $("#id_lesson").val();
	    	if( id_lesson == ""){
		    	alert("请输入课程ID");
		    }else{
		    	var url = "/tea_manage/lesson_list?lessonid="+id_lesson;
		    	window.location.href = url;
		    }
		}
	});

	$("#id_search_lesson").on("click", function(){
		var id_lesson = $("#id_lesson").val();
		if( id_lesson == ""){
			alert("请输入课程ID");
		}else{
			var url = "/tea_manage/lesson_list?lessonid="+id_lesson;
			window.location.href = url;
		}
	});
    

    $("#id_studentid").val(g_studentid);
    $("#id_studentid").admin_select_user({
        "type"     : "student",
        "onChange" : function(){
            load_data( -1,-1,$("#id_studentid").val(),"","" );
        }
    });

});
