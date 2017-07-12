(function($, window, document,undefined) {
    //定义构造函数
    var Cselect_teacher_free_time= function(ele, opt) {
        this.$element = ele;
        this.defaults = {
            'teacherid':0,
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

                    function schedule_event(timestamp,type) {
                        do_ajax(
                            '/teacher_free/get_free_time_ex',
                            {'teacherid':  me.options.teacherid,
                             'timestamp':  timestamp,
                             'type'     :  type 
                            },
                            function(data){
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
                                        teach_data.title = item.title;
                                        teach_data.color = '#FC4848';

                                        teach_data.use_flag = true;
                                        teach_data.lessonid = item.lessonid;
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
		                schedule_time -= 604800;
                        if (check_month()) {
		                    schedule_time += 604800*30;
                        }else{
		                    schedule_time += 604800;
                        }

		                schedule_event(schedule_time);//日历数据加载
		                html_node.fullCalendar('prev');

                    });

                    html_node.find(" .fc-next-button").on("click",function(){
		                html_node.fullCalendar('removeEvents');//日历数据清空
                        if (check_month()) {
		                    schedule_time += 604800*30;
                        }else{
		                    schedule_time += 604800;
                        }

		                schedule_event(schedule_time);//日历数据加载
		                html_node.fullCalendar('next');

                    });

                    html_node.find(" .fc-today-button").on("click",function(){
		                html_node.fullCalendar('removeEvents');//日历数据清空
		                schedule_time = new Date().getTime()/1000;
		                schedule_event(schedule_time);
		                html_node.fullCalendar('today');
                    });
                    html_node.find(" .fc-month-button").on("click",function(){
                        alert("xx");
		                html_node.fullCalendar('removeEvents');//日历数据清空
		                schedule_time = new Date().getTime()/1000;
		                schedule_event(schedule_time);
		                html_node.fullCalendar('month');
                    });
                    html_node.find(" .fc-agendaWeek-button").on("click",function(){
		                html_node.fullCalendar('removeEvents');//日历数据清空
		                schedule_time = new Date().getTime()/1000;
		                schedule_event(schedule_time);
		                html_node.fullCalendar('agendaWeek');
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

    //在插件中使用对象
    $.fn.admin_select_teacher_free_time = function(options) {
        //创建的实体
        var select_teacher_free_time  = new Cselect_teacher_free_time(this, options);
        //调用其方法
        return  select_teacher_free_time;
    };
})(jQuery, window, document);
