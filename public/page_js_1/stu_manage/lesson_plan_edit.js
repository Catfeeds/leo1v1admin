
$(function(){
    $("#id_courseid").val(g_args.courseid);
    $("#id_all_flag").val(g_args.all_flag);
    
    $(".input-change").on("change",function(){
	    //
        reload_self_page({
            "sid" : g_sid, 
            "courseid": $("#id_courseid").val(),
            "all_flag": $("#id_all_flag").val()
        });
	    
    });
    
    $("#id_add_lesson").on("click",function(){
	    //
        if (g_args.courseid>0) {
            do_ajax("/user_deal/lesson_add_lesson",{
                courseid:g_args.courseid
            });

        }else{
            alert( "还没有课程" );
        }
    });



    var now=(new Date()).getTime()/1000;

    $.each($(".opt-div"),function(i,item){
        var $item=$(item);
        
        var lesson_start= parseInt($item.data("lesson_start"));
        var lesson_status= parseInt($item.data("lesson_status"));
        var  check_time= lesson_start - 15*60 ;
        if ( lesson_start >0 && now > check_time ) {
            var a_item=$item.find(".start_edit_lesson_time");
                a_item.hide();
        }
        if (lesson_status==2) {
            $item.find(".start_edit_lesson_time").hide();
            $item.find(".change_time").hide();
            $item.find(".opt_change_teacher").hide();
            $item.find(".cancel_lesson").hide();
            
        }
    });

    $.each($(".change_time"),function(i,item){
        $(item).admin_set_lesson_time({
            "lessonid" : $(item).get_opt_data("lessonid")
        });
    });

    $(".opt_change_teacher").on("click",function(){
        var lessonid=  $(this).get_opt_data("lessonid");
        $(this).admin_select_user({
            "type":"teacher",
            "show_select_flag":true,
            "onChange":function(val){
                var id = val;
                do_ajax("/stu_manage/set_lesson_teacherid",{
                    lessonid:lessonid,
                    teacherid:id
                });
                

            }
        });
    });

    $(".opt_change_lesson_count").on("click",function(){
        var lessonid = $(this).get_opt_data('lessonid');
        var $lesson_count=$("<input/>");
        var arr =[
            ["课时数"  ,$lesson_count  ]
        ];
        show_key_value_table("修改课时数", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax( "/user_deal/lesson_change_lesson_count", {
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

    
    
    $.each( $(".start_edit_lesson_time"), function(i,item ){
        var lessonid=  $(item).get_opt_data("lessonid");
        $(item).admin_select_teacher_free_time({
            "teacherid":  $(item).get_opt_data("teacherid"),
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
                                   
                                    url: '/user_deal/set_lesson_time',
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

	
});	

