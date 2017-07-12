// JavaScript Document

$(function(){
	function timer(){
		$('.class_end').css({'display':'none','opacity':'1','position':'static'});
		$('.class_ing').css({'display':'none','opacity':'1','position':'static'});
	}
	setTimeout(timer,1000)
	//日历插件
	
	//课表日历生成
	var schedule_time = new Date() / 1000;
	schedule_event(schedule_time);
    $('#calendar').fullCalendar({
		header: {
			left: '',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},	
		lang: 'zh-cn',
		timezone: 'local',
		weekends: true,
		height: 600,
		firstDay: 0,
		defaultView: 'agendaWeek',
		events: [{}],
		firstHour:8,
		timeFormat:{agenda: 'H:mm'},
		axisFormat:'H:mm'

    });
	
	//老师空闲时间日历生成
	var free_time = new Date()/1000;
	var tempEvent = new Object();
	$('#layer').hide();
	$('#event').hide();
	var cal = new Object();
	$('#calendar02').fullCalendar({
		header: {
			left: '',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},	
		lang: 'zh-cn',
		timezone: 'local',
		weekends: true,
		height: 600,
		firstDay: 0,
		defaultView: 'agendaWeek',
		events: [{}],
		firstHour:8,
		timeFormat:{agenda: 'H:mm'},
		axisFormat:'H:mm',
		eventClick: function(calEvent) {
            cal = calEvent;
			var ww=calEvent._id;
			tempEvent.start = calEvent.start;
			tempEvent.end = calEvent.end;
            $('#event').show();  
			$('#layer').show();
    	},
		eventResize: function(event, delta, revertFunc) {
			alert(event.title + " end is now " + event.end.format());
			if (!confirm("is this okay?")) {
				revertFunc();
			}
		}
    });
	
	
	////课表日历按钮
	var schedule_time = new Date()/1000;
	//课表查看今天按钮
	$('#today').on('click', function(){
		$('#calendar').fullCalendar('removeEvents');//日历数据清空
		schedule_time = new Date()/1000;
		schedule_event(schedule_time);
		$('#calendar').fullCalendar('today');
	});
	
	//课表查看上一周期按钮
	$('#prev').on('click', function(){
		$('#calendar').fullCalendar('removeEvents');//日历数据清空
		schedule_time -= 604800;
		schedule_event(schedule_time);//日历数据加载
		$('#calendar').fullCalendar('prev');
		//schedule_event(schedule_time);
	});
	
	//课表查看下一周期按钮
	$('#next').on('click', function(){
		$('#calendar').fullCalendar('removeEvents');//日历数据清空
		schedule_time += 604800;
		schedule_event(schedule_time);//日历数据加载
		$('#calendar').fullCalendar('next');
	});
	////课表日历按钮over
	
	
	
	//空闲时间日历按钮
	var free_time = new Date()/1000;
	//空闲时间查看今天按钮
	$('#today02').on('click', function(){
		$('#calendar02').fullCalendar('removeEvents');//日历数据清空
		free_time = new Date();
		freeTime_event(free_time);//日历数据加载
		$('#calendar02').fullCalendar('today');
	})
	
	
	//空闲时间查看上一周期按钮
	$('#prev02').on('click', function(){
		$('#calendar02').fullCalendar('removeEvents');//日历数据清空
		free_time -= 604800;
		freeTime_event(free_time);//日历数据加载
		$('#calendar02').fullCalendar('prev');
	});
	
	//空闲时间查看下一周期按钮
	$('#next02').on('click', function(){
		$('#calendar02').fullCalendar('removeEvents');//日历数据清空
		free_time += 604800;
		freeTime_event(free_time);//日历数据加载
		$('#calendar02').fullCalendar('next');
	});
	//空闲时间日历按钮over
	
	
	////开始排课超链接页面跳转
	
	$('.class_start .btn .arrange_course').click(function(){
		page_ajax(1,0);
		$('.class_ing').show().siblings('.plan_lesson').hide();
	});//课表页跳转到可排课页
	
	$('.class_start .btn .arrange_tess').click(function(){
		//page_ajax(1,0);
		$('.class_me').show().siblings('.plan_lesson').hide();
	});//课表页跳转到自定义课列表
	
	$('.class_ing .btn a').click(function(){
		$('#calendar').fullCalendar('removeEvents');
		schedule_event(schedule_time);
		$('.class_start').show().siblings('.plan_lesson').hide();
	});//可排课页跳转回课表页
	
	$('.class_end .btn a').click(function(){
		page_ajax(1,0);
		$('.class_ing').show().siblings('.plan_lesson').hide();
	});//课表页跳转到可排课
	
	var value_arr=new Array();
	$('.can_plan a').live('click',function(){
		value_arr=[];
		
		$('#calendar02').fullCalendar('removeEvents');
		freeTime_event(free_time);
		
		$('.class_end').show().siblings('.plan_lesson').hide();//跳转页面
		
		var input_value=$(this).siblings('input').val();//可排课程按钮旁边input框中的值
		
		value_arr=input_value.split(",");
		value_arr.return;
		
	});//课表页跳转到空闲时间页
	////开始排课超链接页面跳转over
	console.log(value_arr)
	//弹窗提交按钮
	$('#submit').on('click', function(){
		$('.class_ing').show().siblings('.cont_box').hide();
		var knowledge01 = $('#knowledge01').val();
		var knowledge02 = $('#knowledge02').val();
		var v_start = tempEvent.start._i/1000;
		var v_end   = tempEvent.end._i/1000;
		//var v_title = $('#title').val();

		if (knowledge01) {
			var eventData = {
				//title: v_title,
				knowledge01:knowledge01,
				knowledge02:knowledge02,
				start: v_start,
				end: v_end,
			};
			$('#calendar').fullCalendar('renderEvent', eventData, true); 
			console.log(eventData)
		};
		
		//返回可排课程列表调取新数据
		$.ajax({
            url: 'http://adminapi.weiyi.com/lesson_manage/arrange_lesson',
            type: 'POST',
            data: {'courseid': value_arr[0], 'lesson_num': value_arr[1], 'lesson_type': value_arr[2],'lesson_start':v_start,'lesson_end':v_end,'first_point':knowledge01,'second_point':knowledge02,'teacherid':value_arr[3]},
            dataType: 'jsonp',
            success: function(data) {
                if (data['ret'] == 0) {
                    page_ajax(1,0);
                }

            }
        });//返回可排课程列表调取新数据over
		
		$('#calendar').fullCalendar('unselect');
		$('#layer').hide();
		$('#event').hide();
		$('#title').val('');
		$('#start').val('');
		$('#end').val('');
		$('#knowledge01').val('');
		$('#knowledge02').val('');
		
		
	});
	
	//弹窗取消按钮
    $('#cancel').on('click', function(){
		calEvent = null;
		var v_start = $('#start').val('');
		var v_end   = $('#end').val('');
		//var v_title = $('#title').val('');
		$('#layer').hide();
		$('#event').hide();
		//$('#title').val('');
		$('#start').val('');
		$('#end').val('');
		$('#knowledge01').val('');
		$('#knowledge02').val('');
		$('#calendar').fullCalendar('unselect');
	});
	
	
	page_ajax(1,0);	//初始化在第一页
	
	var inde=0;
	var page_num=inde+1;
	
	//单个页码点击
	$('.page_click').live('click', function(){
		inde=$(this).index()-1;
		page_num=inde+1;
		page_ajax(page_num,inde);
	});
	
	//点击下一页
	$('.next').live('click', function(){
		$('.pre').removeClass('current');
		if(page_num<$('.page_click').length){
			inde++;
			page_num=inde+1;
			page_ajax(page_num,inde);
		};
	}); 
	
	//点击上一页
	$('.pre').live('click', function(){
		$('.next').removeClass('current');
		if(page_num>1){
			inde--;
			page_num=inde+1;
			page_ajax(page_num,inde);
		};
	});
	
	
	
})
	
	
//日历调取课表数据
    function schedule_event(timestamp)
    {
        $.ajax({
            url: 'http://adminapi.weiyi.com/lesson_manage/get_schedule',
            type: 'POST',
            data: {'userid': 50048, 'timestamp': Math.round(timestamp), 'type': 1},
            dataType: 'jsonp',
            success: function(data) {
                if (data['ret'] == 0) {
                    var lesson_source = [];
                    if (data['lesson_list'].length != 0) {
                        for (var i = 0; i < data['lesson_list'].length; i++ ) {
                            var lesson_data = new Object();
                            lesson_data.title = data['lesson_list'][i]. lesson_num;
                            lesson_data.start = data['lesson_list'][i].lesson_start * 1000;
                            lesson_data.end   = data['lesson_list'][i].lesson_end * 1000;
                            lesson_data.color = '#17a6e8';

                            lesson_source.push(lesson_data);
                        }

                    }
                    console.log(lesson_source);
                    $('#calendar').fullCalendar( 'addEventSource', lesson_source);
                }

            }
        });

    };
	//课表数据over
	
	//日历调取空闲时间数据
    function freeTime_event(timestamp)
    {
        $.ajax({
            url: 'http://adminapi.weiyi.com/teacher_free/get_free_time',
            type: 'POST',
            data: {'teacherid': 50038, 'timestamp': timestamp, 'view_type': 1},
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
                    console.log(teach_source);
                    $('#calendar02').fullCalendar( 'addEventSource', teach_source);
                }

            }
        });

    }
	//空闲时间数据over
	
	//时间戳转化成时间
	function UnixToDate(unixTime, isFull, timeZone) {
		if (typeof (timeZone) == 'number')
		{
			unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
		}
		var time = new Date(unixTime*1000);
		var ymdhis = "";
		ymdhis += time.getUTCFullYear() + "-";
		ymdhis += (time.getUTCMonth()+1) + "-";
		ymdhis += time.getUTCDate();
		if (isFull === true)
		{
			ymdhis += " " + (time.getUTCHours() < 10 ? ("0"+time.getUTCHours()):time.getUTCHours() )+ ":";
			
			ymdhis += time.getUTCMinutes() < 10 ? ("0"+time.getUTCMinutes()):time.getUTCMinutes();
			
		}
		return ymdhis;
	};
	
	//可排课程
	//可排课程的分页函数start
	function page_ajax(page_num,inde){
		$.ajax({
			url: 'http://adminapi.weiyi.com/lesson_manage/get_lessons',
			type: 'POST',
			data: {'userid': 50048, 'page_num': page_num},
			dataType: 'jsonp',
			success: function(data) {
				if (data['ret'] == 0) {
					var stu_source = [];
					if (data['lesson_list'].length != 0) {
						var lesson_num=data['lesson_list'].length;
						for (var i = 0; i < data['lesson_list'].length; i++ ) {
							var c_data = new Object();
							c_data.s_name = data['lesson_list'][i].user_nick;
							c_data.t_name = data['lesson_list'][i].tea_nick;
							c_data.start  = data['lesson_list'][i].lesson_start;
							c_data.end    = data['lesson_list'][i].lesson_end;  
							c_data.status = data['lesson_list'][i].lesson_status;
							
							c_data.courseid    = data['lesson_list'][i].courseid;  
							c_data.lesson_num  = data['lesson_list'][i].lesson_num;
							c_data.lesson_type = data['lesson_list'][i].lesson_type;  
							c_data.teacherid   = data['lesson_list'][i].teacherid;
							
							stu_source.push(c_data);
						}
	
					};
					console.log(data['page_cnt']);
					var page_num=data['page_cnt'];
				}
				console.log(stu_source);
				console.log("work ....");
				
				//动态生成表格
				var table=$('.can_plan');
				table.children('tbody').empty();
				for(var a=0;a<lesson_num;a++){
					var tr=$("<tr></tr>");
					tr.appendTo(table);
					for(var b=0;b<6;b++){
						var td=$("<td></td>");
						td.appendTo(tr);
					}
				};
				//console.log(stu_source[0].start)
				//数据填进表格
				for(var c=0;c<lesson_num;c++){
					if(stu_source[c].start!=0){
						table.children('tbody').children('tr').eq(c).children('td').eq(2).html(UnixToDate(stu_source[c].start,true,8));
						table.children('tbody').children('tr').eq(c).children('td').eq(3).html(UnixToDate(stu_source[c].end,true,8));
					}
					table.children('tbody').children('tr').eq(c).children('td').eq(0).html(stu_source[c].s_name);
					table.children('tbody').children('tr').eq(c).children('td').eq(1).html(stu_source[c].t_name);
					table.children('tbody').children('tr').eq(c).children('td').eq(4).html(stu_source[c].status);
					table.children('tbody').children('tr').eq(c).children('td').eq(5).html("<a href='javascript:;'>开始排课</a><input type='hidden' value='"+stu_source[c].courseid+","+stu_source[c].lesson_num+","+stu_source[c].lesson_type+","+stu_source[c].teacherid+"'/>");
				};
				
				//分页
				$('.de_page').empty();
				var de_page=$('.de_page')
				$("<a href='javascript:;' class='pre'>上一页</a>").appendTo(de_page);
				for(var d=1;d<page_num+1;d++){
					var page_a=$("<a class='page_click' href='javascript:;'>"+d+"</a>");
					page_a.appendTo(de_page);
				};
				$("<a href='javascript:;' class='next'>下一页</a>").appendTo(de_page);
				
				//当前页码current
				$('.page_click').eq(inde).addClass('current').siblings('.page_click').removeClass('current');
				
				//上一页、下一页按钮不可点击样式
				if(inde==0){
					$('.pre').addClass('current');
				}else if(inde==$('.page_click').length-1){
					$('.next').addClass('current');
				}
			}
		});
		
	};	//可排课程的分页函数over


	








































