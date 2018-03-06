/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-stu_schedule.d.ts" />
$(function(){
    // function load_data(){
    //     $.reload_self_page({
    //         teacherid        : $('#id_teacherid').val(),
    //         competition_flag : $('#id_competition_flag').val(),
    //         subject          : $('#id_subject').val(),
    //     });
    // }

	  // $('.opt-change').set_input_change_event(load_data);
    // $('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user($("#id_teacherid"), "teacher",get_lesson_list);
    // $('#id_subject').val(g_args.subject);
    Enum_map.append_option_list("competition_flag",$("#id_competition_flag"));
    // $('#id_competition_flag').val(g_args.competition_flag);

    $(".course_select_type").on("change",function(){
        get_lesson_list();
    });


    var loop_type_option = "<option value='-1'>不循环</option>"
        +"<option value='1'>每周</option>"
        +"<option value='2'>隔周</option>";



    //排课
    $("#set_lesson").on("click",function(){
        var teacherid           = $("#id_teacherid").val();
        //课表中点击选择的时间区域
        var select_time         = "预留位置";

        var id_teacherid        = $("<input>");
        var id_competition_flag = $("<select>");
        var id_subject          = $("<select>");
        var id_lesson_start     = $("<input style='width:50%' placeholder='开始时间'>");
        var id_lesson_end       = $("<input style='width:30%' placeholder='结束时间'>");
        var id_lesson_count     = $("<input style='width:20%' placeholder='课时'>");
        var id_loop_type        = $("<select>");

        Enum_map.append_option_list("competition_flag",id_competition_flag);
        Enum_map.append_option_list_by_not_id("subject",id_subject,false,[0,11]);
        id_loop_type.append(loop_type_option);

        var arr = [
            ["选择老师",id_teacherid],
            ["类型",id_competition_flag],
            ["科目",id_subject],
            ["上课时间",id_lesson_start,id_lesson_end,id_lesson_count],
            ["循环方式",id_loop_type],
        ];

        $.show_key_value_table("排课",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {


            }
        },function(){
            id_lesson_start.datetimepicker({
                lang       : 'ch',
                timepicker : true,
                format     : '周N Y-m-d H:i',
                step       : 30,
            });
            id_lesson_end.datetimepicker({
                lang       : 'ch',
                datepicker : false,
                timepicker : true,
                format     : 'H:i',
                step       : 30
            });

            id_lesson_start.on("change",function(){
                set_lesson_count();
            });
            id_lesson_end.on("change",function(){
                set_lesson_count();
            });

            var set_lesson_count = function(){
 	              var lesson_start = id_lesson_start.val();
                var lesson_end   = id_lesson_end.val();
                if(lesson_start!="" && lesson_end!=""){
                    $.do_ajax("/lesson_manage/get_lesson_count_for_stu_schedule",{
                        "lesson_start" : lesson_start,
                        "lesson_end"   : lesson_end,
                    },function(result){
                        if(result.ret==0){
                            id_lesson_count.val(result.data);
                        }else{
                            id_lesson_end.empty();
                            BootstrapDialog.alert(result.info);
                        }
                    });
                }
            }

            $.admin_select_user(id_teacherid, "teacher");
        });
    });



    /**
     * 获取课表信息
     */
    var get_lesson_list = function(){

    }

    // var html_node = $("#id_calendar");
    // html_node.fullCalendar({
		//     header: {
    //         left: 'prev,next,today',
		// 	      center: 'title',
		// 	      right: 'agendaWeek,month'
		//     },
    //     lang        : 'zh-cn',
		//     timezone    : 'local',
		//     weekends    : true,
		//     height      : 800,
		//     firstDay    : 1,
		//     defaultView : 'month',
		//     events      : [{}],
    //     minTime     : "8:00",
		//     firstHour   : 8,
		//     timeFormat  : {agenda: 'H:mm'},
		//     axisFormat  : 'H:mm',
		//     eventClick: function(calEvent) {
    // 	  },
		//     eventResize: function(event, delta, revertFunc) {
		//     },
    //     eventAfterRender:function(){
    //     }
    // });

    // function schedule_event(timestamp) {
    //     var opt_date = new Date(timestamp*1000);

    //     html_node.fullCalendar( 'gotoDate',opt_date);
    //     var month_flag = check_month()?1:0;
    //     $.do_ajax('/stu_manage/get_stu_schedule_lesson_list',{
    //         'timestamp' : timestamp,
    //         'type'      : month_flag,
    //         'teacherid' : $("#id_teacherid").val(),
    //         'subject'   : $("#id_subject").val(),
    //     },function(data){
    //         if (data['ret'] == 0) {
    //             var teach_source = [];

    //             $.each(data["lesson_list"],function( i,item)  {
    //                 var teach_data   = new Object();
    //                 teach_data["start"]= item.lesson_start * 1000;
    //                 teach_data["end"]= item.lesson_end * 1000;

    //                 teach_data["title"]= month_flag? item.month_title:item.week_title;
    //                 teach_data["color"]= '#FC4848';

    //                 teach_data["use_flag"]= true;
    //                 teach_data["lessonid"]= item.lessonid;
    //                 teach_source.push(teach_data);
    //             });

    //             html_node.fullCalendar( 'removeEvents' );
    //             html_node.fullCalendar( 'addEventSource', teach_source);
    //         }

    //     });
    // }

    // var schedule_time = new Date().getTime()/1000; //now

    // html_node.find(" .fc-prev-button").unbind();
    // html_node.find(" .fc-next-button").unbind();
    // html_node.find(" .fc-today-button").unbind();
    // var check_month=function()  {
    //     return $(".fc-month-button").hasClass("fc-state-active");
    // };

    // html_node.find(".fc-prev-button").on("click",function(){
		//     html_node.fullCalendar('removeEvents');//日历数据清空
    //     if (check_month()) {
    //         schedule_time = getPreMonth(schedule_time*1000);
    //     }else{
		//         schedule_time -= 604800;
    //     }
		//     schedule_event(schedule_time);//日历数据加载
    // });

    // html_node.find(" .fc-next-button").on("click",function(){
		// html_node.fullCalendar('removeEvents');//日历数据清空
    //     if (check_month()) {
    //         schedule_time = getNextMonth(schedule_time*1000);
    //     }else{
		//         schedule_time += 604800;
    //     }
		//     schedule_event(schedule_time);//日历数据加载
    // });

    // html_node.find(" .fc-today-button").on("click",function(){
		// html_node.fullCalendar('removeEvents');//日历数据清空
		// schedule_time = new Date().getTime()/1000;
		// schedule_event(schedule_time);
    // });
    // html_node.find(" .fc-month-button").on("click",function(){
		// html_node.fullCalendar('removeEvents');//日历数据清空
		// html_node.fullCalendar('month');
		// schedule_event(schedule_time);
    // });
    // html_node.find(" .fc-agendaWeek-button").on("click",function(){
		// html_node.fullCalendar('agendaWeek');
		// html_node.fullCalendar('removeEvents');//日历数据清空
    //     schedule_event(schedule_time);
    // });

    // schedule_event(schedule_time);//日历数据加载

    // Date.prototype.format = function(fmt){
    //     var o = {
    //         "M+" : this.getMonth()+1,                 //月份 
    //         "d+" : this.getDate(),                    //日 
    //         "h+" : this.getHours(),                   //小时 
    //         "m+" : this.getMinutes(),                 //分 
    //         "s+" : this.getSeconds(),                 //秒 
    //         "q+" : Math.floor((this.getMonth()+3)/3), //季度 
    //         "S"  : this.getMilliseconds()             //毫秒 
    //     };
    //     if(/(y+)/.test(fmt)) {
    //         fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    //     }
    //     for(var k in o) {
    //         if(new RegExp("("+ k +")").test(fmt)){
    //             fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
    //         }
    //     }
    //     return fmt;
    // }

    // //获取上个月时间
    // function getPreMonth(date) {
    //     var date = new Date(date).format("yyyy-MM-dd");
    //     var arr = date.split('-');
    //     var year = arr[0]; //获取当前日期的年份
    //     var month = arr[1]; //获取当前日期的月份
    //     var day = arr[2]; //获取当前日期的日
    //     var days = new Date(year, month, 0);
    //     days = days.getDate(); //获取当前日期中月的天数
    //     var year2 = year;
    //     var month2 = parseInt(month) - 1;
    //     if (month2 == 0) {
    //         year2 = parseInt(year2) - 1;
    //         month2 = 12;
    //     }
    //     var day2 = day;
    //     var days2 = new Date(year2, month2, 0);
    //     days2 = days2.getDate();
    //     if (day2 > days2) {
    //         day2 = days2;
    //     }
    //     if (month2 < 10) {
    //         month2 = '0' + month2;
    //     }
    //     var t2 = year2 + '-' + month2 + '-' + day2;
    //     var t3 = (new Date(t2)).getTime()/1000;
    //     return t3;
    // }

    // //获取下个月时间
    // function getNextMonth(date) {
    //     var date = new Date(date).format("yyyy-MM-dd");
    //     var arr = date.split('-');
    //     var year = arr[0]; //获取当前日期的年份
    //     var month = arr[1]; //获取当前日期的月份
    //     var day = arr[2]; //获取当前日期的日
    //     var days = new Date(year, month, 0);
    //     days = days.getDate(); //获取当前日期中的月的天数
    //     var year2 = year;
    //     var month2 = parseInt(month) + 1;
    //     if (month2 == 13) {
    //         year2 = parseInt(year2) + 1;
    //         month2 = 1;
    //     }
    //     var day2 = day;
    //     var days2 = new Date(year2, month2, 0);
    //     days2 = days2.getDate();
    //     if (day2 > days2) {
    //         day2 = days2;
    //     }
    //     if (month2 < 10) {
    //         month2 = '0' + month2;
    //     }

    //     var t2 = year2 + '-' + month2 + '-' + day2;
    //     var t3 = (new Date(t2)).getTime()/1000;
    //     return t3;
    // }







});
