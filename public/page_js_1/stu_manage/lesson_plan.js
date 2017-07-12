// SWITCH-TO:   ../../template/student/lesson_plan.html  
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
		height: 600,
		firstDay: 1,
		defaultView: 'agendaWeek',
		events: [{}],
        minTime:"6:00",
		timeFormat:{agenda: 'H:mm'},
		axisFormat:'H:mm'

    });
	

    //setTimeout("do_bind_event()",1000);


	//老师空闲时间日历生成
    var frist_flag=true; // calendar02 需要特别处理
	$('#calendar02').fullCalendar({
		header: {
            left: 'prev,next,today',
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
			var v_start = calEvent.start/1000;
			var v_end = calEvent.end/1000;
            var teacherid=$("#calendar02").data("teacherid");
            var lessonid=$("#calendar02").data("lessonid");
            
            BootstrapDialog.show({
                title: '选择老师时间',
                message :  "时间段:"+DateFormat(v_start,"yyyy-MM-dd hh:mm")+"-"+DateFormat(v_end,"yyyy-MM-dd hh:mm") ,
                closable: false, 
                buttons: [{
                    label: '返回',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        dialog.close();
                        //处理
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
                                    alert(data['info']);
                                }else{
                                    page_ajax(1,0);
                                    //回到
                                    $(".div-only-one").hide();
                                    $(".class_ing" ).show();
                                }
                            }
                        });
                    }
                }]
            }); 




    	},
		eventResize: function(event, delta, revertFunc) {
		}
        , eventAfterRender:function(){
            if(frist_flag){ //特别处理
                $('.class_end').hide();
                frist_flag=false;
            }
        }
    });
    
    do_bind_event();
    
	
	
	$("#id_goto_custom_lesson").attr('href', '/stu_manage/lesson_custom?sid='+g_sid+'&nick='+g_nick+"&return_url="+ encodeURIComponent(window.location.href));
	
	
	
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

    



	page_ajax(1,0);	//初始化在第一页
	
});


//日历调取课表数据
function schedule_event(timestamp)
{

	timestamp=Math.floor(timestamp);
    $.ajax({
        url: '/lesson_manage/get_schedule',
        type: 'POST',
        data: {'userid': g_sid , 'timestamp': Math.round(timestamp), 'type': 1},
        dataType: 'jsonp',
        success: function(data) {
            if (data['ret'] == 0) {
                var lesson_source = [];
                if (data['lesson_list'].length != 0) {
                    for (var i = 0; i < data['lesson_list'].length; i++ ) {
                        var lesson_data = new Object();
                        lesson_data.title =  data['lesson_list'][i].title;
                        lesson_data.start = data['lesson_list'][i].lesson_start * 1000;
                        lesson_data.end   = data['lesson_list'][i].lesson_end * 1000;
						var lesson_type= data['lesson_list'][i].lesson_type;
						if ( lesson_type==1){ //赠送
							//lesson_data.color = '#A11E08';
							lesson_data.color = '#17a6e8';
						} else if ( lesson_type==100){
							lesson_data.color = '#2A7E12';
							//lesson_data.color = '#17a6e8';

						}else{
							lesson_data.color = '#17a6e8';
						}

                        lesson_source.push(lesson_data);
                    }
                }
                $('#calendar').fullCalendar( 'removeEvents' );
                $('#calendar').fullCalendar( 'addEventSource', lesson_source);
            }

        }
    });

};
//课表数据over

//日历调取空闲时间数据


