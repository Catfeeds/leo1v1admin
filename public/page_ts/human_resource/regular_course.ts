/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-regular_course.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            teacherid :	$('#id_teacherid').val(),
        });
    }
  $('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user($("#id_teacherid"),"teacher",load_data);
    function opt_item(
        opt_type, old_userid,old_week,old_start_time,old_end_time,old_key,old_count,old_teacherid,old_competition_flag
    ){
        var teacherid = g_args.teacherid ;
        if(opt_type == "add"){
            if(teacherid == -1){
                alert("请先选择老师再添加");
                return;
            }
        }else if (opt_type == "update"){
            teacherid = old_teacherid;
        }

        var id_week       = $("<select/>");
        var id_start_time = $("<input/>");
        var id_end_time   = $("<input/>");
        var id_userid     = $("<input/>");
        var id_count     = $("<input/>");
        var id_competition_flag  = $("<select/>");
        Enum_map.append_option_list("week", id_week,true, [1,2,3,4,5,6,7] );
        Enum_map.append_option_list("competition_flag", id_competition_flag ,true);
        if (opt_type=="update") {
            id_week.val(old_week);
            id_start_time.val(old_start_time);
            id_end_time.val(old_end_time);
            id_userid.val(old_userid);
            id_count.val(old_count);
            id_competition_flag.val(old_competition_flag);
        }
        var old_start = old_start_time;
        var old_week = old_week;
        var old_userid = old_userid;
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


        var arr = [
            [ "星期",  id_week ] ,
            [ "开始时间",  id_start_time ] ,
            [ "结束时间",   id_end_time  ] ,
            [ "userid",   id_userid] ,
            [ "课时数",   id_count] ,
            [ "类型",   id_competition_flag] ,
        ];

        $.show_key_value_table("操作常规课表", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                if(id_start_time.val().substr(0,2)< 4 || id_end_time.val().substr(0,2) < 4){
                    alert('请选择正确的时间');
                    return;
                }
                if(id_count.val()){
                    var st_time =  strtotime(myDate.getFullYear()+'-'+myDate.getMonth()+'-'+myDate.getDay()+' '+id_start_time.val()+':00');
                    var end_time =  strtotime(myDate.getFullYear()+'-'+myDate.getMonth()+'-'+myDate.getDay()+' '+id_end_time.val()+':00');
                    var diff = (end_time - st_time)/60;
                    if (diff<=40) {
                        if(id_count.val() != 1){
                            var res =  confirm("系统计算课时数为 1,您输入的课时数为 "+id_count.val()+",确认OK请点击确定,返回修改点击取消");
                        };
                    } else if ( diff <= 60) {
                        if( id_count.val() !=1.5){
                            var res =  confirm("系统计算课时数为 1.5,您输入的课时数为 "+id_count.val()+",确认OK请点击确定,返回修改点击取消");
                        };
                    } else if ( diff <=90 ) {
                        if(id_count.val() !=2){
                            var res =  confirm("系统计算课时数为 2,您输入的课时数为 "+id_count.val()+",确认OK请点击确定,返回修改点击取消");
                        };
                    }else{
                        if(id_count.val() != Math.ceil(diff/40)){
                            var res =  confirm("系统计算课时数为"+Math.ceil(diff/40)+",您输入的课时数为 "+id_count.val()+",确认OK请点击确定,返回修改点击取消");
                        };
                    }
                    if(res == false){
                        return;
                    }
                }
                $.do_ajax( '/human_resource/otp_common_config_new',{
                    'opt_type'   : opt_type,
                    "teacherid"  : teacherid,
                    'start_time' : ""+id_week.val()+"-"+id_start_time.val(),
                    'old_start_time' : old_start,
                    'end_time'   : id_end_time.val(),
                    "old_key" : old_key,
                    'userid'     : id_userid.val(),
                    'old_userid'     : old_userid,
                    'old_week'     : old_week,
                    'lesson_count'     : 100*(id_count.val()),
                    'competition_flag'     : id_competition_flag.val()
                },function(data){
                    if (data.ret!=0) {
                        alert(data.info);
                    }else{
                        window.location.reload();
                    }
                });
                dialog.close();
            }
        },function(){
            $.admin_select_user(id_userid, "student" );
        });
    }

    $("#id_add_config").on('click',function(){
        opt_item("add","","","","","");
    });

    $('#calendar').fullCalendar({
    header : {
        left   : null,
        center : null,
        right  : null
    },
    lang: 'zh-cn',
    timezone: 'local',
    weekends: true,
    height: 880,
    firstDay: 1,
        minTime:"7:00",

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
            var id_btn_student=$("<button class=\"btn   btn-danger \">点击查看学生详情</button>");

            var arr=[
                ["操作",  id_btn_update ],
                ["操作",  id_btn_del ],
                ["学生",  id_btn_student ],
            ];
            id_btn_del.on("click",function(){
                BootstrapDialog.confirm("要删除？！",function(val){
                    if(val) {
                        $.do_ajax("/human_resource/otp_common_config_new",{
                            "opt_type"  : "del" ,
                            "teacherid" : calEvent.teacherid,
                            "old_key"  : start_time,
                            "start_time" : calEvent.start_time,
                            "userid" : calEvent.userid
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
                         start_time,
                         calEvent.lesson_count,
                         calEvent.teacherid,
                         calEvent.competition_flag
                        );
            });

            id_btn_student.on("click",function(){
                $.wopen("../stu_manage?sid="+calEvent.userid+"&return_url=http%3A%2F%2Fdev.admin.yb1v1.com%2Fuser_manage%2Fall_users   ")
            });


            $.show_key_value_table("操作", arr );
            //-------------------------


        }

    });

    $.do_ajax(
        '/human_resource/get_common_config_new',
        {'teacherid': g_args.teacherid,
         'userid': g_args.userid
        },
        function(data) {
            //alert(JSON.stringify(data));
            $.each(data.common_lesson_config,function(i,item){
                var common_lesson=[];
                var lesson_config={};
                if(item.teacher == ''){
                    item.teacher = 'xxx';
                }
                lesson_config["title"]=  '学生:'+item.nick+'\n'+'老师:'+item.teacher+'\n'+'助教:'+item.ass_nick+'\n'+'类型:'+item.competition_flag_str;
                /*
                 lesson_config.start = (1464235345+3600)* 1000;
                 lesson_config.end   = (1464235345+7600)* 1000;
                 */
                lesson_config["start"]= item.start_time_ex;
                lesson_config["end"]= item.end_time_ex;
                if((item.end_time_ex-item.start_time_ex)<7200000){
                    lesson_config["title"]=  '学生:'+item.nick+' '+'老师:'+item.teacher+'\n'+'助教:'+item.ass_nick+' '+'类型:'+item.competition_flag_str;
                }
              lesson_config["color"]= '#17a6e8';
              lesson_config["userid"]= item.userid;
                lesson_config["start_time"]= item.start_time;
                lesson_config["lesson_count"]= item.lesson_count/100;
                lesson_config["teacherid"]= item.teacherid;
                lesson_config["competition_flag"]= item.competition_flag;

                common_lesson.push(lesson_config);
                $('#calendar').fullCalendar( 'addEventSource', common_lesson);
            });


        });



  $('.opt-change').set_input_change_event(load_data);

    $(".fc-day-header").each(function(){
        $(this).text(  $(this).text().split(" ")[0]);
    });
    $(".fc-widget-content  .fc-row " ).hide();
    $(".fc-widget-content  .fc-widget-header" ).hide();


});
