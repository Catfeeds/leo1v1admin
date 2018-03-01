/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/fulltime_teacher-fulltime_teacher_assessment_positive_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            adminid:	$('#id_adminid').val(),
            become_full_member_flag:	$('#id_become_full_member_flag').val(),
            fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
        });
    }


    Enum_map.append_option_list("boolean", $("#id_become_full_member_flag") );
    Enum_map.append_option_list("fulltime_teacher_type", $("#id_fulltime_teacher_type"),false,[1,2]);
    $('#id_adminid').val(g_args.adminid);
    $('#id_become_full_member_flag').val(g_args.become_full_member_flag);
    $('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);
    $.admin_select_user(
        $('#id_adminid'),
        "admin", load_data,false,{"main_type":5});

    // if (window.location.pathname=="/fulltime_teacher/fulltime_teacher_assessment_positive_info" || window.location.pathname=="/fulltime_teacher/fulltime_teacher_assessment_positive_info/") {
    //     $(".set_fulltime_teacher_assessment_master").hide();
    //     $(".set_fulltime_teacher_positive_require_master").hide();
    // }else{
    //     $(".set_fulltime_teacher_assessment").hide();
    //     $(".set_fulltime_teacher_positive_require").hide();
    // }


    $(".set_fulltime_teacher_assessment").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id = opt_data.id;
        var uid = opt_data.uid;
                console.log(id);
        console.log(opt_data.positive_id);

        if(id <= 0){
           // BootstrapDialog.alert("该老师还未进行考核自评!");

            $.do_ajax( "/fulltime_teacher/get_fulltime_teacher_assessment_info_by_adminid",{
                "adminid" :uid,
            },function(resp){
                var title = "老师考核分数详情";
                var list = resp.data;
                var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>评分项</td><td>得分</td><tr></table></div>");
                var html_score=
                    "<tr>"
                    +"<td>试用期转化率绩效:"+list.order_per+"分</td>"
                    +"<td>"+list.order_per_score+"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td>试用期内月平均课时消耗:"+list.lesson_count_avg+"</td>"
                    +"<td>"+list.lesson_count_avg_score+"</td>"
                    +"</tr>"
                ;

                html_node.find("table").append(html_score);
                var dlg=BootstrapDialog.show({
                    title:title,
                    message :  html_node   ,
                    closable: true,
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

                dlg.getModalDialog().css("width","800px");

            });


            return;
        }

        $.do_ajax( "/fulltime_teacher/get_fulltime_teacher_assessment_info",{
            "id" :id,
        },function(resp){
            var data = resp.data;
            var moral_education_score_master_data = data.moral_education_score_master;
            moral_education_score_master_data = get_val_empty(moral_education_score_master_data);
            var tea_score_master_data = data.tea_score_master;
            tea_score_master_data = get_val_empty(tea_score_master_data);
            var teach_research_score_master_data = data.teach_research_score_master;
            teach_research_score_master_data = get_val_empty(teach_research_score_master_data);
            var result_score_master_data = data.result_score_master;
            result_score_master_data = get_val_empty(result_score_master_data);
            var total_score_master_data = data.total_score_master;
            total_score_master_data = get_val_empty(total_score_master_data);
            var rate_stars_master_data = data.rate_stars_master;
            if(rate_stars_master_data>0){
                rate_stars_master_data=rate_stars_master_data+"星";
            }else{
                rate_stars_master_data="";
            }

            var title = "考核评定";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \" ><tr><td >内容</td><td>考核标准</td><td>分值</td><td>自评</td><td>自评总分</td><td>组长审定</td></tr><tr><td rowspan=\"5\" style=\"vertical-align: middle\">德育(10分)</td><td>遵纪守法,严格遵守公司各项规章制度,上班不迟到、不早退,无请假</td><td>2</td><td>"+data.observe_law_score+"</td><td rowspan=\"5\"  style=\"vertical-align: middle\">"+data.moral_education_score+"</td><td rowspan=\"5\"  style=\"vertical-align: middle\"><input class=\"input_score\" id=\"moral_education_score_master\" type=\"text\" value=\""+moral_education_score_master_data+"\"></td></tr><tr><td>高度认同公司经营理念、企业文化和核心价值观</td><td>2</td><td>"+data.core_socialist_score+"</td></tr><tr><td>勇于承担工作责任,不发表消极言论,积极维护企业形象</td><td>2</td><td>"+data.work_responsibility_score+"</td></tr><tr><td>服从上级领导的安排,积极融入团队合作</td><td>2</td><td>"+data.obey_leadership_score+"</td></tr><tr><td>有高尚的师德,爱岗敬业</td><td>2</td><td>"+data.dedication_score+"</td></tr><tr><td rowspan=\"10\" style=\"vertical-align: middle\">教学(22分)</td><td>认真准备好每次课的教师讲义和学生讲义,并备好本节课学生作业</td><td>2</td><td>"+data.prepare_lesson_score+"</td><td rowspan=\"10\"  style=\"vertical-align: middle\">"+data.tea_score+"</td><td rowspan=\"10\"  style=\"vertical-align: middle\"><input class=\"input_score\" id=\"tea_score_master\" type=\"text\" value=\""+tea_score_master_data+"\"></td></tr><tr><td>在上课前4小时备好本节课讲义和作业并上传至上课平台</td><td>2</td><td>"+data.upload_handouts_score+"</td></tr><tr><td>讲义编写需做到:备学生、备教材、备课堂</td><td>2</td><td>"+data.handout_writing_score+"</td></tr><tr><td>严格按照课程表上课,不私自调、停课</td><td>2</td><td>"+data.no_absences_score+"</td></tr><tr><td>按时上下课,不迟到、不早退</td><td>2</td><td>"+data.late_leave_score+"</td></tr><tr><td>精心上好每节课,重点突出,生动形象,有吸引力</td><td>2</td><td>"+data.prepare_quality_score+"</td></tr><tr><td>上课途中手机调静音,不打接电话,不私自离岗</td><td>2</td><td>"+data.class_concent_score+"</td></tr><tr><td>适时鼓励学生,无贬低、辱骂、抱怨学生现象</td><td>4</td><td>"+data.tea_attitude_score+"</td></tr><tr><td>课后及时对本课堂学生表现及教学效果进行评价和反馈</td><td>2</td><td>"+data.after_feedback_score+"</td></tr><tr><td>学生提交的作业及时进行讲评和订正</td><td>2</td><td>"+data.modify_homework_score+"</td></tr><tr><td rowspan=\"5\" style=\"vertical-align: middle\">教研(13分)</td><td>积极配合排课老师上好所接学生的试听课</td><td>2</td><td>"+data.teamwork_positive_score+"</td><td rowspan=\"5\"  style=\"vertical-align: middle\">"+data.teach_research_score+"</td><td rowspan=\"5\"  style=\"vertical-align: middle\"><input class=\"input_score\" id=\"teach_research_score_master\" type=\"text\" value=\""+teach_research_score_master_data+"\"></td></tr><tr><td>每接一个试听课,都积极准备</td><td>2</td><td>"+data.test_lesson_prepare_score+"</td></tr><tr><td>积极承担教学组长布置任务,准备充分,效果好</td><td>2</td><td>"+data.undertake_actively_score+"</td></tr><tr><td>积极参与教学经验交流、分享活动,积极发挥以老带新作用</td><td>2</td><td>"+data.active_part_score+"</td></tr><tr><td>积极主动分享高质量教学讲义、教学视频</td><td>2</td><td>"+data.active_share_score+"</td></tr><tr><td rowspan=\"4\" style=\"vertical-align: middle\">成果(55分)</td><td>试用期转化率绩效:"+data.order_per+"分</td><td>20</td><td>"+data.order_per_score+"</td><td rowspan=\"4\"  style=\"vertical-align: middle\">"+data.result_score+"</td><td rowspan=\"4\"  style=\"vertical-align: middle\"><input class=\"input_score\" id=\"result_score_master\" type=\"text\" value=\""+result_score_master_data+"\"></td></tr><tr><td>家长评价:"+data.lesson_level+"星</td><td>5</td><td>"+data.lesson_level_score+"</td></tr><tr><td>试用期内月平均课时消耗:"+data.stu_lesson_total+"课时</td><td>25</td><td>"+data.stu_lesson_total_score+"</td></tr><tr><td>家长投诉和退费</td><td>5</td><td>"+data.complaint_refund_score+"</td></tr><tr><td colspan=\"2\">评定总分</td><td>100</td><td colspan=\"2\">"+data.total_score+"</td><td><input class=\"total_score\" id=\"total_score_master\" type=\"text\" value=\""+total_score_master_data+"\"></td></tr><tr><td colspan=\"2\">评定星级</td><td>5星</td><td colspan=\"2\">"+data.rate_stars+"星</td><td><input class=\"rate_stars\" id=\"rate_stars_master\"  type=\"text\" value=\""+rate_stars_master_data+"\"></td></tr></table></div>");
            html_node.find(".input_score").bind('input', function(){
                var moral_education_score_master= parseInt(html_node.find("#moral_education_score_master").val());
                moral_education_score_master = get_val(moral_education_score_master);
                var tea_score_master= parseInt(html_node.find("#tea_score_master").val());
                tea_score_master = get_val(tea_score_master);
                var teach_research_score_master= parseInt(html_node.find("#teach_research_score_master").val());
                teach_research_score_master = get_val(teach_research_score_master);
                var result_score_master= parseInt(html_node.find("#result_score_master").val());
                result_score_master = get_val(result_score_master);
                var total_score_master = moral_education_score_master+teach_research_score_master+tea_score_master+result_score_master;
                html_node.find("#total_score_master").val(total_score_master);
                if(total_score_master >= 95){
                    var str ="5星";
                }else if(total_score_master >= 88){
                     var str ="4星";
                }else if(total_score_master >= 80){
                     var str ="3星";
                }else if(total_score_master >= 70){
                     var str ="2星";
                }else{
                    var str ="1星";
                }
                html_node.find("#rate_stars_master").val(str);
            });
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
                    label: '审核',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        $.do_ajax('/user_deal/fulltime_teacher_assessment_deal_master',{
                            "id":id,
                            "moral_education_score_master":html_node.find("#moral_education_score_master").val(),
                            "tea_score_master":html_node.find("#tea_score_master").val(),
                            "teach_research_score_master":html_node.find("#teach_research_score_master").val(),
                            "result_score_master":html_node.find("#result_score_master").val(),
                            "total_score_master":html_node.find("#total_score_master").val(),
                            "rate_stars_master":html_node.find("#rate_stars_master").val(),
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

    });
    function get_val(val){
        if(val>0){

        }else{
            val=0;
        }
        return val;
    }
    function get_val_empty(val){
        if(val==0){
            val="";
        }
        return val;
    }

    $(".set_fulltime_teacher_assessment_master").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id = opt_data.id;
        console.log(id);
        console.log(opt_data.positive_id);
        if(id <= 0){
            BootstrapDialog.alert("该老师还未进行考核自评!");
            return;
        }

        $.do_ajax( "/fulltime_teacher/get_fulltime_teacher_assessment_info",{
            "id" :id,
        },function(resp){
            var data = resp.data;
            var moral_education_score_master_data = data.moral_education_score_master;
            moral_education_score_master_data = get_val_empty(moral_education_score_master_data);
            var tea_score_master_data = data.tea_score_master;
            tea_score_master_data = get_val_empty(tea_score_master_data);
            var teach_research_score_master_data = data.teach_research_score_master;
            teach_research_score_master_data = get_val_empty(teach_research_score_master_data);
            var result_score_master_data = data.result_score_master;
            result_score_master_data = get_val_empty(result_score_master_data);
            var total_score_master_data = data.total_score_master;
            total_score_master_data = get_val_empty(total_score_master_data);
            var rate_stars_master_data = data.rate_stars_master;
            if(rate_stars_master_data>0){
                rate_stars_master_data=rate_stars_master_data+"星";
            }else{
                rate_stars_master_data="";
            }

            var title = "考核评定";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \" ><tr><td >内容</td><td>考核标准</td><td>分值</td><td>自评</td><td>自评总分</td><td>组长审定</td></tr><tr><td rowspan=\"5\" style=\"vertical-align: middle\">德育(10分)</td><td>遵纪守法,严格遵守公司各项规章制度,上班不迟到、不早退,无请假</td><td>2</td><td>"+data.observe_law_score+"</td><td rowspan=\"5\"  style=\"vertical-align: middle\">"+data.moral_education_score+"</td><td rowspan=\"5\"  style=\"vertical-align: middle\">"+moral_education_score_master_data+"</td></tr><tr><td>高度认同公司经营理念、企业文化和核心价值观</td><td>2</td><td>"+data.core_socialist_score+"</td></tr><tr><td>勇于承担工作责任,不发表消极言论,积极维护企业形象</td><td>2</td><td>"+data.work_responsibility_score+"</td></tr><tr><td>服从上级领导的安排,积极融入团队合作</td><td>2</td><td>"+data.obey_leadership_score+"</td></tr><tr><td>有高尚的师德,爱岗敬业</td><td>2</td><td>"+data.dedication_score+"</td></tr><tr><td rowspan=\"10\" style=\"vertical-align: middle\">教学(22分)</td><td>认真准备好每次课的教师讲义和学生讲义,并备好本节课学生作业</td><td>2</td><td>"+data.prepare_lesson_score+"</td><td rowspan=\"10\"  style=\"vertical-align: middle\">"+data.tea_score+"</td><td rowspan=\"10\"  style=\"vertical-align: middle\">"+tea_score_master_data+"</td></tr><tr><td>在上课前4小时备好本节课讲义和作业并上传至上课平台</td><td>2</td><td>"+data.upload_handouts_score+"</td></tr><tr><td>讲义编写需做到:备学生、备教材、备课堂</td><td>2</td><td>"+data.handout_writing_score+"</td></tr><tr><td>严格按照课程表上课,不私自调、停课</td><td>2</td><td>"+data.no_absences_score+"</td></tr><tr><td>按时上下课,不迟到、不早退</td><td>2</td><td>"+data.late_leave_score+"</td></tr><tr><td>精心上好每节课,重点突出,生动形象,有吸引力</td><td>2</td><td>"+data.prepare_quality_score+"</td></tr><tr><td>上课途中手机调静音,不打接电话,不私自离岗</td><td>2</td><td>"+data.class_concent_score+"</td></tr><tr><td>适时鼓励学生,无贬低、辱骂、抱怨学生现象</td><td>4</td><td>"+data.tea_attitude_score+"</td></tr><tr><td>课后及时对本课堂学生表现及教学效果进行评价和反馈</td><td>2</td><td>"+data.after_feedback_score+"</td></tr><tr><td>学生提交的作业及时进行讲评和订正</td><td>2</td><td>"+data.modify_homework_score+"</td></tr><tr><td rowspan=\"5\" style=\"vertical-align: middle\">教研(13分)</td><td>积极配合排课老师上好所接学生的试听课</td><td>2</td><td>"+data.teamwork_positive_score+"</td><td rowspan=\"5\"  style=\"vertical-align: middle\">"+data.teach_research_score+"</td><td rowspan=\"5\"  style=\"vertical-align: middle\">"+teach_research_score_master_data+"</td></tr><tr><td>每接一个试听课,都积极准备</td><td>2</td><td>"+data.test_lesson_prepare_score+"</td></tr><tr><td>积极承担教学组长布置任务,准备充分,效果好</td><td>2</td><td>"+data.undertake_actively_score+"</td></tr><tr><td>积极参与教学经验交流、分享活动,积极发挥以老带新作用</td><td>2</td><td>"+data.active_part_score+"</td></tr><tr><td>积极主动分享高质量教学讲义、教学视频</td><td>2</td><td>"+data.active_share_score+"</td></tr><tr><td rowspan=\"5\" style=\"vertical-align: middle\">成果(55分)</td><td>试用期转化率绩效:"+data.order_per+"分</td><td>20</td><td>"+data.order_per_score+"</td><td rowspan=\"5\"  style=\"vertical-align: middle\">"+data.result_score+"</td><td rowspan=\"5\"  style=\"vertical-align: middle\">"+result_score_master_data+"</td></tr><tr><td>家长评价:"+data.lesson_level+"星</td><td>5</td><td>"+data.lesson_level_score+"</td></tr><tr><td>试用期内月平均课时消耗:"+data.stu_lesson_total+"课时</td><td>25</td><td>"+data.stu_lesson_total_score+"</td></tr><tr><td>家长投诉和退费</td><td>5</td><td>"+data.complaint_refund_score+"</td></tr><tr><td colspan=\"2\">评定总分</td><td>100</td><td colspan=\"2\">"+data.total_score+"</td><td>"+total_score_master_data+"</td></tr><tr><td colspan=\"2\">评定星级</td><td>5星</td><td colspan=\"2\">"+data.rate_stars+"星</td><td>"+rate_stars_master_data+"</td></tr></table></div>");
            html_node.find(".input_score").bind('input', function(){
                var moral_education_score_master= parseInt(html_node.find("#moral_education_score_master").val());
                moral_education_score_master = get_val(moral_education_score_master);
                var tea_score_master= parseInt(html_node.find("#tea_score_master").val());
                tea_score_master = get_val(tea_score_master);
                var teach_research_score_master= parseInt(html_node.find("#teach_research_score_master").val());
                teach_research_score_master = get_val(teach_research_score_master);
                var result_score_master= parseInt(html_node.find("#result_score_master").val());
                result_score_master = get_val(result_score_master);
                var total_score_master = moral_education_score_master+teach_research_score_master+tea_score_master+result_score_master;
                html_node.find("#total_score_master").val(total_score_master);
                if(total_score_master >= 95){
                    var str ="5星";
                }else if(total_score_master >= 88){
                    var str ="4星";
                }else if(total_score_master >= 80){
                    var str ="3星";
                }else if(total_score_master >= 70){
                    var str ="2星";
                }else{
                    var str ="1星";
                }
                html_node.find("#rate_stars_master").val(str);
            });
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

            dlg.getModalDialog().css("width","1024px");
            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );

        });

    });

    var show_teacher_positive_require_info = function(data,main_flag){
        var id = data.positive_id;
        if(id <= 0){
            BootstrapDialog.alert("该老师还未提交转正申请!");
            return;
        }

        $.do_ajax( "/fulltime_teacher/get_fulltime_teacher_pisitive_require_info",{
            "id" :id,
        },function(resp){
            var data  = resp.data;
            var title = "转正申请审核";
            var html_node = $(
                "<div  id=\"div_table\" class='middle'>"
                    +"<table class=\"table table-bordered \" >"
                    +"<tr >"
                    +"<td>姓名</td><td>"+data.name+"</td>"
                    +"<td>部门</td><td>"+data.main_department_str+"</td>"
                    +"</tr>"
                    +"<tr >"
                    +"<td>职位</td><td>"+data.post_str+"</td>"
                    +"<td>邮箱</td><td>"+data.email+"</td>"
                    +"</tr>"
                    +"<tr >"
                    +"<td>入职时间</td><td>"+data.create_time_str+"</td>"
                    +"<td>目前教师等级</td><td>"+data.level_str+"</td>"
                    +"</tr>"
                    +"<tr >"
                    +"<td>转正时间</td><td>"+data.positive_time_str+"</td>"
                    +"<td>转正后教师等级</td><td>"+data.positive_level_str+"</td>"
                    +"</tr>"
                    +"<tr >"
                    +"<td>考核情况</td><td>考核星级</td><td colspan=\"2\">"+data.rate_stars+"星</td>"
                    +"</tr>"
                    +"<tr >"
                    +"<td>转正情况</td><td colspan=\"3\">"+data.positive_type_str+"</td>"
                    +"</tr>"
                    +"<tr >"
                    +"<td colspan=\"4\"  bgcolor=\"#F0F0F0\" >试用期综合评定</td></tr>"
                    +"<tr>"
                    +"<tr >"
                    +"<td >基础薪资</td><td colspan='3'><input style='width:100%' id='id_base_money' value='"+data.base_money+"'\></td></tr>"
                    +"<tr>"
                    +"<td height=\"200px\" >自我评定</td>"
                    +"<td colspan=\"3\" >"
                    +"<textarea rows=\"8\" cols=\"28\" style=\" width:100%; height:100%;\" id=\"id_self_assessment\" readonly>"+data.self_assessment+"</textarea>"
                    +"</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td >初审</td>"
                    +"<td colspan=\"3\" id='id_set_fulltime_teacher_positive_require'></td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td >终审</td>"
                    +"<td colspan=\"3\" id='id_set_fulltime_teacher_positive_require_master'></td>"
                    +"</tr>"
                    +"</table>"
                    +"</div>"
            );

            var url = "";
            var post_data = "";
            var select_html = "<select id=\"master_deal_flag\">"
                +"<option value=\"1\">同意</option>"
                +"<option value=\"2\">驳回</option>"
                +"</select>";

            var dlg = BootstrapDialog.show({
                title    : title,
                message  : html_node   ,
                closable : true,
                buttons  : [{
                    label: '返回',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                },{
                    label: '审核',
                    cssClass: 'btn-warning',
                    action: function(dialog) {

                        $.do_ajax(url,{
                            "id"               : id,
                            "master_deal_flag" : html_node.find("table").find("#master_deal_flag").val(),
                            "base_money"       : html_node.find("table").find("#id_base_money").val(),
                        });

                    }
                }],
                onshown:function(){
                    if(main_flag==1){
                        url = "/user_deal/fulltime_teacher_positive_require_deal_main_master";
                        $("#id_set_fulltime_teacher_positive_require").html(data.master_deal_flag_str);
                        $("#id_set_fulltime_teacher_positive_require_master").append(select_html);
                    }else{
                        url = "/user_deal/fulltime_teacher_positive_require_deal_master";
                        $("#id_set_fulltime_teacher_positive_require").append(select_html);
                    }
                }
            });

            dlg.getModalDialog().css("width","1024px");
        });
    }


    $(".set_fulltime_teacher_positive_require").on("click",function(){
        var opt_data=$(this).get_opt_data();
        show_teacher_positive_require_info(opt_data,0);
    });

    $(".set_fulltime_teacher_positive_require_master").on("click",function(){
        var opt_data=$(this).get_opt_data();
        show_teacher_positive_require_info(opt_data,1);
    });

    // $('.opt-change').set_input_change_event(load_data);
});
