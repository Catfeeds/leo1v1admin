/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/fulltime_teacher-full_assessment_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
      fulltime_adminid:	$('#id_fulltime_adminid').val()
        });
    }

  $('#id_fulltime_adminid').val(g_args.fulltime_adminid);
    $.admin_select_user(
        $('#id_fulltime_adminid'),
        "admin", load_data,false,{"main_type":5});

    $("#name").on("click",function(){
        var tt = parseInt($("#rate_stars").text());
        alert(tt);
    });

    $(".moral_education_score_flag").on("change",function(){
        $("#moral_education_score").text(parseInt($("#observe_law_score").find("select").val())+parseInt($("#core_socialist_score").find("select").val())+parseInt($("#work_responsibility_score").find("select").val())+parseInt($("#obey_leadership_score").find("select").val())+parseInt($("#dedication_score").find("select").val()));
    });

    $(".tea_score_flag").on("change",function(){
        $("#tea_score").text(parseInt($("#prepare_lesson_score").find("select").val())+parseInt($("#upload_handouts_score").find("select").val())+parseInt($("#handout_writing_score").find("select").val())+parseInt($("#no_absences_score").find("select").val())+parseInt($("#late_leave_score").find("select").val())+parseInt($("#prepare_quality_score").find("select").val())+parseInt($("#class_concent_score").find("select").val())+parseInt($("#tea_attitude_score").find("select").val())+parseInt($("#after_feedback_score").find("select").val())+parseInt($("#modify_homework_score").find("select").val()));
    });

    $(".teach_research_score_flag").on("change",function(){
        $("#teach_research_score").text(parseInt($("#teamwork_positive_score").find("select").val())+parseInt($("#test_lesson_prepare_score").find("select").val())+parseInt($("#undertake_actively_score").find("select").val())+parseInt($("#active_part_score").find("select").val())+parseInt($("#active_share_score").find("select").val()));
    });

    $(".complaint_refund_score_flag").on("change",function(){
        $("#result_score").text(parseInt($("#complaint_refund_score").find("select").val())+parseInt($("#order_per_score").text())+parseInt($("#lesson_count_avg_score").text())+parseInt($("#lesson_level_score").find("select").val()));
    });

    $(".total_score_flag").on("change",function(){
        $("#total_score").text(parseInt($("#observe_law_score").find("select").val())+parseInt($("#core_socialist_score").find("select").val())+parseInt($("#work_responsibility_score").find("select").val())+parseInt($("#obey_leadership_score").find("select").val())+parseInt($("#dedication_score").find("select").val())+parseInt($("#teamwork_positive_score").find("select").val())+parseInt($("#test_lesson_prepare_score").find("select").val())+parseInt($("#undertake_actively_score").find("select").val())+parseInt($("#active_part_score").find("select").val())+parseInt($("#active_share_score").find("select").val())+parseInt($("#prepare_lesson_score").find("select").val())+parseInt($("#upload_handouts_score").find("select").val())+parseInt($("#handout_writing_score").find("select").val())+parseInt($("#no_absences_score").find("select").val())+parseInt($("#late_leave_score").find("select").val())+parseInt($("#prepare_quality_score").find("select").val())+parseInt($("#class_concent_score").find("select").val())+parseInt($("#tea_attitude_score").find("select").val())+parseInt($("#after_feedback_score").find("select").val())+parseInt($("#modify_homework_score").find("select").val())+parseInt($("#complaint_refund_score").find("select").val())+parseInt($("#order_per_score").text())+parseInt($("#lesson_count_avg_score").text())+parseInt($("#lesson_level_score").find("select").val()));
        var time_flag = g_args.time_flag;
        if($("#total_score").text() >= 95){
            $("#rate_stars").text("5星(提前转正)");
        }else if($("#total_score").text() >= 88){
            $("#rate_stars").text("4星(正常转正)");
        }else if($("#total_score").text() >= 80){
            $("#rate_stars").text("3星(正常转正)");
        }else if($("#total_score").text() >= 70){
            $("#rate_stars").text("2星(延期一个月转正)");
        }else{
            $("#rate_stars").text("1星(不合格)");
        }


    });



    $("#id_save").on("click",function(){
        var type_flag = $(this).data("type");
        var time_flag = g_args.time_flag;
        //alert(g_args.time_flag);
        var rate_score = parseInt($("#rate_stars").text());
        var positive_type=0;
        if(positive_type_old==1 || check_is_late==1){
            if(rate_score==1 || rate_score==2){
                var str = "您考核的结果不合格,确认提交吗?";
            }else if(rate_score>2){
                var str = "根据考核的结果,您可以申请转正,确认提交吗";
                positive_type = 4;
            }
        }else if(positive_type_old==2){
            if(rate_score==1){
                var str = "您考核的结果不合格,确认提交吗?";
            }else if(rate_score==2){
                var str = "根据考核的结果,您可以申请延期一个月考核,确认提交吗";
                positive_type = 3;
            }else if(rate_score>2){
                var str = "根据考核的结果,您可以申请正常转正,确认提交吗";
                positive_type = 1;
            }

        }else if(positive_type_old >= 3){
            alert("您没有申请转正资格!")
            return;
        }else{
            if(rate_score==1){
                var str = "您考核的结果不合格,确认提交吗?";
            }else if(rate_score==2){
                var str = "根据考核的结果,您可以申请延期一个月考核,确认提交吗";
                positive_type = 3;
            }else if(rate_score==3 || rate_score==4){
                var str = "根据考核的结果,您可以申请正常转正,确认提交吗";
                positive_type = 1;
            }else if(rate_score ==5){
                var str = "根据考核的结果,您可以申请提前转正,确认提交吗";
                positive_type = 2;
            }

        }
        BootstrapDialog.confirm(str, function(val){
            if (val) {
                $.do_ajax('/user_deal/fulltime_teacher_assessment_deal',{
                    "type_flag"                 : type_flag,
                    "adminid"                   : g_args.tea_adminid,
                    "observe_law_score"         : $("#observe_law_score").find("select").val(),
                    "core_socialist_score"      : $("#core_socialist_score").find("select").val(),
                    "work_responsibility_score" : $("#work_responsibility_score").find("select").val(),
                    "obey_leadership_score"     : $("#obey_leadership_score").find("select").val(),
                    "dedication_score"          : $("#dedication_score").find("select").val(),
                    "prepare_lesson_score"      : $("#prepare_lesson_score").find("select").val(),
                    "upload_handouts_score"     : $("#upload_handouts_score").find("select").val(),
                    "handout_writing_score"     : $("#handout_writing_score").find("select").val(),
                    "no_absences_score"         : $("#no_absences_score").find("select").val(),
                    "late_leave_score"          : $("#late_leave_score").find("select").val(),
                    "prepare_quality_score"     : $("#prepare_quality_score").find("select").val(),
                    "class_concent_score"       : $("#class_concent_score").find("select").val(),
                    "tea_attitude_score"        : $("#tea_attitude_score").find("select").val(),
                    "after_feedback_score"      : $("#after_feedback_score").find("select").val(),
                    "modify_homework_score"     : $("#modify_homework_score").find("select").val(),
                    "teamwork_positive_score"   : $("#teamwork_positive_score").find("select").val(),
                    "test_lesson_prepare_score" : $("#test_lesson_prepare_score").find("select").val(),
                    "undertake_actively_score"  : $("#undertake_actively_score").find("select").val(),
                    "active_part_score"         : $("#active_part_score").find("select").val(),
                    "active_share_score"        : $("#active_share_score").find("select").val(),
                    "complaint_refund_score"    : $("#complaint_refund_score").find("select").val(),
                    "order_per_score"           : $("#order_per_score").text(),
                    "lesson_level_score"        : $("#lesson_level_score").find("select").val(),
                    "stu_lesson_total_score"    : $("#lesson_count_avg_score").text(),
                    "moral_education_score"     : $("#moral_education_score").text(),
                    "tea_score"                 : $("#tea_score").text(),
                    "teach_research_score"      : $("#teach_research_score").text(),
                    "result_score"              : $("#result_score").text(),
                    "total_score"               : $("#total_score").text(),
                    "rate_stars"                : rate_score,
                    "positive_type"             : positive_type,
                   "order_per"                  : g_order_per,
                   "stu_lesson_total"           : g_stu_lesson_total
                },function(res){
                    var id = res.id;
                    if(id<=0){
                        alert("提交考核失败,请联系研发人员确认!")
                        return;
                    }

                    if(positive_type >0){
                        $.do_ajax( "/fulltime_teacher/get_admin_user_info",{
                            "adminid" :g_args.tea_adminid,
                            "positive_type" :positive_type
                        },function(resp){
                            var data = resp.data;
                            var title = "转正申请";
                            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \" style=\"text-align: center;vertical-align: middle\"><tr><td>姓名</td><td>"+data.realname+"</td><td>部门</td><td>"+data.main_department_str+"</td></tr><tr><td>职位</td><td>"+data.post_str+"</td><td>邮箱</td><td>"+data.email+"</td></tr><tr><td>入职时间</td><td>"+data.create_time_str+"</td><td>目前教师等级</td><td>"+data.level_str+"</td></tr><tr><td>转正时间</td><td>"+data.positive_time_str+"</td><td>转正后教师等级</td><td>"+data.positive_level_str+"</td></tr><tr><td>考核情况</td><td>考核星级</td><td colspan=\"2\">"+rate_score+"星</td></tr></tr><tr><td>转正情况</td><td colspan=\"3\">"+data.positive_type_str+"</td></tr><tr><td colspan=\"4\"  bgcolor=\"#F0F0F0\">试用期综合评定</td></tr><tr><td height=\"200px\" style=\"text-align: center;vertical-align: middle\">自我评定</td><td colspan=\"3\" ><textarea rows=\"8\" cols=\"28\" style=\" width:100%; height:100%;\" id=\"id_self_assessment\"></textarea></td></tr><tr></tr><tr></tr><tr><td>教学部总监</td><td colspan=\"3\"></td></tr><tr><td>总经理</td><td colspan=\"3\"></td></tr></table></div>");
                            var dlg=BootstrapDialog.show({
                                title:title,
                                message :  html_node   ,
                                closable: false,
                                buttons:[{
                                    label: '返回',
                                    cssClass: 'btn',
                                    action: function(dialog) {
                                        dialog.close();

                                    }
                                },{
                                    label: '申请',
                                    cssClass: 'btn-warning',
                                    action: function(dialog) {
                                        $.do_ajax('/user_deal/fulltime_teacher_positive_require_deal',{
                                            "type_flag":type_flag,
                                            "adminid" :g_args.tea_adminid,
                                            "assess_id":id,
                                            "main_department" : data.main_department,
                                            "post" : data.post,
                                            "create_time" : data.create_time,
                                            "positive_time" : data.positive_time,
                                            "positive_type" : positive_type,
                                            "rate_stars":rate_score,
                                            "level" : data.level,
                                            "positive_level" : data.positive_level,
                                            "self_assessment" : html_node.find("table").find("#id_self_assessment").val()
                                        });
                                    }
                                }],
                                onshown:function(){

                                }

                            });

                            dlg.getModalDialog().css("width","1024px");
                            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
                            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
                            close_btn.on("click",function(){
                                dlg.close();
                            } );

                        });
                    }else{
                        window.location.reload();
                    }


                });

            }
        });

        //alert(g_args.tea_adminid);

    });


    $("#id_assessment_positive_info").on("click",function(){
        $.do_ajax( "/fulltime_teacher/get_fulltime_teacher_pisitive_require_info",{
            "id" :g_positive_id,
        },function(resp){
            var data = resp.data;
            var title = "转正申请详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \" ><tr style=\"text-align: center;vertical-align: middle\"><td>姓名</td><td>"+data.name+"</td><td>部门</td><td>"+data.main_department_str+"</td></tr><tr style=\"text-align: center;vertical-align: middle\"><td>职位</td><td>"+data.post_str+"</td><td>邮箱</td><td>"+data.email+"</td></tr><tr style=\"text-align: center;vertical-align: middle\"><td>入职时间</td><td>"+data.create_time_str+"</td><td>目前教师等级</td><td>"+data.level_str+"</td></tr><tr style=\"text-align: center;vertical-align: middle\"><td>转正时间</td><td>"+data.positive_time_str+"</td><td>转正后教师等级</td><td>"+data.positive_level_str+"</td></tr><tr style=\"text-align: center;vertical-align: middle\"><td>考核情况</td><td>考核星级</td><td colspan=\"2\">"+data.rate_stars+"星</td></tr></tr><tr style=\"text-align: center;vertical-align: middle\"><td>转正情况</td><td colspan=\"3\">"+data.positive_type_str+"</td></tr><tr style=\"text-align: center;vertical-align: middle\"><td colspan=\"4\"  bgcolor=\"#F0F0F0\" >试用期综合评定</td></tr><tr><td height=\"200px\" style=\"text-align: center;vertical-align: middle\">自我评定</td><td colspan=\"3\" ><textarea rows=\"8\" cols=\"28\" style=\" width:100%; height:100%;\" id=\"id_self_assessment\" >"+data.self_assessment+"</textarea></td></tr><tr></tr><tr></tr><tr><td style=\"text-align: center;vertical-align: middle\">教学部总监</td><td colspan=\"3\">"+data.master_deal_flag_str+"</td></tr><tr><td style=\"text-align: center;vertical-align: middle\">总经理</td><td colspan=\"3\">"+data.main_master_deal_flag_str+"</td></tr></table></div>");
            if(data.master_deal_flag>0){
                var dlg=BootstrapDialog.show({
                    title:title,
                    message :  html_node   ,
                    closable: false,
                    buttons:[{
                        label: '返回',
                        cssClass: 'btn',
                        action: function(dialog) {
                            dialog.close();

                        }
                    }],
                    onshown:function(){

                    }

                });
            }else{
                var dlg=BootstrapDialog.show({
                    title:title,
                    message :  html_node   ,
                    closable: false,
                    buttons:[
                        {
                            label: '返回',
                            cssClass: 'btn',
                            action: function(dialog) {
                                dialog.close();

                            }
                        },{
                            label: '修改',
                            cssClass: 'btn-warning',
                            action: function(dialog) {
                                $.do_ajax('/user_deal/set_fulltime_teacher_self_assessment',{
                                    "id":g_positive_id,
                                    "self_assessment":html_node.find("#id_self_assessment").val()
                                });

                            }
                        }

                    ],
                    onshown:function(){

                    }

                });

            }


            dlg.getModalDialog().css("width","1024px");
            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );

        });

    })

    if(g_args.acc=="jack" || g_args.acc=="low-key"){
        $("#id_fulltime_adminid").parent().parent().show();
    }else{
        $("#id_fulltime_adminid").parent().parent().hide();
    }

  $('.opt-change').set_input_change_event(load_data);
});
