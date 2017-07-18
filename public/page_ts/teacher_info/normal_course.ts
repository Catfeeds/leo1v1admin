/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-normal_course.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
        });
    }

    function opt_item(opt_type, old_userid,old_week,old_start_time,old_end_time,old_key) {
       
        var id_week       = $("<select/>");
        var id_start_time = $("<input/>");
        var id_end_time   = $("<input/>");
        var id_userid     = $("<input/>");
        Enum_map.append_option_list("week", id_week,true, [1,2,3,4,5,6,7] );
        if (opt_type=="update") {
            id_week.val(old_week);
            id_start_time.val(old_start_time);
            id_end_time.val(old_end_time);
            id_userid.val(old_userid);
        }
        
        var myDate = new Date();
        //时间插件
	    id_start_time.datetimepicker({
		    datepicker:false,
		    timepicker:true,
		    format:'H:i',
		    step:30,
	        onChangeDateTime :function(){
                var end_time= parseInt(
                    $.strtotime(myDate.getFullYear()+'-'+myDate.getMonth()+'-'+myDate.getDay()+' '+id_start_time.val()+':00')) + 7200;
                id_end_time.val(  $.DateFormat(end_time, "hh:mm"));
            }
            
	    });
        id_end_time.datetimepicker({
            datepicker:false,
            timepicker:true,
            format:'H:i',
            step:30
        });
        
        
        var arr                = [
            [ "星期",  id_week ] ,
            [ "开始时间",  id_start_time ] ,
            [ "结束时间",   id_end_time  ] ,
            [ "userid",   id_userid] ,
        ];
        $.show_key_value_table("操作常规课表", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax( '/teacher_info/otp_common_config',
                         {
                             'opt_type'   : opt_type,
                             'start_time' : ""+id_week.val()+"-"+id_start_time.val(),
                             'end_time'   : id_end_time.val(),
                             "old_key" : old_key,
                             'userid'     : id_userid.val()
                         },function(data){
                             if (data.ret!=0) {
                                 alert(data.info);
                             }else{
                                 window.location.reload();
                             }
                         }
                       );

			    dialog.close();

            }
        },function(){
            $.admin_select_user(id_userid, "student" );
        });
    }

    
    $('#id_calendar').fullCalendar({
		header: {
            left: null, 
			center: null,
			right:null
		},	
		lang: 'zh-cn',
		timezone: 'local',
		weekends: true,
		height: 780,
		firstDay: 1,
        minTime:"8:00",

		defaultView: 'agendaWeek',

		events: [{}],
		timeFormat:{agenda: 'H:mm'},
		axisFormat:'H:mm',
        eventClick: function(calEvent) {
            var date_v=new Date(calEvent.start);
            // start_itme 1-12:10 
            var week=date_v.getDay();
            if (week==0) {
                week=7;
            }
            var start_time= calEvent.start_time;

            var id_btn_update=$("<button class=\"btn  btn-primary \">修改</button>");
            var id_btn_del=$("<button class=\"btn   btn-warning \">删除</button>");
            
            var arr=[
                ["操作",  id_btn_update ],
                ["操作",  id_btn_del ]
            ];
            id_btn_del.on("click",function(){
                BootstrapDialog.confirm("要删除？！",function(val){
                    if(val) {
                        $.do_ajax("/teacher_info/otp_common_config",{
                            "opt_type"  : "del" ,
                            "old_key"  : start_time 
                        } );
                    }

                } );
            });
            id_btn_update.on("click",function(){
                opt_item("update",
                         calEvent.userid,
                         week,
                         $.DateFormat (  calEvent.start/1000,  "hh:mm" ),
                         $.DateFormat (  calEvent.end/1000,  "hh:mm" ),
                         start_time
                        );
            });


            $.show_key_value_table("操作", arr );
            //-------------------------


        }

    });

    $.do_ajax(
        '/teacher_info/get_common_config','',
        function(data) {
            //alert(JSON.stringify(data));
            $.each(data.common_lesson_config,function(i,item){
                var common_lesson=[];
                var lesson_config={};
                if(item.teacher == ''){
                    item.teacher = 'xxx';
                }
                lesson_config["title"]=  '学生:'+item.nick+'\n'+'老师:'+item.teacher;
                /*
                  lesson_config.start = (1464235345+3600)* 1000;
                  lesson_config.end   = (1464235345+7600)* 1000;
                */
                lesson_config["start"]= item.start_time_ex;
                lesson_config["end"]= item.end_time_ex;
	            lesson_config["color"]= '#17a6e8';
	            lesson_config["userid"]= item.userid; 
                lesson_config["start_time"]= item.start_time;

                common_lesson.push(lesson_config);
                $('#id_calendar').fullCalendar( 'addEventSource', common_lesson);
            });

        
        });



	$('.opt-change').set_input_change_event(load_data);

    $(".fc-day-header").each(function(){
        $(this).text(  $(this).text().split(" ")[0]);
    });
    $(".fc-widget-content  .fc-row " ).hide();
    $(".fc-widget-content  .fc-widget-header" ).hide();
    

});


