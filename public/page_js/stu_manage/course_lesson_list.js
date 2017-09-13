
/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-course_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			sid: g_sid	,
			courseid: g_args.courseid, 
			all_flag:	$('#id_all_flag').val()
        });
    }
	$('#id_all_flag').val(g_args.all_flag);
	$('.opt-change').set_input_change_event(load_data);

    $(".cancel_lesson").on("click",function(){
        var lessonid = $(this).get_opt_data('lessonid');
        var courseid = $(this).get_opt_data('courseid');
        
        BootstrapDialog.show({
            title: '取消',
            message : "取消吗？" ,
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

                    $.ajax({
                        url: '/user_deal/cancel_lesson',
                        type: 'POST',
                        data: {
				            'lessonid':  lessonid
			            },
                        dataType: 'json',
                        success: function(data) {
                            window.location.reload();
                        }
                    });//返回可排课程列表调取新数据over

                }
            }]
        }); 
    });

    $(".opt_change_lesson_count").on("click",function(){
        var lessonid = $(this).get_opt_data('lessonid');
        var $lesson_count=$("<input/>");
        var arr =[
            ["课时数"  ,$lesson_count  ]
        ];
        $.show_key_value_table("修改课时数", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax( "/user_deal/lesson_change_lesson_count", {
                    "lessonid" : lessonid ,
                    'lesson_count': $lesson_count.val() *100
                },function(data){
                    if (data.ret !=0 ) {
                        alert(data.info);
                    }else{
                        alert("成功");
                        window.location.reload();
                    }
                }) ;
                
            }
        });
    });


    $.each( $(".start_edit_lesson_time"), function(i,item ){
        var lessonid=  $(item).get_opt_data("lessonid");
        $(item).admin_select_teacher_free_time({
            "teacherid":  $(item).get_opt_data("teacherid"),
            "onSelect" :function(  calEvent,dlg) {
                var v_start  = calEvent.start/1000;
			    var v_end    = calEvent.end/1000;
                var use_flag = calEvent.use_flag;

                if (!use_flag) {
                    BootstrapDialog.show({
                        title: '选择老师时间',
                        message :  "时间段:"+$.DateFormat(v_start,"yyyy-MM-dd hh:mm")+"-"+$.DateFormat(v_end,"yyyy-MM-dd hh:mm") ,
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
		                        $.ajax({
                                    url: '/user_deal/set_lesson_time',
                                    type: 'POST',
                                    data : {
				                        'sid'          : g_sid, 
				                        'lessonid'     : lessonid,
				                        'lesson_start' : v_start,
				                        'lesson_end'   : v_end
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
            "lessonid" : $(item).get_opt_data("lessonid")
        });
    });

    $("#id_add_lesson").on("click",function(){
        // BootstrapDialog.alert("开发中");
        // return false;

        // var id_lesson_start = $("<input>");
        // var id_lesson_end = $("<input>");

        // var arr = [
        //     ["开始时间",id_lesson_start],
        //     ["结束时间",id_lesson_end]
        // ];

        // $.show_key_value_table("排课",arr,{
        //     label    : "确认",
        //     cssClass : "btn-warning",
        //     action   : function(dialog) {
        //         $.do_ajax("/user_deal/add_lesson",{
        //             "courseid"     : g_args.courseid,
        //             "lesson_start" : id_lesson_start.val(),
        //             "lesson_end"   : id_lesson_end.val(),
        //         },function(result){
        //             if(result.ret==0){
        //                 window.location.reload();
        //             }else{
        //                 BootstrapDialog.alert(result.info);
        //             }
        //         });
        //     }
        // });
        if (g_args.courseid>0) {
            $.do_ajax("/user_deal/lesson_add_lesson",{
                courseid:g_args.courseid
            },function(resp){
                var  $item=$("<div></div> ");
                $item.admin_set_lesson_time({
                    "lessonid" : resp.lessonid
                });
                $item.click();
            });
        }else{
            alert( "还没有课程" );
        }
    });

    $(".opt-confirm").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var lessonid = $(this).get_opt_data("lessonid");

        var $confirm_flag              = $("<select> </select>");
        var $lesson_cancel_reason_type = $("<select> </select>");
        var $lesson_cancel_time_type   = $("<select> </select>");
        var $lesson_cancel_reason_next_lesson_time     = $("<input/>");
        var $lesson_cancel_reason_next_lesson_end_time = $("<input/>");
        var $lesson_count   = $("<input/>");
        var $confirm_reason = $("<textarea/> ");

        Enum_map.append_option_list( "confirm_flag", $confirm_flag,true);
        Enum_map.append_option_list( "lesson_cancel_reason_type", $lesson_cancel_reason_type,true);
        Enum_map.append_option_list( "lesson_cancel_time_type", $lesson_cancel_time_type,true);
        
        var arr=[
            ["上课完成", $confirm_flag ] ,
            ["课堂确认情况",$lesson_cancel_time_type] ,
            ["无效类型", $lesson_cancel_reason_type   ] ,
            ["调课-上课时间",$lesson_cancel_reason_next_lesson_time  ],
            ["调课-下课时间",$lesson_cancel_reason_next_lesson_end_time  ],
            ["课时数",$lesson_count  ],
            ["无效说明", $confirm_reason ] 

        ];

        $confirm_flag.val( opt_data.confirm_flag )  ;
        $confirm_reason.val( opt_data.confirm_reason )  ;
        $lesson_cancel_reason_next_lesson_time.val( opt_data.lesson_cancel_reason_next_lesson_time )  ;
        $lesson_count.val( opt_data.lesson_count)  ;
        $lesson_cancel_reason_type.val( opt_data.lesson_cancel_reason_type )  ;

        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        var reset_ui=function() {
            var val=$confirm_flag.val();
            if (val==1 || val==0) {
                show_field( $confirm_reason ,false );
                show_field( $lesson_cancel_reason_type,false );
                show_field( $lesson_cancel_time_type,false );
                show_field( $lesson_cancel_reason_next_lesson_time,false );
                show_field( $lesson_cancel_reason_next_lesson_end_time ,false);
                show_field( $lesson_count ,false);
            }else{
                show_field( $confirm_reason ,true);
                show_field( $lesson_cancel_reason_type,true);
                show_field( $lesson_cancel_time_type,true);
                var reason_type= $lesson_cancel_reason_type.val();
                if ( reason_type >0  && reason_type <10 ) {
                    show_field( $lesson_cancel_reason_next_lesson_time,true);
                    show_field( $lesson_cancel_reason_next_lesson_end_time,true);
                    show_field( $lesson_count,true);
                }else{
                    show_field( $lesson_cancel_reason_next_lesson_time ,false);
                    show_field( $lesson_cancel_reason_next_lesson_end_time ,false);
                    show_field( $lesson_count ,false);
                }
            }
        };

        $confirm_flag.on("change",function(){
            reset_ui();
        });
        $lesson_cancel_reason_type.on("change",function(){
            reset_ui();
        });
        
        $.show_key_value_table("确认课时", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_deal/lesson_set_confirm", {
                    "lessonid":lessonid,
                    "confirm_flag":$confirm_flag.val(),
                    "confirm_reason":$confirm_reason.val(),
                    "lesson_cancel_reason_next_lesson_time":$lesson_cancel_reason_next_lesson_time.val(),
                    "lesson_cancel_reason_next_lesson_end_time":$lesson_cancel_reason_next_lesson_end_time.val(),
                    "lesson_count":$lesson_count.val(),
                    "lesson_cancel_reason_type" : $lesson_cancel_reason_type.val(),
                    "lesson_cancel_time_type"   : $lesson_cancel_time_type.val(),
                    "courseid":opt_data.courseid,
                    "lesson_type":opt_data.lesson_type,
                    "subject":opt_data.subject,
                    "grade":opt_data.grade,
                    "teacherid":opt_data.teacherid,
                    "userid":opt_data.userid,
                    "phone":opt_data.phone
                });
            }
        },function(){
            reset_ui();
            $lesson_cancel_reason_next_lesson_time.datetimepicker({
                datepicker:true,
                timepicker:true,
                format:'Y-m-d H:i',
                step:30,
                onChangeDateTime :function(){
                    var end_time= parseInt(
                        $.strtotime($lesson_cancel_reason_next_lesson_time.val()+':00')) + opt_data.lesson_diff;
                    $lesson_cancel_reason_next_lesson_end_time.val(  $.DateFormat(end_time, "hh:mm"));
                }

            });
            $lesson_cancel_reason_next_lesson_end_time.datetimepicker({
                datepicker:false,
                timepicker:true,
                format:'H:i',
                step:30
            });

		});

    });


});

