$(function(){

    
    $("#id_lesson_account_id").val( g_args.lesson_account_id );
    
    $("#id_lesson_account_id").on("change",function(){
        window.location.href=window.location.pathname+"?sid="+g_sid+"&lesson_account_id=" + $(this).val() ;
    });

    $("#id_show_log_lesson_account").on("click",function(){
        if ( g_args.lesson_account_id <=0 ) {
            alert("还没有选择课时包");
            return;
        }

        window.location.href="/stu_manage/lesson_account_log/"+"?sid="+g_sid+"&lesson_account_id="
            + g_args.lesson_account_id ;
    });

    
    $.each( $(".opt-set-time"),function(i,item)  {
        var $item=$(item);
        var lessonid=$item.get_opt_data("lessonid");
        $item.admin_set_lesson_time({lessonid :lessonid});
    });

    

    $("#id_set_lesson_account").on("click",function(){
        if ( g_args.lesson_account_id <=0 ) {
            alert("还没有选择课时包");
            return;
        }
	    
        do_ajax( "/stu_manage/get_lesson_account_info", {
            'sid': g_sid,
            "lesson_account_id" : g_args.lesson_account_id 
        },function(data){

            data=data.data;
            var id_course_name=$("<input/>");
            var id_lesson_1v1_price=$("<input/>");
            id_course_name.val(data.course_name);
            id_lesson_1v1_price.val(data.lesson_1v1_price);
            var arr=[
                ["创建时间" ,  data.add_time ],
                ["课程包id" , data.courseid],
                ["课程包名称" , id_course_name],
                //["总金额" ,    data.total_money  ],
                ["总课时" ,    ( data.total_money/data.lesson_1v1_price).toFixed(1)  ],
                ["已消耗课时" ,  ((data.total_money-data.left_money)/ data.lesson_1v1_price).toFixed(1) ],
                ["剩余课时" ,  (data.left_money/data.lesson_1v1_price).toFixed(1) ],
            ];
    
            show_key_value_table("课程包信息", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    do_ajax( "/stu_manage/lesson_account_set", {
                        'sid': g_sid,
                        "lesson_account_id" : g_args.lesson_account_id ,
                        'course_name': id_course_name.val()
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

        }) ;

    });


    $("#id_add_lesson_account").on("click",function(){


        var id_course_name=$("<input/>");
        var id_total_money=$("<input/>");
        var id_lesson_1v1_price=$("<input/>");
        var arr=[
            ["课程包名称" ,  id_course_name],
            ["金额" ,  id_total_money],
            ["1对1,1课时价格" ,  id_lesson_1v1_price],
        ];
    
         show_key_value_table("增加课程包", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax( "/stu_manage/lesson_account_add", {
                    'sid': g_sid,
                    'course_name': id_course_name.val(),
                    'total_money': id_total_money.val(),
                    'lesson_1v1_price': id_lesson_1v1_price.val()
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


    $("#id_add_lesson").on("click",function(){
        if ( g_args.lesson_account_id <=0 ) {
            alert("还没有选择课时包");
            return;
        }


        var teacherid;
        var lesson_start;
        var lesson_end;

        do_ajax( "/stu_manage/get_lesson_account_info_for_add_lesson", {
            "lesson_account_id" : g_args.lesson_account_id }
                 ,function( data) {
                     var lesson_1v1_price=data.lesson_account.lesson_1v1_price;
                     var id_lesson_start=$("<input/>");
                     var id_teacherid=$("<input/>");
                     var id_lesson_count=$("<input/>");
                     id_lesson_count.val(2);

                     teacherid=data.lesson_account.teacherid;
                     do_ajax("/user_manage/get_nick",
                             {"type" : "teacher" ,"id"  : teacherid},
                             function(result){
                                 var nick = result.nick;
                                 id_teacherid.val(nick);
                             });


                     var select_teacher_free_time= id_lesson_start.admin_select_teacher_free_time({
                         "onSelect":function(calEvent) {
                             
                             var v_start = calEvent.start/1000;
			                 var v_end = calEvent.end/1000;
                             var use_flag= calEvent.use_flag;

                             if (!use_flag) {
                                 
                                 lesson_start=v_start;
                                 lesson_end=v_end;
                                 return true;
                                 
                             }else{
                                 alert("已被占用") ;
                             }
                             return false;
                         },
                         "teacherid":teacherid 
                     });

                     



                     id_teacherid.on("click",function(){
                         id_teacherid.admin_select_user({
                             "type":"teacher",
                             "show_select_flag":true,
                             "onChange":function(val){
                                 teacherid=val;
                                 select_teacher_free_time.set_teacherid(teacherid );

                                 do_ajax("/user_manage/get_nick",
                                         {
                                             "type" : "teacher" 
                                             ,"id"  : teacherid
                                         },
			                             function(result){
                                             var nick = result.nick;
                                             id_teacherid.val(nick);
                                         });
                             }
                         });
                     });

                     var arr=[
                         ["老师" ,  id_teacherid],
                         ["课程时间" ,  id_lesson_start],
                         ["当前剩余课时" ,  (data.lesson_account.left_money / data.lesson_account.lesson_1v1_price ).toFixed(1) ],
                         ["课时数"     , id_lesson_count],
                     ];
                     
                     show_key_value_table("增加课次", arr ,{
                         label: '确认',
                         cssClass: 'btn-warning',
                         action: function(dialog) {
                             do_ajax( "/stu_manage/lesson_account_add_lesson", {
                                 'sid': g_sid,
                                 'lesson_account_id': g_args.lesson_account_id, 
                                 'lesson_start': lesson_start,
                                 'lesson_end': lesson_end,
                                 'teacherid': teacherid, 
                                 'lesson_count': id_lesson_count.val()*100
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
    });

    $(".opt-change-money" ).on("click",function(){
        var lessonid=$(this).get_opt_data("lessonid");

        var id_lesson_count=$("<input/>");
        var id_reason=$("<input/>");

        var lesson_count=$(this).get_opt_data("lesson_count"); 
        var real_lesson_count=$(this).get_opt_data("real_lesson_count"); 
        var arr=[
            ["课程时间" , $(this).get_opt_data("lesson_time")  ],
            ["原定课时" ,  lesson_count],
            ["当前课时" ,  real_lesson_count],
            ["修改为"     , id_lesson_count],
            ["原因"     , id_reason]
        ];
        
        show_key_value_table("修改课时", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var change_value= id_lesson_count.val();
                if (change_value<0) {
                    alert("金额 >0");
                    return;
                }

                var reason =$.trim( id_reason.val());
                if( reason =="" ) {
                    alert("要填原因");
                    return;
                }
                do_ajax( "/stu_manage/lesson_account_change_lesson_real_price", {
                    'sid': g_sid,
                    "lesson_account_id" : g_args.lesson_account_id ,
                    'lessonid': lessonid,
                    'real_lesson_count': id_lesson_count.val()*100,
                    'reason': id_reason.val()
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

    
}); 







