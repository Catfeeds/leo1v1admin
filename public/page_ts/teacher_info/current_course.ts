/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-current_course.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
    var html_node=$("#id_calendar");
    html_node.fullCalendar({
		header: {
            left: 'prev,next,today',
			center: 'title',
			right: 'agendaWeek,month'
		},	
		lang: 'zh-cn',
		timezone: 'local',
		weekends: true,
		height: 800,
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
        html_node.fullCalendar( 'gotoDate',  
                                opt_date  ) ;  
        var month_flag=check_month()?1:0;
        //alert(DateFormat(timestamp, "yyyy-MM-dd" ));  
        $.do_ajax('/teacher_info/get_lesson_time_js',{
            'timestamp': timestamp,
            'type'     : month_flag 
        },function(data){
            if (data['ret'] == 0) {
                var teach_source = [];

                $.each(data["lesson_list"],function( i,item)  {
                    var teach_data = new Object();
                    teach_data["start"]= item.lesson_start * 1000;
                    teach_data["end"]= item.lesson_end * 1000;

                    teach_data["title"]= month_flag? item.month_title:item.week_title;
                    teach_data["color"]= '#FC4848';

                    teach_data["use_flag"]= true;
                    teach_data["lessonid"]= item.lessonid;
                    teach_source.push(teach_data);
                });

                html_node.fullCalendar( 'removeEvents' );
                html_node.fullCalendar( 'addEventSource', teach_source);
            }
        });
    }

    var schedule_time = new Date().getTime()/1000; //now

    html_node.find(" .fc-prev-button").unbind();
    html_node.find(" .fc-next-button").unbind();
    html_node.find(" .fc-today-button").unbind();
    var check_month=function()  {
        return $(".fc-month-button").hasClass("fc-state-active");
    };

    html_node.find(" .fc-prev-button").on("click",function(){

		html_node.fullCalendar('removeEvents');//日历数据清空
        if (check_month()) {
		    //schedule_time -= 86400*30;
            schedule_time = getPreMonth(schedule_time*1000);
        }else{
		    schedule_time -= 604800;
        }

		schedule_event(schedule_time);//日历数据加载

    });

    html_node.find(" .fc-next-button").on("click",function(){
		html_node.fullCalendar('removeEvents');//日历数据清空
        if (check_month()) {
		    //schedule_time += 86400*30;
            schedule_time = getNextMonth(schedule_time*1000);
        }else{
		    schedule_time += 604800;
        }

		schedule_event(schedule_time);//日历数据加载

    });

    html_node.find(" .fc-today-button").on("click",function(){
		html_node.fullCalendar('removeEvents');//日历数据清空
		schedule_time = new Date().getTime()/1000;
		schedule_event(schedule_time);
    });
    html_node.find(" .fc-month-button").on("click",function(){
		html_node.fullCalendar('removeEvents');//日历数据清空
		html_node.fullCalendar('month');
		schedule_event(schedule_time);
    });
    html_node.find(" .fc-agendaWeek-button").on("click",function(){
		html_node.fullCalendar('agendaWeek');
		html_node.fullCalendar('removeEvents');//日历数据清空
		schedule_event(schedule_time);
    });

	schedule_event(schedule_time);//日历数据加载


     Date.prototype.format = function(fmt) { 
        var o = { 
            "M+" : this.getMonth()+1,                 //月份 
            "d+" : this.getDate(),                    //日 
            "h+" : this.getHours(),                   //小时 
            "m+" : this.getMinutes(),                 //分 
            "s+" : this.getSeconds(),                 //秒 
            "q+" : Math.floor((this.getMonth()+3)/3), //季度 
            "S"  : this.getMilliseconds()             //毫秒 
        }; 
        if(/(y+)/.test(fmt)) {
            fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length)); 
        }
        for(var k in o) {
            if(new RegExp("("+ k +")").test(fmt)){
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
            }
        }
        return fmt; 
    }    

    //获取上个月时间
    function getPreMonth(date) {  
        var date = new Date(date).format("yyyy-MM-dd");
        var arr = date.split('-');  
        var year = arr[0]; //获取当前日期的年份  
        var month = arr[1]; //获取当前日期的月份  
        var day = arr[2]; //获取当前日期的日  
        var days = new Date(year, month, 0);  
        days = days.getDate(); //获取当前日期中月的天数  
        var year2 = year;  
        var month2 = parseInt(month) - 1;  
        if (month2 == 0) {  
            year2 = parseInt(year2) - 1;  
            month2 = 12;  
        }  
        var day2 = day;  
        var days2 = new Date(year2, month2, 0);  
        days2 = days2.getDate();  
        if (day2 > days2) {  
            day2 = days2;  
        }  
        if (month2 < 10) {  
            month2 = '0' + month2;  
        }  
        var t2 = year2 + '-' + month2 + '-' + day2;  
        var t3 = (new Date(t2)).getTime()/1000;
        return t3;  
    }  

    //获取下个月时间
    function getNextMonth(date) {
        var date = new Date(date).format("yyyy-MM-dd");
        var arr = date.split('-');
        var year = arr[0]; //获取当前日期的年份
        var month = arr[1]; //获取当前日期的月份
        var day = arr[2]; //获取当前日期的日
        var days = new Date(year, month, 0);
        days = days.getDate(); //获取当前日期中的月的天数
        var year2 = year;
        var month2 = parseInt(month) + 1;
        if (month2 == 13) {
            year2 = parseInt(year2) + 1;
            month2 = 1;
        }
        var day2 = day;
        var days2 = new Date(year2, month2, 0);
        days2 = days2.getDate();
        if (day2 > days2) {
            day2 = days2;
        }
        if (month2 < 10) {
            month2 = '0' + month2;
        }
        
        var t2 = year2 + '-' + month2 + '-' + day2;
        var t3 = (new Date(t2)).getTime()/1000;
        return t3;
    }


	$('.opt-change').set_input_change_event(load_data);
});


