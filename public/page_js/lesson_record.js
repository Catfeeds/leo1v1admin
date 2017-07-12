$(function(){
	//时间控件
	$('#datetimepicker1').datetimepicker({
		lang:'ch',
		timepicker:false,
        onChangeDateTime :function(){
			load_data(
                g_sid,
				$("#datetimepicker1").val(),
				$("#datetimepicker2").val(),
                $("#id_lesson_status").val(),
                $("#id_stu_status").val(),
                $("#id_lesson_time").val()
			);
		},
		format:'Y-m-d'
	});
		
	$('#datetimepicker2').datetimepicker({
		lang:'ch',
		timepicker:false,
        onChangeDateTime :function(){
			load_data(
                g_sid,
				$("#datetimepicker1").val(),
				$("#datetimepicker2").val(),
                $("#id_lesson_status").val(),
                $("#id_stu_status").val(),
                $("#id_lesson_time").val()
			);
		},
		format:'Y-m-d'
	});

    function load_data( sid, start_time,end_time , lesson_status, stu_status, lesson_time){
		var url="/stu_manage/lesson_record?sid="+sid+"&start_time="+start_time+"&end_time="+end_time+"&lesson_status="+lesson_status+"&stu_status="+stu_status+"&lesson_time="+lesson_time+"&nick="+g_nick;
		window.location.href=url;
	}

    $(".lesson_change").on("change",function(){
        var start_time = $("#datetimepicker1").val();
        var end_time = $("#datetimepicker2").val();
        var stu_status = $("#id_stu_status").val();
        var lesson_time = $("#id_lesson_time").val();
        var lesson_status = $("#id_lesson_status").val();
        load_data(g_sid,start_time, end_time, lesson_status, stu_status, lesson_time);
    });
    $(".opt-close-complain").on("click",function(){
        var lessonid = $(this).parent().data("lessonid");
        
        
        BootstrapDialog.show({
	        title: "取消投诉",
	        message : "你要取消投诉吗?",
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    $.ajax({
                        url: '/stu_manage/add_complain_note',
                        type: 'POST',
                        data: {
				            'lessonid':lessonid,
				            'is_complained':0,
				            'complain_note':"取消投诉"
			            },
                        dataType: 'json',
                        success: function(data) {
					        window.location.reload();
                        }
                    });
                    dialog.close();

                    
		        }
	        }]
        });

        
    });

    $(".opt-complain").on("click",function(){
        var lessonid = $(this).parent().data("lessonid");
        var tr_node= $(this).closest("tr");
        var lesson_date = tr_node.find('.lesson_date').html();
        var lesson_time = tr_node.find('.lesson_time').html();
        var lesson_num = tr_node.find('.lesson_num').html();
        var html_node=dlg_need_html_by_id("id_dlg_complain");
        html_node.find("#id_alert_date").html(lesson_date);
        html_node.find("#id_alert_time").html(lesson_time);
        html_node.find("#id_alert_num").html(lesson_num);

        BootstrapDialog.show({
            title: '投诉信息',
            message :   html_node ,
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.ajax({
                        url: '/stu_manage/add_complain_note',
                        type: 'POST',
                        data: {
				            'lessonid':lessonid,
				            'is_complained':1,
				            'complain_note':html_node.find("#id_complain_note").val()
			            },
                        dataType: 'json',
                        success: function(data) {
					        window.location.reload();
                        }
                    });
                    dialog.close();
                }
            }]
        });
        
    });

});
