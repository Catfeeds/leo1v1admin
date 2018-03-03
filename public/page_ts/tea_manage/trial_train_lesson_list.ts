/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-trial_train_lesson_list.d.ts" />
var Cwhiteboard=null;
$(function(){
    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("check_status",$("#id_status"));
    Enum_map.append_option_list("is_test",$("#id_is_test_flag"));
    Enum_map.append_option_list("teacher_type",$("#id_teacher_type"));
    Enum_map.append_option_list("lesson_status",$("#id_lesson_status"),false,[0,1,2]);
    $('#id_status').append("<option value='999'>旷课</option>");

    $("#id_grade").val(g_args.grade);
    $("#id_status").val(g_args.status);
    $("#id_subject").val(g_args.subject);
    $("#id_teacherid").val(g_args.teacherid);
    $("#id_is_test_flag").val(g_args.is_test);
    $("#id_lesson_status").val(g_args.lesson_status);
    $.admin_select_user( $("#id_teacherid"), "teacher", load_data);
    $("#id_teacher_type").val(g_args.teacher_type);
    function load_data(){
        $.reload_self_page ( {
            date_type_config : $('#id_date_type_config').val(),
            date_type        : $('#id_date_type').val(),
            opt_date_type    : $('#id_opt_date_type').val(),
            start_time       : $('#id_start_time').val(),
            end_time         : $('#id_end_time').val(),
            status           : $('#id_status').val(),
            grade            : $('#id_grade').val(),
            subject          : $('#id_subject').val(),
            teacherid        : $('#id_teacherid').val(),
            is_test          : $('#id_is_test_flag').val(),
            lesson_status    : $('#id_lesson_status').val(),
            teacher_type     : $('#id_teacher_type').val(),
        });
    }

    $(".opt-edit").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        console.log(opt_data.stu_comment);
        var teacherid = opt_data.teacherid;
        var id_jysj = $("<select class=\"class_score\" />");
        var id_yybd = $("<select class=\"class_score\" />");
        var id_zyzs = $("<select class=\"class_score\" />");
        var id_jxjz = $("<select class=\"class_score\" />");
        var id_hdqk = $("<select class=\"class_score\" />");
        var id_bsqk = $("<select class=\"class_score\" />");
        var id_rjcz = $("<select class=\"class_score\" />");
        var id_skhj = $("<select class=\"class_score\" />");
        var id_khfk = $("<select class=\"class_score\" />");
        var id_lcgf = $("<select class=\"class_score\" />");

        var id_sshd=$("<label><input name=\"Fruit\" type=\"checkbox\" value=\"6\" />幽默风趣 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"7\" />生动活泼 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"8\" />循循善诱 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"9\" />细致耐心 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"10\" />考纲熟悉 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"11\" />善于互动</label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"12\" />没有口音</label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"13\" />经验丰富</label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"14\" />功底扎实</label> ");

        Enum_map.append_option_list("teacher_lecture_score",id_jysj,true,[0,1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("teacher_lecture_score",id_yybd,true,[0,1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("teacher_lecture_score",id_zyzs,true,[0,1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("teacher_lecture_score",id_jxjz,true,[0,1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("teacher_lecture_score",id_hdqk,true,[0,1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("teacher_lecture_score",id_bsqk,true,[0,1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("teacher_lecture_score",id_rjcz,true,[0,1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("teacher_lecture_score",id_skhj,true,[0,1,2,3,4,5]);
        Enum_map.append_option_list("teacher_lecture_score",id_khfk,true,[0,1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("test_lesson_score",id_lcgf,true,[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]);
        var id_score        = $("<input readonly/>");
        var id_no_tea_score = $("<input readonly/>");
        var id_record       = $("<textarea />");
        var id_jkqk         = $("<textarea />");
        var id_trial_train_status  = $("<select/>");
        var trial_train_status_html="<option value='2'>未通过</option><option value='1'>通过</option>";
        id_trial_train_status.append(trial_train_status_html);

        var arr = [
            ["讲义设计情况评分", id_jysj],
            ["语言表达能力评分", id_yybd],
            ["专业知识技能评分", id_zyzs],
            ["教学节奏把握评分", id_jxjz],
            ["互动情况评分", id_hdqk],
            ["板书情况评分", id_bsqk],
            ["软件操作评分", id_rjcz],
            ["授课环境评分", id_skhj],
            ["课后反馈评分", id_khfk],
            ["流程规范情况评分", id_lcgf],
            ["总分",id_score],
            ["非教学相关得分",id_no_tea_score],
            ["模拟试听是否通过",id_trial_train_status],
            ["监课情况",id_jkqk],
            ["意见或建议",id_record],
            ["老师标签",id_sshd]
        ];

        $.show_key_value_table("试听评价", arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var record_info = id_record.val();
                if(record_info==""){
                    BootstrapDialog.alert("请填写评价内容!");
                    return ;
                }

                if(record_info.length>150){
                    BootstrapDialog.alert("评价内容不能超过150字!");
                    return ;
                }

                var sshd_good=[];
                id_sshd.find("input:checkbox[name='Fruit']:checked").each(function(i) {
                    sshd_good.push($(this).val());
                });
                var not_grade = "";
                $("input[name='not_grade']:checked").each(function(){
                    if(not_grade==""){
                        not_grade = $(this).val();
                    }else{
                        not_grade += ","+$(this).val();
                    }
                });

                var trial_train_status = id_trial_train_status.val();
                $.do_ajax("/human_resource/set_trial_train_lesson",{
                    "teacherid"                        : teacherid,
                    "lessonid"                         : opt_data.lessonid,
                    "id"                               : opt_data.id,
                    "status"                           : trial_train_status,
                    "tea_process_design_score"         : id_jysj.val(),
                    "language_performance_score"       : id_yybd.val(),
                    "knw_point_score"                  : id_zyzs.val(),
                    "tea_rhythm_score"                 : id_jxjz.val(),
                    "tea_concentration_score"          : id_hdqk.val(),
                    "teacher_blackboard_writing_score" : id_bsqk.val(),
                    "tea_operation_score"              : id_rjcz.val(),
                    "tea_environment_score"            : id_skhj.val(),
                    "answer_question_cre_score"        : id_khfk.val(),
                    "class_abnormality_score"          : id_lcgf.val(),
                    "score"                            : id_score.val(),
                    "no_tea_related_score"             : id_no_tea_score.val(),
                    "record_info"                      : id_record.val(),
                    "record_monitor_class"             : id_jkqk.val(),
                    "sshd_good"                        : JSON.stringify(sshd_good),
                    "record_lesson_list"               : JSON.stringify(opt_data.lessonid)
                });
            }
        },function(){
            id_score.attr("placeholder","满分100分");
            id_record.attr("placeholder","字数不能超过150字");

        });
        arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
            id_score.val(parseInt(id_jysj.val())+parseInt(id_yybd.val())+parseInt(id_zyzs.val())+parseInt(id_jxjz.val())+parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));
            if(id_score.val() <60){
                id_trial_train_status.val(2);
            }else{
                id_trial_train_status.val(1);
            }
            id_no_tea_score.val(parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));
        });
    });

    $(".opt-play").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var lessonid = opt_data.lessonid;

        $.do_ajax("/tea_manage/set_teacher_record_account",{
            "id" : opt_data.id
        },function(result){
            if(result.ret==0){
                $.ajax({
                    type     : "post",
                    url      : "/tea_manage/get_lesson_reply",
                    dataType : "json",
                    data     : {"lessonid":lessonid},
                    success  : function(result){
                        if(result.ret == 0){
                            if ( false && !$.check_in_phone() ) {
                                window.open("http://admin.leo1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                                            +"&audio="+encodeURIComponent(result.audio_url)
                                            +"&start="+result.real_begin_time,"_blank");
                            }else{

                                var w = $.check_in_phone()?329 : 558;
                                var h = w/4*3;
                                var html_node = $("<div style=\"text-align:center;\"> "
                                                  +"<div id=\"drawing_list\" style=\"width:100%\">"
                                                  +"</div><audio preload=\"none\"></audio></div>"
                                                 );
                                BootstrapDialog.show({
                                    title    : '课程回放:lessonid:'+opt_data.lessonid,
                                    message  : html_node,
                                    closable : true,
                                    onhide   : function(dialogRef){
                                    }
                                });
                                Cwhiteboard = get_new_whiteboard(html_node.find("#drawing_list"));
                                Cwhiteboard.loadData(w,h,result.real_begin_time,result.draw_url,result.audio_url,html_node);
                            }
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    }
                });
            }else{
                BootstrapDialog.alert(result.info);
            }
        })
    });

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    $(".opt-confirm-score").on("click",function(){
        var id        = $(this).get_opt_data("id");
        var lessonid        = $(this).get_opt_data("lessonid");
        console.log(id);

        $.do_ajax('/ss_deal/get_train_lesson_record_info',{
            "id" : id,
            "lessonid":lessonid
        },function(resp) {
            var title = "审核评分详情";
            var list = resp.data;
            console.log(list);
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>评分项</td><td>得分</td><tr></table></div>");
            var html_score=
                "<tr>"
                +"<td>讲义设计情况评分</td>"
                +"<td>"+list.tea_process_design_score+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>语言表达能力评分</td>"
                +"<td>"+list.language_performance_score+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>专业知识技能评分</td>"
                +"<td>"+list.knw_point_score+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>教学节奏把握评分</td>"
                +"<td>"+list.tea_rhythm_score+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>互动情况评分</td>"
                +"<td>"+list.tea_concentration_score+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>板书情况评分</td>"
                +"<td>"+list.teacher_blackboard_writing_score+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>软件操作评分</td>"
                +"<td>"+list.tea_operation_score+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>授课环境评分</td>"
                +"<td>"+list.tea_environment_score+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>课后反馈评分</td>"
                +"<td>"+list.answer_question_cre_score+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>流程规范情况评分</td>"
                +"<td>"+list.class_abnormality_score+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>总分</td>"
                +"<td>"+list.record_score+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>非教学相关得分</td>"
                +"<td>"+list.no_tea_related_score+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>监课情况</td>"
                +"<td>"+list.record_monitor_class+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>意见或建议</td>"
                +"<td>"+list.record_info+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>老师标签</td>"
                +"<td>"+list.label+"</td>"
                +"</tr>"



            html_node.find("table").append(html_score);
            var dlg=BootstrapDialog.show({
                title    : title,
                message  : html_node,
                closable : true,
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

            dlg.getModalDialog().css("width","1024px");
        });
    });


    $(".opt-reset-acc").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id= opt_data.id;
        if(id==0){
            alert("无数据,请刷新确认!");
            return;
        }else{
            BootstrapDialog.confirm("确定要重置吗？", function(val){
                if (val) {
                    $.do_ajax( '/user_deal/reset_record_acc', {
                        'id' : id
                    });
                }
            });

        }
    });

    $(".opt-qr-pad-at-time").on("click",function(){
        var lessonid= $(this).get_opt_data("lessonid");
        var url=$(this).data("type");
        var title = $(this).attr("title");
        //得到
        $.do_ajax("/tea_manage/get_lesson_xmpp_audio",{
            "lessonid" :lessonid
        },function(result){

            var data=result.data;

            var args="title=lessonid:"+lessonid+"&beginTime="+data.lesson_start+"&endTime="+data.lesson_end+"&roomId="+data.roomid+"&xmpp="+data.xmpp+"&webrtc="+data.webrtc+"&ownerId="+data.teacherid+"&type="+data.type+"&audioService="+data.audioService ;

            var args_64 = $.base64.encode(args);

            console.log(args);

            var text = encodeURIComponent(url+args_64);

            var dlg = BootstrapDialog.show({
                title: title,
                message :"<div style = \"text-align:center\"><img width=\"350px\" src=\"/common/get_qr?text="+text+"\"></img>" ,
                closable             : true
            });
            //dlg.getModalDialog().css("width","800px");

        });

    });
    $(".opt-qr-pad").on("click",function(){

        var lessonid= $(this).get_opt_data("lessonid");
        var url = $(this).data("type");
        var title=$(this).attr("title");
        //得到
        $.do_ajax("/tea_manage/get_lesson_xmpp_audio",{
            "lessonid" :lessonid
        },function(result){
            var data = result.data;
            var args="title=lessonid : "+lessonid+"&beginTime="+data.real_begin_time+"&endTime="+data.real_end_time+"&drawUrl="+data.draw+"&audioUrl="+data.audio;
            var args_64 = $.base64.encode(args);
            var text = encodeURIComponent(url+args_64);
            var dlg = BootstrapDialog.show({
                title: title,
                message  : "<div style=\"text-align:center\"><img width=\"300\" src=\"/common/get_qr?text="+text+"\"></img><br/>" +  url+args_64+"<br/> "+args +"</div>",
                closable : true
            });
        });
    });

    $(".opt-set-new-lesson").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id= opt_data.id;

        BootstrapDialog.confirm("确定视频出错吗？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/set_new_train_lesson', {
                    'id' : id,
                    'lessonid':opt_data.lessonid
                });
            }
        });



    });

    $(".opt-lesson-recover").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id= opt_data.id;

        BootstrapDialog.confirm("确定恢复吗？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/set_train_lesson_recover', {
                    'id' : id
                });
            }
        });



    });


    $(".opt-out-link").on("click",function(){
        var lessonid = $(this).get_opt_data("lessonid");
        $.do_ajax( "/common/encode_text",{
            "text" : lessonid
        }, function(ret){
            BootstrapDialog.alert("对外链接 : http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text  );
        });
    });

    $(".opt-play-new").on("click",function(){
        var lessonid = $(this).get_opt_data("lessonid");
        var id = $(this).get_opt_data("id");
        $.do_ajax( "/common/encode_text",{
            "text" : lessonid
        }, function(ret){
           // BootstrapDialog.alert("对外链接 : http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text  );
            console.log("http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text);
            $.wopen("http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text);
        });
        $.do_ajax("/tea_manage/set_teacher_record_account",{
            "id" : id
        });

    });


    $(".opt-test").on("click",function(){
        var data = '{"hash":"FowenjfaE1uV_1oBhiH54IrpCcm3","key":"cf730e61cd80dd3eb69c3a63891655631503039454512.jpg"}';
        console.log(JSON.parse( data + "" ));
    });

    $(".opt-get-stu-comment").on("click",function(){
        var lessonid        = $(this).get_opt_data("lessonid");
        console.log(lessonid);

        $.do_ajax('/user_deal/get_train_lesson_comment',{
            "lessonid":lessonid
        },function(resp) {
            var title = "课后评价详情";
            var list = resp.data;
            console.log(list);
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>类别</td><td>详情</td><tr></table></div>");
            var html_score=
                "<tr>"
                +"<td>试听情况</td>"
                +"<td>"+list.stu_lesson_content+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>学习态度</td>"
                +"<td>"+list.stu_lesson_status+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>学习基础情况</td>"
                +"<td>"+list.stu_study_status+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>学生优点</td>"
                +"<td>"+list.stu_advantages+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>学生有待提高</td>"
                +"<td>"+list.stu_disadvantages+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>培训计划</td>"
                +"<td>"+list.stu_lesson_plan+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>教学方向</td>"
                +"<td>"+list.stu_teaching_direction+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>意见、建议等</td>"
                +"<td><textarea>"+list.stu_advice+"</textarea></td>"
                +"</tr>";



            html_node.find("table").append(html_score);
            var dlg=BootstrapDialog.show({
                title    : title,
                message  : html_node,
                closable : true,
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

            dlg.getModalDialog().css("width","1024px");
        });

    });

    $(".opt-show-stu-test-paper").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var url = opt_data.paper_url;
        window.open(url, '_blank');
    });

    $(".opt-get-interview-assess").on("click",function(){
        var opt_data = $(this).get_opt_data();
        $.do_ajax('/user_deal/get_interview_assess_by_subject_grade',{
            "subject":opt_data.subject,
            "grade":opt_data.grade,
            "teacherid":opt_data.teacherid,
        },function(resp) {
            var id_assess = $("<textarea />");
            var arr=[
                ["面试评价",resp.data]
            ];
            id_assess.val(resp.data);
            $.show_key_value_table("面试评价", arr,"");

        });

    });

    $(".opt-reset-interview-assess").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id= opt_data.id;
        console.log(id);
        var $record_info = $("<textarea />");
        var arr=[
            ["面试评价",$record_info]
        ];
        $record_info.val(opt_data.record_info);
        $.show_key_value_table("修改试听评价", arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax('/ajax_deal2/reset_train_lesson_record_info',{
                    "id":id,
                    "record_info":$record_info.val()
                });
 
            }
        });
                

       

    });


    if (window.location.pathname=="/tea_manage/trial_train_lesson_list_zs" || window.location.pathname=="/tea_manage/trial_train_lesson_list_zs/") {
        $(".show_flag").children().hide();
    }

    $(".opt-edit-new").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        console.log(opt_data.stu_comment);
        var teacherid = opt_data.teacherid;

        $.do_ajax('/ajax_deal2/get_teacher_tag_info',{
        },function(resp) {
            var list = resp.data;
            var teacher_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>风格性格:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"style_character\"></div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>专业能力:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"professional_ability\"> </div><div>");
            var class_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>课堂气氛:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"classroom_atmosphere\"></div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>课件要求:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"courseware_requirements\"> </div><div>");
            var teaching_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>素质培养:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"diathesis_cultivation\"></div>");


            $.each(list,function(i,item){
                var str="";
                $.each(item,function(ii,item_p){
                    console.log(item_p);
                    str += "<label style=\"margin-left:6px\"><input name=\""+i+"\" type=\"checkbox\" value=\""+item_p+"\" /> "+item_p+"</label>";
                });
                if(i=="风格性格"){
                    teacher_related_labels.find("#style_character").append(str);
                }else if(i=="专业能力"){
                     teacher_related_labels.find("#professional_ability").append(str);
                }else if(i=="课堂气氛"){
                    class_related_labels.find("#classroom_atmosphere").append(str);
                }else if(i=="课件要求"){
                     class_related_labels.find("#courseware_requirements").append(str);
                }else if(i=="素质培养"){
                     teaching_related_labels.find("#diathesis_cultivation").append(str);
                }
            });

            console.log(teaching_related_labels);
            var id_jysj = $("<select class=\"class_score\" />");
            var id_yybd = $("<select class=\"class_score\" />");
            var id_zyzs = $("<select class=\"class_score\" />");
            var id_jxjz = $("<select class=\"class_score\" />");
            var id_hdqk = $("<select class=\"class_score\" />");
            var id_bsqk = $("<select class=\"class_score\" />");
            var id_rjcz = $("<select class=\"class_score\" />");
            var id_skhj = $("<select class=\"class_score\" />");
            var id_khfk = $("<select class=\"class_score\" />");
            var id_lcgf = $("<select class=\"class_score\" />");

         
            Enum_map.append_option_list("teacher_lecture_score",id_jysj,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_yybd,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_zyzs,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_jxjz,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_hdqk,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_bsqk,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_rjcz,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("teacher_lecture_score",id_skhj,true,[0,1,2,3,4,5]);
            Enum_map.append_option_list("teacher_lecture_score",id_khfk,true,[0,1,2,3,4,5,6,7,8,9,10]);
            Enum_map.append_option_list("test_lesson_score",id_lcgf,true,[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]);
            var id_score        = $("<input readonly/>");
            var id_no_tea_score = $("<input readonly/>");
            var id_record       = $("<textarea />");
            var id_jkqk         = $("<textarea />");
            var id_trial_train_status  = $("<select/>");
            var trial_train_status_html="<option value='2'>未通过</option><option value='1'>通过</option>";
            id_trial_train_status.append(trial_train_status_html);

            var arr = [
                ["讲义设计情况评分", id_jysj],
                ["语言表达能力评分", id_yybd],
                ["专业知识技能评分", id_zyzs],
                ["教学节奏把握评分", id_jxjz],
                ["互动情况评分", id_hdqk],
                ["板书情况评分", id_bsqk],
                ["软件操作评分", id_rjcz],
                ["授课环境评分", id_skhj],
                ["课后反馈评分", id_khfk],
                ["流程规范情况评分", id_lcgf],
                ["总分",id_score],
                ["非教学相关得分",id_no_tea_score],
                ["模拟试听是否通过",id_trial_train_status],
                ["监课情况",id_jkqk],
                ["<font style=\"color:red\">*</font>&nbsp意见或建议",id_record],
                ["<font style=\"color:red\">*</font>&nbsp教师相关标签",teacher_related_labels],
                ["<font style=\"color:red\">*</font>&nbsp课堂相关标签",class_related_labels],
                ["<font style=\"color:red\">*</font>&nbsp教学相关标签",teaching_related_labels],
            ];

            $.show_key_value_table("试听评价", arr,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {

                    var style_character=[];
                    teacher_related_labels.find("#style_character").find("input:checkbox[name='风格性格']:checked").each(function(i) {
                        style_character.push($(this).val());
                    });
                    var professional_ability=[];
                    teacher_related_labels.find("#professional_ability").find("input:checkbox[name='专业能力']:checked").each(function(i) {
                        professional_ability.push($(this).val());
                    });
                    var classroom_atmosphere=[];
                    class_related_labels.find("#classroom_atmosphere").find("input:checkbox[name='课堂气氛']:checked").each(function(i) {
                        classroom_atmosphere.push($(this).val());
                    });
                    var courseware_requirements=[];
                    class_related_labels.find("#courseware_requirements").find("input:checkbox[name='课件要求']:checked").each(function(i) {
                        courseware_requirements.push($(this).val());
                    });
                    var diathesis_cultivation=[];
                    teaching_related_labels.find("#diathesis_cultivation").find("input:checkbox[name='素质培养']:checked").each(function(i) {
                        diathesis_cultivation.push($(this).val());
                    });
                    if(courseware_requirements.length ==0 || style_character.length==0 || professional_ability.length==0 || classroom_atmosphere.length==0 || diathesis_cultivation.length==0){
                        BootstrapDialog.alert("请填写标签内容");
                        return ;

                    }


                    var not_grade = "";
                    $("input[name='not_grade']:checked").each(function(){
                        if(not_grade==""){
                            not_grade = $(this).val();
                        }else{
                            not_grade += ","+$(this).val();
                        }
                    });
                   
                    var record_info = id_record.val();
                    if(record_info==""){
                        BootstrapDialog.alert("请填写意见或建议内容!");
                        return ;
                    }

                    if(record_info.length>150){
                        BootstrapDialog.alert("评价内容不能超过150字!");
                        return ;
                    }



                   
                    var trial_train_status = id_trial_train_status.val();
                    $.do_ajax("/human_resource/set_trial_train_lesson",{
                        "teacherid"                        : teacherid,
                        "lessonid"                         : opt_data.lessonid,
                        "id"                               : opt_data.id,
                        "status"                           : trial_train_status,
                        "tea_process_design_score"         : id_jysj.val(),
                        "language_performance_score"       : id_yybd.val(),
                        "knw_point_score"                  : id_zyzs.val(),
                        "tea_rhythm_score"                 : id_jxjz.val(),
                        "tea_concentration_score"          : id_hdqk.val(),
                        "teacher_blackboard_writing_score" : id_bsqk.val(),
                        "tea_operation_score"              : id_rjcz.val(),
                        "tea_environment_score"            : id_skhj.val(),
                        "answer_question_cre_score"        : id_khfk.val(),
                        "class_abnormality_score"          : id_lcgf.val(),
                        "score"                            : id_score.val(),
                        "no_tea_related_score"             : id_no_tea_score.val(),
                        "record_info"                      : id_record.val(),
                        "record_monitor_class"             : id_jkqk.val(),
                        "record_lesson_list"               : JSON.stringify(opt_data.lessonid),
                        "style_character"                  : JSON.stringify(style_character),
                        "professional_ability"             : JSON.stringify(professional_ability),
                        "classroom_atmosphere"             : JSON.stringify(classroom_atmosphere),
                        "courseware_requirements"          : JSON.stringify(courseware_requirements),
                        "diathesis_cultivation"            : JSON.stringify(diathesis_cultivation),
                        "new_tag_flag" : 1
                    });
                }
            },function(){
                id_score.attr("placeholder","满分100分");
                id_record.attr("placeholder","字数不能超过150字");

            });
            arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
                id_score.val(parseInt(id_jysj.val())+parseInt(id_yybd.val())+parseInt(id_zyzs.val())+parseInt(id_jxjz.val())+parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));
                if(id_score.val() <60){
                    id_trial_train_status.val(2);
                }else{
                    id_trial_train_status.val(1);
                }
                id_no_tea_score.val(parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));
            });

        });

    });

    $(".opt-edit-no-pass").on("click",function(){
        var data     = $(this).get_opt_data();
        var id       = $(this).get_opt_data("id");
        
        
        var id_re_submit=$("<label><input name=\"re_submit\" type=\"checkbox\" value=\"1\" />授课环境不佳</label> <label><input name=\"re_submit\" type=\"checkbox\" value=\"2\" />授课内容错误 </label><label><input name=\"re_submit\" type=\"checkbox\" value=\"7\" />无自我介绍(英语科目) </label><label><input name=\"re_submit\" type=\"checkbox\" value=\"12\" />没有声音</label><label><input name=\"re_submit\" type=\"checkbox\" value=\"13\" />旷课</label><label><input name=\"re_submit\" type=\"checkbox\" value=\"100\" />其他</label> ");
        var id_lecture_out=$("<label><input name=\"lecture_out\" type=\"checkbox\" value=\"3\" />语速过慢/过快 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"4\" />语调沉闷 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"5\" />节奏拖沓 </label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"6\" />枯燥乏味 </label> <label><input name=\"lecture_out\" type=\"checkbox\" value=\"8\" />解题错误</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"9\" />普通话发音不标准</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"10\" />英文发音不标准</label><label><input name=\"lecture_out\" type=\"checkbox\" value=\"100\" />其他</label>");
        var id_reason_all = $("<textarea/>");

        var arr = [
            ["可重审",id_re_submit],
           // ["未通过",id_lecture_out],
            ["原因/建议",id_reason_all]
        ];

        $.show_key_value_table("重审淘汰判断",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var re_submit_list=[];
                id_re_submit.find("input:checkbox[name='re_submit']:checked").each(function(i) {
                    re_submit_list.push($(this).val());
                });
                var lecture_out_list=[];
                id_lecture_out.find("input:checkbox[name='lecture_out']:checked").each(function(i) {
                    lecture_out_list.push($(this).val());
                });

                if(id_reason_all.val()=="" || (re_submit_list.length==0 && lecture_out_list.length==0)){
                    BootstrapDialog.alert("请填写完整");
                    return;
                }
                
                $.do_ajax("/ajax_deal2/set_no_pass_train_info",{
                    "id" : id,
                    "re_submit_list": JSON.stringify(re_submit_list),
                    "lecture_out_list":"",
                    "reason" : id_reason_all.val()
                });
            }
        });
        
    });

    $(".opt-edit-new-1").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        console.log(opt_data.stu_comment);
        var teacherid = opt_data.teacherid;

        $.do_ajax('/ajax_deal2/get_teacher_tag_info',{
        },function(resp) {
            var list = resp.data;
            var teacher_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>风格性格:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"style_character\"></div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>专业能力:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"professional_ability\"> </div><div>");
            var class_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>课堂气氛:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"classroom_atmosphere\"></div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>课件要求:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"courseware_requirements\"> </div><div>");
            var teaching_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>素质培养:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"diathesis_cultivation\"></div>");


            $.each(list,function(i,item){
                var str="";
                $.each(item,function(ii,item_p){
                    console.log(item_p);
                    str += "<label style=\"margin-left:6px\"><input name=\""+i+"\" type=\"checkbox\" value=\""+item_p+"\" /> "+item_p+"</label>";
                });
                if(i=="风格性格"){
                    teacher_related_labels.find("#style_character").append(str);
                }else if(i=="专业能力"){
                    teacher_related_labels.find("#professional_ability").append(str);
                }else if(i=="课堂气氛"){
                    class_related_labels.find("#classroom_atmosphere").append(str);
                }else if(i=="课件要求"){
                    class_related_labels.find("#courseware_requirements").append(str);
                }else if(i=="素质培养"){
                    teaching_related_labels.find("#diathesis_cultivation").append(str);
                }
            });

            console.log(teaching_related_labels);
            var id_jysj = $("<div ><div class='col-xs-6 col-md-1'><span class='class_score'></span>分</div><div class='col-xs-6 col-md-11'><button class='col-xs-6 col-md-1   score_button '>0</button><button class='col-xs-6 col-md-1   score_button '>1</button><button class='col-xs-6 col-md-1   score_button  '>2</button><button class='col-xs-6 col-md-1   score_button '>3</button><button class='col-xs-6 col-md-1   score_button '>4</button><button class='col-xs-6 col-md-1   score_button '>5</button><button class='col-xs-6 col-md-1   score_button '>6</button><button class='col-xs-6 col-md-1   score_button '>7</button><button class='col-xs-6 col-md-1   score_button '>8</button><button class='col-xs-6 col-md-1   score_button '>9</button><button class='col-xs-6 col-md-1   score_button '>10</button></div></div>");
            var id_yybd = $("<div ><div class='col-xs-6 col-md-1'><span class='class_score'></span>分</div><div class='col-xs-6 col-md-11'><button class='col-xs-6 col-md-1   score_button '>0</button><button class='col-xs-6 col-md-1   score_button '>1</button><button class='col-xs-6 col-md-1   score_button '>2</button><button class='col-xs-6 col-md-1   score_button '>3</button><button class='col-xs-6 col-md-1   score_button '>4</button><button class='col-xs-6 col-md-1   score_button '>5</button><button class='col-xs-6 col-md-1   score_button '>6</button><button class='col-xs-6 col-md-1   score_button '>7</button><button class='col-xs-6 col-md-1   score_button '>8</button><button class='col-xs-6 col-md-1   score_button '>9</button><button class='col-xs-6 col-md-1   score_button '>10</button></div></div>");
            var id_zyzs = $("<div ><div class='col-xs-6 col-md-1'><span class='class_score'></span>分</div><div class='col-xs-6 col-md-11'><button class='col-xs-6 col-md-1   score_button '>0</button><button class='col-xs-6 col-md-1   score_button '>1</button><button class='col-xs-6 col-md-1   score_button '>2</button><button class='col-xs-6 col-md-1   score_button '>3</button><button class='col-xs-6 col-md-1   score_button '>4</button><button class='col-xs-6 col-md-1   score_button '>5</button><button class='col-xs-6 col-md-1   score_button '>6</button><button class='col-xs-6 col-md-1   score_button '>7</button><button class='col-xs-6 col-md-1   score_button '>8</button><button class='col-xs-6 col-md-1   score_button '>9</button><button class='col-xs-6 col-md-1   score_button '>10</button></div></div>");
            var id_jxjz = $("<div ><div class='col-xs-6 col-md-1'><span class='class_score'></span>分</div><div class='col-xs-6 col-md-11'><button class='col-xs-6 col-md-1   score_button '>0</button><button class='col-xs-6 col-md-1   score_button '>1</button><button class='col-xs-6 col-md-1   score_button '>2</button><button class='col-xs-6 col-md-1   score_button '>3</button><button class='col-xs-6 col-md-1   score_button '>4</button><button class='col-xs-6 col-md-1   score_button '>5</button><button class='col-xs-6 col-md-1   score_button '>6</button><button class='col-xs-6 col-md-1   score_button '>7</button><button class='col-xs-6 col-md-1   score_button '>8</button><button class='col-xs-6 col-md-1   score_button '>9</button><button class='col-xs-6 col-md-1   score_button '>10</button></div></div>");
            var id_hdqk = $("<div ><div class='col-xs-6 col-md-1'><span class='class_score'></span>分</div><div class='col-xs-6 col-md-11'><button class='col-xs-6 col-md-1   score_button '>0</button><button class='col-xs-6 col-md-1   score_button '>1</button><button class='col-xs-6 col-md-1   score_button '>2</button><button class='col-xs-6 col-md-1   score_button '>3</button><button class='col-xs-6 col-md-1   score_button '>4</button><button class='col-xs-6 col-md-1   score_button '>5</button><button class='col-xs-6 col-md-1   score_button '>6</button><button class='col-xs-6 col-md-1   score_button '>7</button><button class='col-xs-6 col-md-1   score_button '>8</button><button class='col-xs-6 col-md-1   score_button '>9</button><button class='col-xs-6 col-md-1   score_button '>10</button></div></div>");
            var id_bsqk = $("<div ><div class='col-xs-6 col-md-1'><span class='class_score'></span>分</div><div class='col-xs-6 col-md-11'><button class='col-xs-6 col-md-1   score_button '>0</button><button class='col-xs-6 col-md-1   score_button '>1</button><button class='col-xs-6 col-md-1   score_button '>2</button><button class='col-xs-6 col-md-1   score_button '>3</button><button class='col-xs-6 col-md-1   score_button '>4</button><button class='col-xs-6 col-md-1   score_button '>5</button><button class='col-xs-6 col-md-1   score_button '>6</button><button class='col-xs-6 col-md-1   score_button '>7</button><button class='col-xs-6 col-md-1   score_button '>8</button><button class='col-xs-6 col-md-1   score_button '>9</button><button class='col-xs-6 col-md-1   score_button '>10</button></div></div>");
            var id_rjcz = $("<div ><div class='col-xs-6 col-md-1'><span class='class_score'></span>分</div><div class='col-xs-6 col-md-11'><button class='col-xs-6 col-md-1   score_button '>0</button><button class='col-xs-6 col-md-1   score_button '>1</button><button class='col-xs-6 col-md-1   score_button '>2</button><button class='col-xs-6 col-md-1   score_button '>3</button><button class='col-xs-6 col-md-1   score_button '>4</button><button class='col-xs-6 col-md-1   score_button '>5</button><button class='col-xs-6 col-md-1   score_button '>6</button><button class='col-xs-6 col-md-1   score_button '>7</button><button class='col-xs-6 col-md-1   score_button '>8</button><button class='col-xs-6 col-md-1   score_button '>9</button><button class='col-xs-6 col-md-1   score_button '>10</button></div></div>");
            var id_skhj = $("<div ><div class='col-xs-6 col-md-1'><span class='class_score'></span>分</div><div class='col-xs-6 col-md-11'><button class='col-xs-6 col-md-1   score_button '>0</button><button class='col-xs-6 col-md-1   score_button '>1</button><button class='col-xs-6 col-md-1   score_button '>2</button><button class='col-xs-6 col-md-1   score_button '>3</button><button class='col-xs-6 col-md-1   score_button '>4</button><button class='col-xs-6 col-md-1   score_button '>5</button><button class='col-xs-6 col-md-1   score_button '>6</button><button class='col-xs-6 col-md-1   score_button '>7</button><button class='col-xs-6 col-md-1   score_button '>8</button><button class='col-xs-6 col-md-1   score_button '>9</button><button class='col-xs-6 col-md-1   score_button '>10</button></div></div>");
           
            var id_lcgf = $("<div ><div class='col-xs-6 col-md-1'><span class='class_score'></span>分</div><div class='col-xs-6 col-md-11'><button class='col-xs-6 col-md-1   score_button '>0</button><button class='col-xs-6 col-md-1   score_button '>1</button><button class='col-xs-6 col-md-1   score_button '>2</button><button class='col-xs-6 col-md-1   score_button '>3</button><button class='col-xs-6 col-md-1   score_button '>4</button><button class='col-xs-6 col-md-1   score_button '>5</button><button class='col-xs-6 col-md-1   score_button '>6</button><button class='col-xs-6 col-md-1   score_button '>7</button><button class='col-xs-6 col-md-1   score_button '>8</button><button class='col-xs-6 col-md-1   score_button '>9</button><button class='col-xs-6 col-md-1   score_button '>10</button><button class='col-xs-6 col-md-1   score_button '>11</button><button class='col-xs-6 col-md-1   score_button '>12</button><button class='col-xs-6 col-md-1   score_button '>13</button><button class='col-xs-6 col-md-1   score_button '>14</button><button class='col-xs-6 col-md-1   score_button '>15</button></div></div>");

               var id_khfk = $("<div ><div class='col-xs-6 col-md-1'><span class='class_score'></span>分</div><div class='col-xs-6 col-md-11'><button class='col-xs-6 col-md-1   score_button '>0</button><button class='col-xs-6 col-md-1   score_button '>1</button><button class='col-xs-6 col-md-1   score_button '>2</button><button class='col-xs-6 col-md-1   score_button '>3</button><button class='col-xs-6 col-md-1   score_button '>4</button><button class='col-xs-6 col-md-1   score_button '>5</button></div></div>");

            
            // Enum_map.append_option_list("teacher_lecture_score",id_jysj,true,[0,1,2,3,4,5,6,7,8,9,10]);
            // Enum_map.append_option_list("teacher_lecture_score",id_yybd,true,[0,1,2,3,4,5,6,7,8,9,10]);
            // Enum_map.append_option_list("teacher_lecture_score",id_zyzs,true,[0,1,2,3,4,5,6,7,8,9,10]);
            // Enum_map.append_option_list("teacher_lecture_score",id_jxjz,true,[0,1,2,3,4,5,6,7,8,9,10]);
            // Enum_map.append_option_list("teacher_lecture_score",id_hdqk,true,[0,1,2,3,4,5,6,7,8,9,10]);
            // Enum_map.append_option_list("teacher_lecture_score",id_bsqk,true,[0,1,2,3,4,5,6,7,8,9,10]);
            // Enum_map.append_option_list("teacher_lecture_score",id_rjcz,true,[0,1,2,3,4,5,6,7,8,9,10]);
            // Enum_map.append_option_list("teacher_lecture_score",id_skhj,true,[0,1,2,3,4,5]);
            // Enum_map.append_option_list("teacher_lecture_score",id_khfk,true,[0,1,2,3,4,5,6,7,8,9,10]);
            // Enum_map.append_option_list("test_lesson_score",id_lcgf,true,[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]);
            var id_score        = $("<input readonly/>");
            var id_no_tea_score = $("<input readonly/>");
            var id_record       = $("<textarea />");
            var id_jkqk         = $("<textarea />");
            var id_trial_train_status  = $("<select/>");
            var trial_train_status_html="<option value='2'>未通过</option><option value='1'>通过</option>";
            id_trial_train_status.append(trial_train_status_html);

            var arr = [
                ["<font style=\"color:red\">*</font>&nbsp讲义设计情况评分", id_jysj],
                ["<font style=\"color:red\">*</font>&nbsp语言表达能力评分", id_yybd],
                ["<font style=\"color:red\">*</font>&nbsp专业知识技能评分", id_zyzs],
                ["<font style=\"color:red\">*</font>&nbsp教学节奏把握评分", id_jxjz],
                ["<font style=\"color:red\">*</font>&nbsp互动情况评分", id_hdqk],
                ["<font style=\"color:red\">*</font>&nbsp板书情况评分", id_bsqk],
                ["<font style=\"color:red\">*</font>&nbsp软件操作评分", id_rjcz],
                ["<font style=\"color:red\">*</font>&nbsp授课环境评分", id_skhj],
                ["<font style=\"color:red\">*</font>&nbsp课后反馈评分", id_khfk],
                ["<font style=\"color:red\">*</font>&nbsp流程规范情况评分", id_lcgf],
                ["总分",id_score],
                ["非教学相关得分",id_no_tea_score],
                ["模拟试听是否通过",id_trial_train_status],
                ["监课情况",id_jkqk],
                ["<font style=\"color:red\">*</font>&nbsp意见或建议",id_record],
                ["<font style=\"color:red\">*</font>&nbsp教师相关标签",teacher_related_labels],
                ["<font style=\"color:red\">*</font>&nbsp课堂相关标签",class_related_labels],
                ["<font style=\"color:red\">*</font>&nbsp教学相关标签",teaching_related_labels],
            ];

            $.show_key_value_table("试听评价", arr,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {

                    var style_character=[];
                    teacher_related_labels.find("#style_character").find("input:checkbox[name='风格性格']:checked").each(function(i) {
                        style_character.push($(this).val());
                    });
                    var professional_ability=[];
                    teacher_related_labels.find("#professional_ability").find("input:checkbox[name='专业能力']:checked").each(function(i) {
                        professional_ability.push($(this).val());
                    });
                    var classroom_atmosphere=[];
                    class_related_labels.find("#classroom_atmosphere").find("input:checkbox[name='课堂气氛']:checked").each(function(i) {
                        classroom_atmosphere.push($(this).val());
                    });
                    var courseware_requirements=[];
                    class_related_labels.find("#courseware_requirements").find("input:checkbox[name='课件要求']:checked").each(function(i) {
                        courseware_requirements.push($(this).val());
                    });
                    var diathesis_cultivation=[];
                    teaching_related_labels.find("#diathesis_cultivation").find("input:checkbox[name='素质培养']:checked").each(function(i) {
                        diathesis_cultivation.push($(this).val());
                    });
                    if(courseware_requirements.length ==0 || style_character.length==0 || professional_ability.length==0 || classroom_atmosphere.length==0 || diathesis_cultivation.length==0){
                        BootstrapDialog.alert("请填写标签内容");
                        return ;

                    }


                    var not_grade = "";
                    $("input[name='not_grade']:checked").each(function(){
                        if(not_grade==""){
                            not_grade = $(this).val();
                        }else{
                            not_grade += ","+$(this).val();
                        }
                    });
                    
                    var record_info = id_record.val();
                    if(record_info==""){
                        BootstrapDialog.alert("请填写意见或建议内容!");
                        return ;
                    }

                    if(record_info.length>150){
                        BootstrapDialog.alert("评价内容不能超过150字!");
                        return ;
                    }

                    var score_num=0;
                    arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".class_score").each(function(){
                        if($(this).text()==""){
                            score_num+=1;

                        }
                    });
                    if(score_num>0){
                        alert(score_num);
                        BootstrapDialog.alert("请打分");
                        return ;

                    }


                    
                    var trial_train_status = id_trial_train_status.val();
                    $.do_ajax("/human_resource/set_trial_train_lesson",{
                        "teacherid"                        : teacherid,
                        "lessonid"                         : opt_data.lessonid,
                        "id"                               : opt_data.id,
                        "status"                           : trial_train_status,
                        "tea_process_design_score"         : id_jysj.val(),
                        "language_performance_score"       : id_yybd.val(),
                        "knw_point_score"                  : id_zyzs.val(),
                        "tea_rhythm_score"                 : id_jxjz.val(),
                        "tea_concentration_score"          : id_hdqk.val(),
                        "teacher_blackboard_writing_score" : id_bsqk.val(),
                        "tea_operation_score"              : id_rjcz.val(),
                        "tea_environment_score"            : id_skhj.val(),
                        "answer_question_cre_score"        : id_khfk.val(),
                        "class_abnormality_score"          : id_lcgf.val(),
                        "score"                            : id_score.val(),
                        "no_tea_related_score"             : id_no_tea_score.val(),
                        "record_info"                      : id_record.val(),
                        "record_monitor_class"             : id_jkqk.val(),
                        "record_lesson_list"               : JSON.stringify(opt_data.lessonid),
                        "style_character"                  : JSON.stringify(style_character),
                        "professional_ability"             : JSON.stringify(professional_ability),
                        "classroom_atmosphere"             : JSON.stringify(classroom_atmosphere),
                        "courseware_requirements"          : JSON.stringify(courseware_requirements),
                        "diathesis_cultivation"            : JSON.stringify(diathesis_cultivation),
                        "new_tag_flag" : 1
                    });
                }
            },function(){
                id_score.attr("placeholder","满分100分");
                id_record.attr("placeholder","字数不能超过150字");

            });
            arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".score_button").on("click",function(){
                $(this).addClass('btn-primary');
                $(this).siblings().removeClass('btn-primary');
                $(this).parent().parent().find(".class_score").text($(this).text());
              //  alert($(this).text());

                //alert(id_jysj.find(".class_score").text());
                
                id_score.val(parseInt(id_jysj.find(".class_score").text()==""?0:id_jysj.find(".class_score").text())+parseInt(id_yybd.find(".class_score").text()==""?0:id_yybd.find(".class_score").text())+parseInt(id_zyzs.find(".class_score").text()==""?0:id_zyzs.find(".class_score").text())+parseInt(id_jxjz.find(".class_score").text()==""?0:id_jxjz.find(".class_score").text())+parseInt(id_hdqk.find(".class_score").text()==""?0:id_hdqk.find(".class_score").text())+parseInt(id_bsqk.find(".class_score").text()==""?0:id_bsqk.find(".class_score").text())+parseInt(id_rjcz.find(".class_score").text()==""?0:id_rjcz.find(".class_score").text())+parseInt(id_skhj.find(".class_score").text()==""?0:id_skhj.find(".class_score").text())+parseInt(id_khfk.find(".class_score").text()==""?0:id_khfk.find(".class_score").text())+parseInt(id_lcgf.find(".class_score").text()==""?0:id_lcgf.find(".class_score").text()));
                if(id_score.val() <60){
                    id_trial_train_status.val(2);
                }else{
                    id_trial_train_status.val(1);
                }
                id_no_tea_score.val(parseInt(id_hdqk.find(".class_score").text()==""?0:id_hdqk.find(".class_score").text())+parseInt(id_bsqk.find(".class_score").text()==""?0:id_bsqk.find(".class_score").text())+parseInt(id_rjcz.find(".class_score").text()==""?0:id_rjcz.find(".class_score").text())+parseInt(id_skhj.find(".class_score").text()==""?0:id_skhj.find(".class_score").text())+parseInt(id_khfk.find(".class_score").text()==""?0:id_khfk.find(".class_score").text())+parseInt(id_lcgf.find(".class_score").text()==""?0:id_lcgf.find(".class_score").text()));
            });
            arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("width",970);
            arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("left",-200);


        });

    });




    $('.opt-change').set_input_change_event(load_data);
});
