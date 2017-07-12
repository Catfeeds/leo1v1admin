/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-regular_course_seller.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            teacherid:	$('#id_teacherid').val(),
        });
    }

    var g_sid = -1;



//课表日历生成
var schedule_time = new Date().getTime()/1000; //now
var free_time = new Date().getTime()/1000;
function do_bind_event(){
    $("#calendar .fc-prev-button").unbind();
    $("#calendar .fc-next-button").unbind();
    $("#calendar .fc-today-button").unbind();
    $("#calendar .fc-prev-button").on("click",function(){
        $('#calendar').fullCalendar('removeEvents');//日历数据清空
        schedule_time -= 604800;
        schedule_event(schedule_time);//日历数据加载
        $('#calendar').fullCalendar('prev');
    });

    $("#calendar .fc-next-button").on("click",function(){
        $('#calendar').fullCalendar('removeEvents');//日历数据清空
        schedule_time += 604800;
        schedule_event(schedule_time);//日历数据加载
        $('#calendar').fullCalendar('next');

    });

    $("#calendar .fc-today-button").on("click",function(){
        $('#calendar').fullCalendar('removeEvents');//日历数据清空
        schedule_time = new Date().getTime()/1000;
        schedule_event(schedule_time);
        $('#calendar').fullCalendar('today');
    });

}


    //日历调取课表数据
    function schedule_event(timestamp)
    {
        timestamp=Math.floor(timestamp);
        $.ajax({
            url: '/human_resource/get_common_config_new_seller',
            type: 'POST',
            data: { 'timestamp': Math.round(timestamp), 'type': 1},
            dataType: 'jsonp',
            success: function(data) {
                if (data['ret'] == 0) {
                    // console.log(data );
                    var lesson_source = [];
                    if (data['lesson_list'].length != 0) {
                        for (var i = 0; i < data['lesson_list'].length; i++ ) {
                            var lesson_data = new Object();
                            var item=data['lesson_list'][i];
                            lesson_data.title = item.title ;
                            lesson_data.start = item.lesson_start * 1000;
                            lesson_data.end   = item.lesson_end * 1000;
                            lesson_data.teacher = item.teacher;
                            lesson_data.nick = item.nick;
                            lesson_data.lesson_type = item.lesson_type;
                            lesson_data.lesson_count = item.lesson_count;
                            lesson_data.lesson_start_str = item.lesson_start_str;
                            lesson_data.lesson_end_str = item.lesson_end_str;
                            var lesson_type= data['lesson_list'][i].lesson_type;

                            console.log( lesson_data.title);

                            if ( lesson_type==1){ //赠送
                                //lesson_data.color = '#A11E08';
                                lesson_data.color = '#17a6e8';
                            } else if ( lesson_type=='试听'){
                                lesson_data.color = '#17a6e8';
                           }else{
                                  lesson_data.color = '#a94442';
                               
                           }

                            lesson_source.push(lesson_data);
                        }


                    }
                    $('#calendar').fullCalendar( 'removeEvents' );
                    $('#calendar').fullCalendar( 'addEventSource', lesson_source);

                    console.log(lesson_source);
                }

            }
        });

    };

    //课表数据over

    //日历调取空闲时间数据


    //可排课程
    //可排课程的分页函数start




    $(function(){
        //日历插件
        schedule_event(schedule_time);
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next,today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            lang: 'zh-cn',
            timezone: 'local',
            weekends: true,
            height: 800,
            firstDay: 1,
            defaultView: 'agendaWeek',
            events: [{}],
            minTime:"8:00",
            timeFormat:{agenda: 'H:mm'},
            axisFormat:'H:mm',

            eventClick: function(calEvent) {

                var arr=  [
                    ["时间", calEvent.lesson_start_str+' - '+calEvent.lesson_end_str] ,
                    ["老师", calEvent.teacher ] ,
                    ["学生", calEvent.nick ] ,
                    ["课时", calEvent.lesson_count ] ,
                    ["课程类型", calEvent.lesson_type ] ,
                ];

                $.show_key_value_table("详细",arr);
            }
        });

        $("#id_arrange_course").on("click",function(){
            window.location.href= "/stu_manage/lesson_plan_edit/?sid="+ g_sid;
            return false;
            /**
               $(".div-only-one").hide();
               $(".class_ing" ).show();
            */
        });
        $("#id_return_lesson_list").on("click",function(){
            $(".div-only-one").hide();
            $(".class_ing" ).show();
        });

        $("#id_return_cur_info").on("click",function(){
            schedule_event(schedule_time);
            $(".div-only-one").hide();
            $(".div-cur-info ").show();
        });

        // page_ajax(1,0);	//初始化在第一页
        do_bind_event();

    });





});