//可排课程
//可排课程的分页函数start
function page_ajax(page_num,inde){
    do_ajax(
		'/lesson_manage/get_lessons',
		{'userid': g_sid, 'page_num': page_num},
		function(data) {
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
						c_data.lessonid   = data['lesson_list'][i].lessonid;
						
						stu_source.push(c_data);
					}
					
				};
				console.log(data['page_cnt']);
				var page_num=data['page_cnt'];
			}
			console.log("work ....");
			

            var row_str="";
			//动态生成表格
			for(var c=0;c<lesson_num;c++){
                //处理
                row_str =  row_str+'<tr><td class="remove-for-not-xs"  > <ul class="nav nav-pills" > <li class="dropdown all-camera-dropdown"> <a class="dropdown-toggle  fa  fa-cog " data-toggle="dropdown" href="javascript:;"  > </a> <ul class="dropdown-menu"   > <li   > </li> </ul> </li> </ul> </td>';

                row_str = row_str+"<td>"+ stu_source[c].courseid ;
                row_str = row_str+"<td>"+ stu_source[c].t_name;
				if(stu_source[c].start!=0){
                    row_str = row_str+"<td>"+  DateFormat (stu_source[c].start,"yyyy-MM-dd hh:mm" );
                    row_str = row_str+"<td>"+  DateFormat (stu_source[c].end,"yyyy-MM-dd hh:mm" );
                }else{
                    row_str = row_str+"<td>";  
                    row_str = row_str+"<td>";
                }
                row_str = row_str+"<td>"+ stu_source[c].lesson_num;

                var str="";
                if(stu_source[c].start != 0){
                    str = "<a href='javascript:;' class='cancel_lesson btn fa fa-trash-o' data-courseid='"+stu_source[c].courseid+"' data-lessonid='"+stu_source[c].lessonid+"' title='取消课程'> </a>";
                    str += "<a href='javascript:;' class='change_time btn fa fa-clock-o' data-courseid='"+stu_source[c].courseid+"' data-lessonid='"+stu_source[c].lessonid+"' data-lesson_num='"+stu_source[c].lesson_num+"' data-lesson_start='"+stu_source[c].start+"' data-lesson_end='"+ stu_source[c].end +"' data-teacherid='"+stu_source[c].teacherid+"' title=\"更改时间\"></a>";
                }else{
                    str = "<a href='javascript:;' class='start_edit_lesson_time' data-teacherid='"+ stu_source[c].teacherid +"' data-lessonid='"+ stu_source[c].lessonid +"'>开始排课</a><input type='hidden' value='"+stu_source[c].courseid+","+stu_source[c].lesson_num+","+stu_source[c].lesson_type+","+stu_source[c].teacherid+"'/>";
                }
                row_str = row_str+"<td class=\"remove-for-xs\"> <div> <a href=\"javascript:;\" class=\"btn  fa fa-info td-info\"></a> "+ str +"</div>";
                row_str =row_str+ "</tr>";
			};

			$('.can_plan tbody').html(row_str);


            $.each( $(".start_edit_lesson_time"), function(i,item ){
                var lessonid=  $(item).data("lessonid");
                $(item).admin_select_teacher_free_time({
                    "teacherid":  $(item).data("teacherid"),
                    "onSelect" :function(  calEvent,dlg) {
                        var v_start = calEvent.start/1000;
			            var v_end = calEvent.end/1000;
                        var use_flag= calEvent.use_flag;

                        if (!use_flag) {
                            BootstrapDialog.show({
                                title: '选择老师时间',
                                message :  "时间段:"+DateFormat(v_start,"yyyy-MM-dd hh:mm")+"-"+DateFormat(v_end,"yyyy-MM-dd hh:mm") ,
                                closable: false, 
                                buttons: [{
                                    label: '返回',
                                    action: function(dialog) {
                                        dialog.close();
                                    }
                                }, {
                                    label: '确认',
                                    cssClass: 'btn-warning',
                                    action: function(dialog) {
                                        dialog.close();
                                        //处理
		                                $.ajax({
                                            url: '/stu_manage/set_lesson_time',
                                            type: 'POST',
                                            data : {
				                                'sid'      : g_sid, 
				                                'lessonid' : lessonid,
				                                'lesson_start'    : v_start,
				                                'lesson_end'      : v_end
				                            },
                                            dataType: 'json',
                                            success: function(data) {
                                                if (data['ret'] != 0) {
                                                    alert(data['info']);
                                                }else{
                                                    alert("成功");
                                                    window.location.reload();
                                                }
                                            }
                                        });
                                    }
                                }]
                            }); 

                            
                            return false;
                            
                        }else{
                            alert("已被占用") ;
                            return false; 
                        }
                    }
                });
            });




            $.each($(".change_time"),function(i,item){
                $(item).admin_set_lesson_time({
                    "lessonid" : $(item).data("lessonid")
                });
            });

            
            $(".cancel_lesson").on("click",function(){
                var lessonid = $(this).data('lessonid');
                var courseid = $(this).data('courseid');
                
                BootstrapDialog.show({
                    title: '取消原因',
                    message : $("<textarea id=\"id_note\" class=\"form-control\" style=\"height:150px\" />"),
                    closable: false, 
                    buttons: [{
                        label: '返回',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }, {
                        label: '确认',
                        cssClass: 'btn-warning',
                        action: function(dialog) {
                            dialog.close();

		                    var note = $.trim(dlg_get_val_by_id("id_note") );
                            $.ajax({
                                url: '/lesson_manage/cancel_lesson',
                                type: 'POST',
                                data: {
				                    'lessonid':  lessonid,
                                    'courseid':  courseid,
                                    'cancel_reason':note 
			                    },
                                dataType: 'json',
                                success: function(data) {
                                    page_ajax(1,0);
                                }
                            });//返回可排课程列表调取新数据over

                        }
                    }]
                }); 


            });

            //OK
            $('body').scrollspy({ target: '.nav-pills' });
            bind_td_drapdown();
            bind_td_info();

		}
	);
	
};	//可排课程的分页函数over

