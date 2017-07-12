// SWITCH-TO:   ../../template/student/

var free_time = new Date().getTime()/1000;
$(function(){
	$('#cal_teacher').fullCalendar({
		header: {
			left: '',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},	
		lang: 'zh-cn',
		timezone: 'local',
		weekends: true,
		height: 600,
		firstDay: 1,
		defaultView: 'agendaWeek',
		events: [{}],
		firstHour:8,
		timeFormat:{agenda: 'H:mm'},
		axisFormat:'H:mm',
		eventClick: function(calEvent) {
            var cal = calEvent;
			var ww=calEvent._id;
			//tempEvent.start = calEvent.start;
			//tempEvent.end = calEvent.end;
    	},
		eventResize: function(event, delta, revertFunc) {

		}
    });
    freeTime_event($.query.get("teacherid"), free_time  );
	
	
	//课表查看今天按钮
	$('#today').on('click', function(){
		$('#cal_teacher').fullCalendar('removeEvents');//日历数据清空
		free_time = new Date().getTime()/1000;
		 freeTime_event(free_time);
		$('#cal_teacher').fullCalendar('today');
	});
	
	//课表查看上一周期按钮
	$('#prev').on('click', function(){
		$('#cal_teacher').fullCalendar('removeEvents');//日历数据清空
		free_time -= 604800;
		freeTime_event( $.query.get("teacherid"),free_time);//日历数据加载
		$('#cal_teacher').fullCalendar('prev');
	});
	
	//课表查看下一周期按钮
	$('#next').on('click', function(){
		$('#cal_teacher').fullCalendar('removeEvents');//日历数据清空
		free_time += 604800;
		freeTime_event( $.query.get("teacherid"),free_time);//日历数据加载
		$('#cal_teacher').fullCalendar('next');
	});
	////课表日历按钮over
	
	
	
	

    function cancel_lesson(){
        var lessonid = $(this).data('lessonid');
        var courseid = $(this).data('courseid');
        var cancel_reason = $("#id_cancel_reason").val();
        $('#id_submit_cancel').attr('disabled',"true");
        $.ajax({
            url: '/lesson_manage/cancel_lesson',
            type: 'POST',
            data: {
				'lessonid':  lessonid,
                'courseid':  courseid,
                'cancel_reason': cancel_reason
			},
            dataType: 'json',
            success: function(data) {
                $('.mesg_alert111').hide();
                $('#id_submit_cancel').removeAttr("disabled");  
            }
        });//返回可排课程列表调取新数据over
    }


    //课表页跳转到空闲时间页
	////开始排课超链接页面跳转over
	//弹窗提交按钮
	$('#submit').on('click', function(){
		$('.class_ing').show().siblings('.cont_box').hide();
		var lessonid= $(this ).data("lessonid");
		var knowledge01 = $('#knowledge01').val();
		var knowledge02 = $('#knowledge02').val();
		var v_start = tempEvent.start._i/1000;
		var v_end   = tempEvent.end._i/1000;
		//var v_title = $('#title').val();

		if (knowledge01) {
			var eventData = {
				//title: v_title,
				"knowledge01":knowledge01,
				"knowledge02":knowledge02,
				"start": v_start,
				"end": v_end
			};
			$('#cal_teacher').fullCalendar('renderEvent', eventData, true); 
			console.log(eventData);
		};
        if(g_arrange_flag == 1){
            alert('正在排课中');
        }else{
		    g_arrange_flag = 1;
		    //返回可排课程列表调取新数据
		    $.ajax({
                url: '/lesson_manage/arrange_lesson',
                type: 'POST',
                data: {
				    'sid': g_sid, 
				    'lessonid':  lessonid,
				    'lesson_start':v_start,
				    'lesson_end':v_end
				},
                dataType: 'json',
                success: function(data) {
                    if (data['ret'] != 0) {
		                g_arrange_flag = 0;
                        alert(data['info']);
                    }else{
		                g_arrange_flag = 0;
                    }
                }
            });//返回可排课程列表调取新数据over
	    }	
		$('#cal_teacher').fullCalendar('unselect');

		$('#title').val('');
		$('#start').val('');
		$('#end').val('');
		$('#knowledge01').val('');
		$('#knowledge02').val('');
	});
	
	
});


//日历调取空闲时间数据
function freeTime_event( teacherid, timestamp )
{
	timestamp=Math.floor(timestamp);
    $.ajax({
        url: '/teacher_free/get_free_time',
        type: 'POST',
        data: {'teacherid':  teacherid , 'timestamp': timestamp, 'view_type': 1},
        dataType: 'jsonp',
        success: function(data) {
            if (data['ret'] == 0) {
                var teach_source = [];
                if (data['free_time_list'].length != 0) {
                    for (var i = 0; i < data['free_time_list'].length; i++ ) {
                        var teach_data = new Object();
                        teach_data.title = data['free_time_list'][i].teacherid;
                        teach_data.start = data['free_time_list'][i].time_start * 1000;
                        teach_data.end   = data['free_time_list'][i].time_end * 1000;
                        teach_data.color = '#17a6e8';

                        teach_source.push(teach_data);
                    }

                }
                $('#cal_teacher').fullCalendar( 'addEventSource', teach_source);
            }

        }
    });
}


