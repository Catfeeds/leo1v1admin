/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-trial_train_lesson_list.d.ts" />
var Cwhiteboard=null;
$(function(){
    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("check_status",$("#id_status"));
    Enum_map.append_option_list("is_test",$("#id_is_test_flag"));
    Enum_map.append_option_list("lesson_status",$("#id_lesson_status"),false,[0,1,2]);

    $("#id_grade").val(g_args.grade);
    $("#id_status").val(g_args.status);
    $("#id_subject").val(g_args.subject);
    $("#id_teacherid").val(g_args.teacherid);
    $("#id_is_test_flag").val(g_args.is_test);
    $("#id_lesson_status").val(g_args.lesson_status);
    $.admin_select_user( $("#id_teacherid"), "teacher", load_data);

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
        });
    }

    $(".opt-edit").on("click",function(){
        var opt_data  = $(this).get_opt_data();
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
        var id_sshd = $("<label><input name=\"Fruit\" type=\"checkbox\" value=\"1\" />鼓励发言 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"2\" />善于引导 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"3\" />提问形式多样 </label><label><input name=\"Fruit\" type=\"checkbox\" value=\"4\" />关注度高 </label>");
        var id_sshd2=$("<label><input name=\"dog\" type=\"checkbox\" value=\"5\" />空话套话过多 </label> <label><input name=\"dog\" type=\"checkbox\" value=\"6\" />Yes/No问题过多 </label> <label><input name=\"dog\" type=\"checkbox\" value=\"7\" />提问形式单一 </label> <label><input name=\"dog\" type=\"checkbox\" value=\"8\" />关注度低 </label> ");
        var id_ktfw=$("<label><input name=\"ktfw\" type=\"checkbox\" value=\"1\" />语速均匀 </label> <label><input name=\"ktfw\" type=\"checkbox\" value=\"2\" />轻松愉快 </label> <label><input name=\"ktfw\" type=\"checkbox\" value=\"3\" />节奏紧凑 </label> <label><input name=\"ktfw\" type=\"checkbox\" value=\"4\" />生动有趣 </label><label><input name=\"ktfw\" type=\"checkbox\" value=\"5\" />思路清晰</label> ");
        var id_ktfw2=$("<label><input name=\"kt\" type=\"checkbox\" value=\"6\" />语速过慢/过快 </label> <label><input name=\"kt\" type=\"checkbox\" value=\"7\" />语调沉闷 </label> <label><input name=\"kt\" type=\"checkbox\" value=\"8\" />节奏拖沓 </label><label><input name=\"kt\" type=\"checkbox\" value=\"9\" />枯燥乏味 </label><label><input name=\"kt\" type=\"checkbox\" value=\"10\" />思路混乱 </label>  ");
        var id_skgf=$("<label><input name=\"skgf\" type=\"checkbox\" value=\"1\" />考纲熟悉 </label> <label><input name=\"skgf\" type=\"checkbox\" value=\"2\" />软件使用熟练 </label> <label><input name=\"skgf\" type=\"checkbox\" value=\"3\" />讲义精美</label><label><input name=\"skgf\" type=\"checkbox\" value=\"4\" />截图合理 </label> <label><input name=\"skgf\" type=\"checkbox\" value=\"5\" />板书规范 </label><label><input name=\"skgf\" type=\"checkbox\" value=\"6\" />普通话标准 </label> ");
        var id_skgf2 = $("<label><input name=\"sk\" type=\"checkbox\" value=\"7\" />考纲不熟悉 </label> <label><input name=\"sk\" type=\"checkbox\" value=\"8\" />软件使用生疏 </label> <label><input name=\"sk\" type=\"checkbox\" value=\"9\" />讲义凌乱 </label><label><input name=\"sk\" type=\"checkbox\" value=\"10\" />截图不合理 </label><label><input name=\"sk\" type=\"checkbox\" value=\"11\" />板书不规范 </label> <label><input name=\"sk\" type=\"checkbox\" value=\"12\" />有口音 </label> ");
        var id_jsfg = $("<label><input name=\"jsfg\" type=\"checkbox\" value=\"1\" />平易近人 </label> <label><input name=\"jsfg\" type=\"checkbox\" value=\"2\" />生动活泼</label> <label><input name=\"jsfg\" type=\"checkbox\" value=\"3\" />幽默风趣 </label> <label><input name=\"jsfg\" type=\"checkbox\" value=\"4\" />严谨认真 </label> ");
        var id_jsfg2 = $("<label><input name=\"js\" type=\"checkbox\" value=\"5\" />咄咄逼人</label> <label><input name=\"js\" type=\"checkbox\" value=\"6\" />沉闷乏味 </label> <label><input name=\"js\" type=\"checkbox\" value=\"7\" />缺乏课堂主导性 </label><label><input name=\"js\" type=\"checkbox\" value=\"8\" />散漫随性 </label>  ");

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
            ["标签-师生互动(好)",id_sshd],
            ["标签-师生互动(不好)",id_sshd2],
            ["标签-课堂氛围(好)",id_ktfw],
            ["标签-课堂氛围(不好)",id_ktfw2],
            ["标签-授课规范(好)",id_skgf],
            ["标签-授课规范(不好)",id_skgf2],
            ["标签-教师风格(好)",id_jsfg],
            ["标签-教师风格(不好)",id_jsfg2],
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
                var sshd_bad=[];
                id_sshd2.find("input:checkbox[name='dog']:checked").each(function(i) {
                    sshd_bad.push($(this).val());
                });
                var ktfw_good=[];
                id_ktfw.find("input:checkbox[name='ktfw']:checked").each(function(i) {
                    ktfw_good.push($(this).val());
                });
                var ktfw_bad=[];
                id_ktfw2.find("input:checkbox[name='kt']:checked").each(function(i) {
                    ktfw_bad.push($(this).val());
                });
                var skgf_good=[];
                id_skgf.find("input:checkbox[name='skgf']:checked").each(function(i) {
                    skgf_good.push($(this).val());
                });
                var skgf_bad=[];
                id_skgf2.find("input:checkbox[name='sk']:checked").each(function(i) {
                    skgf_bad.push($(this).val());
                });

                var jsfg_good=[];
                id_jsfg.find("input:checkbox[name='jsfg']:checked").each(function(i) {
                    jsfg_good.push($(this).val());
                });
                var jsfg_bad=[];
                id_jsfg2.find("input:checkbox[name='js']:checked").each(function(i) {
                    jsfg_bad.push($(this).val());
                });

                $.do_ajax("/human_resource/set_trial_train_lesson",{
                    "teacherid"                        : teacherid,
                    "lessonid"                         : opt_data.lessonid,
                    "id"                               : opt_data.id,
                    "status"                           : id_trial_train_status.val(),
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
                    "record_lessonid_list"             : JSON.stringify(opt_data.lessonid),
                    "sshd_good"                        : JSON.stringify(sshd_good),
                    "sshd_bad"                         : JSON.stringify(sshd_bad),
                    "ktfw_good"                        : JSON.stringify(ktfw_good),
                    "ktfw_bad"                         : JSON.stringify(ktfw_bad),
                    "skgf_good"                        : JSON.stringify(skgf_good),
                    "skgf_bad"                         : JSON.stringify(skgf_bad),
                    "jsfg_good"                        : JSON.stringify(jsfg_good),
                    "jsfg_bad"                         : JSON.stringify(jsfg_bad),
                });
            }
        },function(){
            id_score.attr("placeholder","满分100分");
            id_record.attr("placeholder","字数不能超过150字");
        });
        arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
            id_score.val(parseInt(id_jysj.val())+parseInt(id_yybd.val())+parseInt(id_zyzs.val())+parseInt(id_jxjz.val())+parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));
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
                                window.open("http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
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
                                    title    : '课程回放:lessonid:'+opt_data.lessonid+", 学生:" + opt_data.stu_nick,
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

	$('.opt-change').set_input_change_event(load_data);
});
