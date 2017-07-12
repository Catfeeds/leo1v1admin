/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-winter_teacher_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            teacherid:	$('#id_teacherid').val()
        });
    }
    $('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user($("#id_teacherid"),"teacher",load_data);
    var html_node1=$("#id_calendar1");
    var html_node2=$("#id_calendar2");
    html_node1.fullCalendar({
		header: {
            left: 'prev,next,today',
			center: 'title',
			right: 'agendaWeek,month'
		},	
		lang: 'zh-cn',
		timezone: 'local',
		weekends: true,
		height: 500,
		firstDay: 1,
		defaultView: 'month',
		events: [{}],
        minTime:"8:00",
		firstHour:8,
		timeFormat:{agenda: 'H:mm'},
		axisFormat:'H:mm',
		eventClick: function(calEvent) {

    	},
		eventResize: function(event, delta, revertFunc) {
		}
        ,eventAfterRender:function(){

        }
    });
    html_node2.fullCalendar({
		header: {
            left: 'prev,next,today',
			center: 'title',
			right: 'agendaWeek,month'
		},	
		lang: 'zh-cn',
		timezone: 'local',
		weekends: true,
		height: 500,
		firstDay: 1,
		defaultView: 'month',
		events: [{}],
        minTime:"8:00",
		firstHour:8,
		timeFormat:{agenda: 'H:mm'},
		axisFormat:'H:mm',
		eventClick: function(calEvent) {

    	},
		eventResize: function(event, delta, revertFunc) {
		}
        ,eventAfterRender:function(){

        }
    });


    function schedule_event(timestamp) {

        var opt_date=new Date(timestamp*1000);

        //alert( ""+ opt_date.getFullYear() +":"+ opt_date.getMonth());
        html_node1.fullCalendar( 'gotoDate',  
                                opt_date  ) ;  
        var month_flag=check_month()?1:0;
        //alert(DateFormat(timestamp, "yyyy-MM-dd" ));  
        $.do_ajax(
            '/teacher_info/get_lesson_time_js',
            {
                'timestamp':  timestamp,
                'type'     : month_flag,
                'teacherid':g_args.teacherid
            },
            function(data){
                if (data['ret'] == 0) {
                    var teach_source = [];

                    $.each(data["lesson_list"],function( i,item)  {
                        var teach_data   = new Object();
                        teach_data["start"]= item.lesson_start * 1000;
                        teach_data["end"]= item.lesson_end * 1000;

                        teach_data["title"]= month_flag? item.month_title:item.week_title;                       
                        teach_data["color"]= '#FC4848';

                        teach_data["use_flag"]= true;
                        teach_data["lessonid"]= item.lessonid;
                        teach_source.push(teach_data);
                    });

                   
                    html_node1.fullCalendar( 'removeEvents' );
                    html_node1.fullCalendar( 'addEventSource', teach_source);
                }

            });
    }

    var schedule_time = Date.parse(new Date("2017-01-01 10:10:10"))/1000; //now

    html_node1.find(" .fc-prev-button").unbind();
    html_node1.find(" .fc-next-button").unbind();
    html_node1.find(" .fc-today-button").unbind();
    var check_month=function()  {
        return $(".fc-month-button").hasClass("fc-state-active");
    };

    html_node1.find(" .fc-prev-button").on("click",function(){

		html_node1.fullCalendar('removeEvents');//日历数据清空
        if (check_month()) {
		    schedule_time -= 86400*30;
        }else{
		    schedule_time -= 604800;
        }

		schedule_event(schedule_time);//日历数据加载

    });

    html_node1.find(" .fc-next-button").on("click",function(){
		html_node1.fullCalendar('removeEvents');//日历数据清空
        if (check_month()) {
		    schedule_time += 86400*30;
        }else{
		    schedule_time += 604800;
        }

		schedule_event(schedule_time);//日历数据加载

    });

    html_node1.find(" .fc-today-button").on("click",function(){
		html_node1.fullCalendar('removeEvents');//日历数据清空
		schedule_time = Date.parse(new Date("2017-01-01 10:10:10"))/1000;
		schedule_event(schedule_time);
    });
    html_node1.find(" .fc-month-button").on("click",function(){
		html_node1.fullCalendar('removeEvents');//日历数据清空
		html_node1.fullCalendar('month');
		schedule_event(schedule_time);
    });
    html_node1.find(" .fc-agendaWeek-button").on("click",function(){
		html_node1.fullCalendar('agendaWeek');
		html_node1.fullCalendar('removeEvents');//日历数据清空
		schedule_event(schedule_time);
    });

	schedule_event(schedule_time);//日历数据加载
    function schedule_event2(timestamp) {

        var opt_date=new Date(timestamp*1000);

        //alert( ""+ opt_date.getFullYear() +":"+ opt_date.getMonth());
        html_node2.fullCalendar( 'gotoDate',  
                                opt_date  ) ;  
        var month_flag=check_month()?1:0;
        //alert(DateFormat(timestamp, "yyyy-MM-dd" ));  
        $.do_ajax(
            '/teacher_info/get_lesson_time_js',
            {
                'timestamp':  timestamp,
                'type'     : month_flag,
                'teacherid':g_args.teacherid
            },
            function(data){
                if (data['ret'] == 0) {
                    var teach_source = [];

                    $.each(data["lesson_list"],function( i,item)  {
                        var teach_data   = new Object();
                        teach_data["start"]= item.lesson_start * 1000;
                        teach_data["end"]= item.lesson_end * 1000;

                        teach_data["title"]= month_flag? item.month_title:item.week_title;
                        teach_data["color"]= '#FC4848';

                        teach_data["use_flag"]= true;
                        teach_data["lessonid"]= item.lessonid;
                        teach_source.push(teach_data);
                    });

                    html_node2.fullCalendar( 'removeEvents' );
                    html_node2.fullCalendar( 'addEventSource', teach_source);
                }

            });
    }

    var schedule_time2 = Date.parse(new Date("2017-02-01 10:10:10"))/1000; //now

    html_node2.find(" .fc-prev-button").unbind();
    html_node2.find(" .fc-next-button").unbind();
    html_node2.find(" .fc-today-button").unbind();
    var check_month=function()  {
        return $(".fc-month-button").hasClass("fc-state-active");
    };

    html_node2.find(" .fc-prev-button").on("click",function(){

		html_node2.fullCalendar('removeEvents');//日历数据清空
        if (check_month()) {
		    schedule_time2 -= 86400*30;
        }else{
		    schedule_time2 -= 604800;
        }

		schedule_event2(schedule_time2);//日历数据加载

    });

    html_node2.find(" .fc-next-button").on("click",function(){
		html_node2.fullCalendar('removeEvents');//日历数据清空
        if (check_month()) {
		    schedule_time2 += 86400*30;
        }else{
		    schedule_time2 += 604800;
        }

		schedule_event2(schedule_time2);//日历数据加载

    });

    html_node2.find(" .fc-today-button").on("click",function(){
		html_node2.fullCalendar('removeEvents');//日历数据清空
		schedule_time2 = Date.parse(new Date("2017-02-01 10:10:10"))/1000;
		schedule_event2(schedule_time2);
    });
    html_node2.find(" .fc-month-button").on("click",function(){
		html_node2.fullCalendar('removeEvents');//日历数据清空
		html_node2.fullCalendar('month');
		schedule_event2(schedule_time2);
    });
    html_node2.find(" .fc-agendaWeek-button").on("click",function(){
		html_node2.fullCalendar('agendaWeek');
		html_node2.fullCalendar('removeEvents');//日历数据清空
		schedule_event2(schedule_time2);
    });

	schedule_event2(schedule_time2);//日历数据加载





	$('.opt-change').set_input_change_event(load_data);
});







