(function($, window, document,undefined) {
    //定义构造函数
    var Cselect_teacher_free_time= function(ele, opt) {
        this.$element = ele;
        this.defaults = {
            'teacherid':0,
            'month_start':0,
            "onSelect":null
        };
        var me=this;

        this.options     = $.extend({}, this.defaults, opt);

        me.$element.on("click",function( ){
            if (!(me.options.teacherid >0)) {
                alert ("请先选择老师");
                return ;
            }
            
            var html_node=$("<div/>");
            var dlg=BootstrapDialog.show({
                title: "选择时间",
                message :  html_node, 
                
                closable: true,
                onshown: function(  dialog )  {
                    html_node.fullCalendar({
		                header: {
                            left: 'prev,next,today',
			                center: 'title',
			                right: 'agendaWeek,month'
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
                            if (me.options.onSelect) {
                                if(me.options.onSelect( calEvent ,dlg )) {
                                    var v_start = calEvent.start/1000;
			                        var v_end   = calEvent.end/1000;
                                    var date_str=DCalEventateFormat(v_start, "yyyy-MM-dd hh:mm")+"~"+DateFormat(v_end,"hh:mm");
                                    me.$element.val(date_str);

                                    dlg.close();
                                }
                            }

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
                        //alert(DateFormat(timestamp, "yyyy-MM-dd" ));  
                        do_ajax(
                            '/tea_manage_new/get_free_time_new',
                            {'teacherid':  me.options.teacherid,
                             'timestamp':  timestamp,
                             'month_start': me.options.month_start,
                             'type'     :  check_month()?1:0
                            },
                            function(data){
                                console.log(data);
                                if (data['ret'] == 0) {
                                    var teach_source = [];
                                    $.each(data["free_time_list"],function( i,item)  {
                                        var teach_data   = new Object();
                                        teach_data.start = item.free_start * 1000;
                                        teach_data.end   = item.free_end * 1000;
                                        if (!item.lesson_list) {
                                            teach_data.use_flag = false;
                                            teach_data.color = '#17a6e8';
                                            teach_source.push(teach_data);
                                        }
                                    });

                                    $.each(data["lesson_list"],function( i,item)  {
                                        var teach_data   = new Object();
                                        teach_data.start = item.lesson_start * 1000;
                                        teach_data.end   = item.lesson_end * 1000;
                                        if(check_month()==1){
                                            teach_data.title = item.title;
                                        }else{
                                            teach_data.title = item.title+" "+"\n助教-"+item.nick;
                                        }
                                        teach_data.color = '#FC4848';

                                        teach_data.use_flag = true;
                                        teach_data.lessonid = item.lessonid;
                                        teach_data.ass_nick = item.nick;
                                        teach_source.push(teach_data);
                                    });

                                    console.log(teach_source);
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
                            schedule_time = getPreMonth(schedule_time*1000);

                        //     console.log(schedule_time);
		                    // schedule_time -= 86400*30;
                        }else{
		                    schedule_time -= 604800;
                        }

		                schedule_event(schedule_time);//日历数据加载

                    });

                    html_node.find(" .fc-next-button").on("click",function(){
		                html_node.fullCalendar('removeEvents');//日历数据清空
                        if (check_month()) {
                            schedule_time = getNextMonth(schedule_time*1000);
                            // console.log(tt);
                            // console.log(schedule_time);
		                        // schedule_time += 86400*30;
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

                }
            });
            dlg.getModalDialog().css("width","1000px");
        });
        //
    };

    //定义方法
    Cselect_teacher_free_time.prototype = {
        set_teacherid:function(teacherid){
            this.options.teacherid=teacherid;
        }
    };

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

    //在插件中使用对象
    $.fn.admin_select_teacher_free_time_new = function(options) {
        //创建的实体
        var select_teacher_free_time  = new Cselect_teacher_free_time(this, options);
        //调用其方法
        return  select_teacher_free_time;
    };
})(jQuery, window, document);
