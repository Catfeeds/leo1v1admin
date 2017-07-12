/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-lesson_plan_edit.d.ts" />
$(function(){
    $("#id_courseid").val(g_args.courseid);
    $("#id_all_flag").val(g_args.all_flag);
    
    $(".input-change").on("change",function(){
	    //
        $.reload_self_page({
            "sid" : g_sid, 
            "courseid": $("#id_courseid").val(),
            "all_flag": $("#id_all_flag").val()
        });
	    
    });
    
    $("#id_add_lesson").on("click",function(){
        if (g_args.courseid>0) {
            $.do_ajax("/user_deal/lesson_add_lesson",{
                courseid:g_args.courseid
            });
        }else{
            alert( "还没有课程" );
        }
    });

    $("#id_add_assigned").on("click",function(){
        show_add_assigned(0);
    });

    $("#id_add_assigned_from_tea").on("click",function(){
        var courseid=$("#id_courseid").val();
        show_add_assigned(courseid);
    });

    var show_add_assigned = function( courseid ){
        var $teacherid    = $("<input/>");
        var $lesson_total = $("<input/>");
        var $subject      = $("<select/>");
        var $default_lesson_count = $("<input/>");
        Enum_map.append_option_list( "subject", $subject);

        $.do_ajax("/stu_manage/get_stu_lesson_left",{
            "courseid" : courseid,
            "userid"   : g_sid
        },function(data){
            if(data<=0){
                BootstrapDialog.alert("此学生没有剩余课时");
                return ;
            }

            var arr=[
                ["学生未分配课时",data],
                ["老师",$teacherid],
                ["科目",$subject],
                ["","待分配课时=课堂数*每堂课时数"],
                ["课堂数",$lesson_total],
                ["每堂课时数",$default_lesson_count],
            ];

            $.show_key_value_table("分配课时数",arr,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog){
                    var lesson_total = $lesson_total.val();
                    var subject      = $subject.val();
                    var teacherid    = $teacherid.val();
                    var default_lesson_count= $default_lesson_count.val();
                    if(subject==-1){
                        BootstrapDialog.alert("请选择科目");
                        return ;
                    }
                    if(!teacherid){
                        BootstrapDialog.alert("请选择老师");
                        return ;
                    }
                    if(isNaN(lesson_total) || isNaN(default_lesson_count)){
                        BootstrapDialog.alert("请检查课堂数和课时数是否为数字");
                        return ;
                    }
                    if((lesson_total*default_lesson_count) > data){
                        BootstrapDialog.alert("课时数不足");
                        return ;
                    }

                    $.do_ajax("/stu_manage/add_course_order_for_stu",{
                        "courseid"             : courseid,
                        "teacherid"            : $teacherid.val(),
                        "userid"               : g_sid,
                        "subject"              : subject,
                        "lesson_total"         : lesson_total,
                        "default_lesson_count" : default_lesson_count*100
                    },function(result){
                        if (result.ret !=0 ) {
                            alert(result.info);
                        }else{
                            window.location.reload();
                        }
                    });
                }
            });

            $teacherid.admin_select_user({
                "type" : "teacher"
            });

        });
    }

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
                $.do_ajax("/stu_manage/set_lesson_teacherid",{
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
                    $.do_ajax( '/user_deal/cancel_lesson',  {
				            'lessonid':  lessonid
                    });

                }
            }]
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

    
    $(".opt-edit").on("click",function(){
        var lessonid   = $(this).get_opt_data("lessonid");
        var id_grade   = $("<select></select>");
        var id_subject = $("<select></select>");
        Enum_map.append_option_list("grade",id_grade, true);
        Enum_map.append_option_list("subject",id_subject, true);

        $.do_ajax("/stu_manage/get_lesson_simple_info",{
            "lessonid" : lessonid,
            "type"     : "get"
        },function(result){
            var arr = [
                ["lessonid",lessonid],
                ["科目",id_subject],
                ["年级",id_grade]
            ];

            id_grade.val(result.data.grade);
            id_subject.val(result.data.subject);

            $.show_key_value_table("设置课堂信息",arr,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog){
                    $.do_ajax( "/stu_manage/get_lesson_simple_info", {
                        "type"     : "set",
                        "lessonid" : lessonid ,
                        "grade"    : id_grade.val(),
                        "subject"  : id_subject.val()
                    },function(data){
                        if (data.ret !=0 ) {
                            BootstrapDialog.alert(data.info);
                        }else{
                            alert("成功");
                            window.location.reload();
                        }
                    });
                }
            });
        });
    });
    
    $("#id_change_teacher_subject").on("click",function(){
        
        var id_subject = $("<select></select>");
        var id_teacherid = $("<input/>");

        Enum_map.append_option_list("subject",id_subject, true);

        var arr = [
            ["老师",id_teacherid],
            ["科目",id_subject],
        ];

        $.show_key_value_table("设置课堂信息",arr,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog){
                $.do_ajax("/user_deal/course_set_teacher_subject",{
                    courseid : g_args.courseid,
                    teacherid: id_teacherid.val(),
                    subject : id_subject.val()
                });
            }
        },function(){
            id_teacherid.admin_select_user({
                "type" : "teacher"
            });
        });
    });

	
});

