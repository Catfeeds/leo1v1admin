/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_teacher_test_lesson_info.d.ts" />
function load_data(){
    $.reload_self_page ( {
		order_by_str: g_args.order_by_str,
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		teacherid:	$('#id_teacherid').val(),
		teacher_account:	$('#id_teacher_account').val(),
        teacher_money_type:	$('#id_teacher_money_type').val(),
        have_interview_teacher:	$('#id_have_interview_teacher').val(),
		subject:	$('#id_subject').val(),
		subject_num:	$('#id_subject_num').val(),
		teacher_subject:	$('#id_teacher_subject').val(),
		identity:	$('#id_identity').val(),
	    reference_teacherid:	$('#id_reference_teacherid').val(),
		is_new_teacher:	$('#id_is_new_teacher').val(),
		teacher_test_status:	$('#id_teacher_test_status').val(),
        grade_part_ex: $('#id_grade_part_ex').val()
    });
}

$(function(){
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
    Enum_map.append_option_list("teacher_money_type", $("#id_teacher_money_type") );
    Enum_map.append_option_list("subject", $("#id_subject") );
    Enum_map.append_option_list("subject", $("#id_teacher_subject") );
    Enum_map.append_option_list("identity", $("#id_identity") );
    Enum_map.append_option_list("boolean", $("#id_have_interview_teacher" ));
    Enum_map.append_option_list("grade_part_ex", $("#id_grade_part_ex"),false,[0,1,2,3]);

	$('#id_teacherid').val(g_args.teacherid);
	$('#id_grade_part_ex').val(g_args.grade_part_ex);
	$('#id_teacher_account').val(g_args.teacher_account);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_subject').val(g_args.subject);
	$('#id_subject_num').val(g_args.subject_num);
	$('#id_teacher_subject').val(g_args.teacher_subject);
	$('#id_identity').val(g_args.identity);
	$('#id_is_new_teacher').val(g_args.is_new_teacher);
	$('#id_have_interview_teacher').val(g_args.have_interview_teacher);
    $('#id_reference_teacherid').val(g_args.reference_teacherid);
    $('#id_teacher_test_status').val(g_args.teacher_test_status);

    $.admin_select_user($("#id_reference_teacherid"),"teacher",load_data);
    $.admin_select_user($("#id_teacherid"), "teacher", load_data);
    $.admin_select_user($("#id_teacher_account"), "interview_teacher", load_data);

    $(".regular_stu_num").on("click",function(){
        var teacherid = $(this).data("teacherid");
        if(teacherid > 0){
            var title = "学生详情";
            var html_node = $("<div id=\"div_table\"><div class=\"col-md-12\" id=\"div_grade\"><div class=\"col-md-2\">年级统计:</div></div><br><div class=\"col-md-12\" id=\"div_subject\"><div class=\"col-md-2\">科目统计:</div></div><br><br><br><table   class=\"table table-bordered \"><tr><td>id</td><td>名字</td><td>年级</td><td>科目</td><tr></table></div>");
            
            $.do_ajax('/tongji_ss/get_teacher_stu_info_new',{
                "teacherid" : teacherid
            },function(resp) {
                var userid_list   = resp.data;
                var grade_count   = resp.grade;
                var subject_count = resp.subject;
                for(var i in grade_count){
                    html_node.find("#div_grade").append("<div class=\"col-md-1\">"+i+":"+grade_count[i]+"</div>");
                }
                for(var i in subject_count){
                    html_node.find("#div_subject").append("<div class=\"col-md-1\">"+i+":"+subject_count[i]+"</div>");
                }

                /*html_node.prepend("<div class=\"col-md-12\"><div class=\"col-md-2\">年级统计:</div><div class=\"col-md-3\">小学:"+grade_count.primary+"</div><div class=\"col-md-3\">初中:"+grade_count.junior+"</div><div class=\"col-md-3\">高中:"+grade_count.senior+"</div></div><br><br><br>");*/
                
                $.each(userid_list,function(i,item){
                    var userid = item[0];
                    var name = item[1];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    html_node.find("table").append("<tr><td>"+userid+"</td><td>"+name+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
                });
            });

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

            dlg.getModalDialog().css("width","1024px");

        }
        
    });

    $(".opt-limit-plan-lesson").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;       
        var id_limit_plan_lesson_type = $("<select><option value=\"0\">未限制</option><option value=\"1\">一周限排1节</option><option value=\"3\">一周限排3节</option><option value=\"5\">一周限排5节</option></select>");

        var id_limit_plan_lesson_reason = $("<textarea/>");
       // Enum_map.append_option_list("limit_plan_lesson_type",id_limit_plan_lesson_type,true);
        var arr= [
            ["限制类型",id_limit_plan_lesson_type],
            ["限制原因",id_limit_plan_lesson_reason]
        ];        
        
        id_limit_plan_lesson_type.val(opt_data.limit_plan_lesson_type);
        $.show_key_value_table("排课限制", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/human_resource/set_teacher_limit_plan_lesson', {
                    "teacherid"          : opt_data.teacherid,
                    "limit_plan_lesson_type":id_limit_plan_lesson_type.val(),
                    "limit_plan_lesson_reason":id_limit_plan_lesson_reason.val()
                });
            }
        });
        
    });

    $(".test_lesson_num").on("click",function(){
        var teacherid = $(this).data("teacherid");
        if(teacherid > 0){
                                 

            var title = "今后三周试听课详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>学生</td><td>年级</td><td>科目</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_teacher_test_lesson_info_new',{
                "teacherid" : teacherid
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
                });
            });

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

            dlg.getModalDialog().css("width","1024px");

        }
        
    });

    $(".test_lesson_num_week").on("click",function(){
        var teacherid = $(this).data("teacherid");
        if(teacherid > 0){
            var title = "本周剩余试听课详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>学生</td><td>年级</td><td>科目</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_teacher_test_lesson_info_week',{
                "teacherid" : teacherid
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
                });
            });

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

            dlg.getModalDialog().css("width","1024px");

        }
        
    });

    if(adminid != 72 && adminid != 349){
        $(".id_account_teacher").hide();
    }

    $(".all_lesson").on("click",function(){
        var teacherid = $(this).data("teacherid");
        console.log(g_subject);
        if(teacherid > 0){
            var title = "试听课详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>学生</td><td>年级</td><td>科目</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_test_lesson_history_info',{
                "teacherid"  : teacherid,
                "subject"    : g_subject,
                "start_time" : g_start_time,
                "end_time"   : g_end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick     = item["nick"]
                    var time     = item["lesson_start_str"];
                    var subject  = item["subject_str"];
                    var grade    = item["grade_str"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
                });
            });

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

            dlg.getModalDialog().css("width","1024px");
        }
    });

    $(".success_lesson").on("click",function(){
        var teacherid = $(this).data("teacherid");
        var teacher_subject = $(this).data("subject");
        if(teacherid > 0){
            var title     = "试听成功详情";
            var html_node = $("<div id=\"div_table\"><table class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>学生</td><td>年级</td><td>科目</td><td>试听需求</td><td width=\"100px\">视频回放</td><td>咨询师回访记录</td><td>合同</td><td width=\"120px\">签约失败说明</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_test_lesson_history_success_info',{
                "teacherid" : teacherid,
                "subject"    : g_subject,
                "start_time":g_start_time,
                "end_time":g_end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    var rev = item["rev"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td><td>期待时间:"+item["stu_request_test_lesson_time"]+"<br>试听内容:"+item["stu_test_lesson_level_str"]+"<br>试听需求:"+item["stu_request_test_lesson_demand"]+"<br>教材:"+item["editionid_str"]+"<br>学生成绩:"+item["stu_score_info"]+"<br>学生性格:"+item["stu_character_info"]+"<br>试卷:"+item["stu_test_paper_flag_str"]+"</td><td><a href=\"http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(item["draw_url"])+"&audio="+encodeURIComponent(item["audio_url"])+"&start="+item["real_begin_time"]+" \" target=\"_blank\">点击回放</a><br><br><br><a class=\"url_class\" data-subject=\""+item["subject"]+"\" data-time=\""+item["lesson_start"]+"\" data-grade=\""+item["grade"]+"\" data-url=\"http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(item["draw_url"])+"&audio="+encodeURIComponent(item["audio_url"])+"&start="+item["real_begin_time"]+"\">推荐视频</a><br><br><br><a class=\"add_record\" data-lessonid=\""+lessonid+"\" data-teacherid=\""+teacherid+"\">反馈</a></td><td>"+rev+"</td><td>"+item["have_order"]+"</td><td>"+item["test_lesson_order_fail_desc"]+"</td></tr>");
                });
                html_node.find("table").find(".url_class").each(function(){
                    $(this).on("click",function(){
                        var url = $(this).data("url");
                        var grade = $(this).data("grade");
                        var lesson_start = $(this).data("time");
                        var video_subject = $(this).data("subject");
                        console.log(grade);
                        var id_subject=$("<select/>");        
                        var id_grade_part_ex=$("<select/>");        
                        var id_identity=$("<select/>");        
                        var id_create_time=$("<select><option value=\"-1\">全部</option><option value=\"1\">入职一周</option><option value=\"2\">入职一个月</option></select>");
                        var id_tea_qua=$("<select><option value=\"-1\">全部</option><option value=\"1\">已冻结</option><option value=\"2\">已限课</option><option value=\"3\">已反馈</option></select>");
                        var id_tra=$("<select><option value=\"-1\">全部</option><option value=\"1\">高于25%</option><option value=\"2\">低于10%</option><option value=\"3\">10% - 25%</option></select>");
                        var id_send_reason = $("<textarea />");
                        var id_class_content = $("<textarea />");
                        var id_teacherid = $("<input />");

                        Enum_map.append_option_list("subject", id_subject);
                        Enum_map.append_option_list("grade_part_ex", id_grade_part_ex);
                        Enum_map.append_option_list("identity", id_identity);
                        var arr=[
                            ["老师科目", id_subject],
                            ["老师年级", id_grade_part_ex],
                            ["老师类型", id_identity],
                            ["入职情况", id_create_time],
                            ["教学质量", id_tea_qua],
                            ["转化率", id_tra],
                            ["推荐理由", id_send_reason],
                            ["上课内容", id_class_content],
                            ["老师（可不选）", id_teacherid]
                        ];
                        id_subject.val(teacher_subject);
                        $.show_key_value_table("请选择推荐的老师", arr ,{
                            label    : '确认',
                            cssClass : 'btn-warning',
                            action   : function(dialog) {
                                $.do_ajax( '/ss_deal/send_video_url_to_teacher', {
                                    "subject"                : id_subject.val(),
                                    "identity"               : id_identity.val(),
                                    "teacherid"              : teacherid,
                                    "url"                    : url,
                                    "create_time"            : id_create_time.val(),
                                    "tea_qua"                : id_tea_qua.val(),
                                    "tra"                    : id_tra.val(),
                                    "grade_part_ex"          : id_grade_part_ex.val(),
                                    "send_reason"            : id_send_reason.val(),
                                    "class_content"          : id_class_content.val(),
                                    "send_teacherid"         : id_teacherid.val(),
                                    "grade"                  : grade,
                                    "lesson_start"           : lesson_start,
                                    "video_subject"          : video_subject
                                });
                            }
                        },function(){
                            $.admin_select_user(id_teacherid,"teacher");
                        });


                    });
                    
                });
                
                html_node.find("table").find(".add_record").each(function(){
                    $(this).on("click",function(){
                        var lessonid = $(this).data("lessonid");
                        var lessonid_list = "["+lessonid+"]";
                        //alert(lessonid_list);
                        var teacherid = $(this).data("teacherid");
                        var id_have_kj =  $("<div><span >类型:</span><select id=\"teacher_have_kj\"><option value=\"1\" selected>有无基本上课讲义</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_have_kj_score\" class=\"class_score\" /></div></div>");
                        var id_bk_pp =  $("<div><span >类型:</span><select id=\"teacher_bk_pp\"><option value=\"0\">请选择</option><option value=\"1\">匹配度极差</option><option value=\"2\">匹配度一般</option><option value=\"3\">匹配度良好</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_bk_pp_score\" class=\"class_score\" /></div></div>");
                        var id_kj_zl =  $("<div><span >类型:</span><select id=\"teacher_kj_zl\"><option value=\"0\">请选择</option><option value=\"1\">课件内容层次不清，逻辑混乱</option><option value=\"2\">课件内容层次基本合理，符合教学逻辑</option><option value=\"3\">课件内容层次清晰，难度上循序渐进，重点突出</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kj_zl_score\" class=\"class_score\" /></div></div>");
                        var id_tea_pro =  $("<div><span >类型:</span><select id=\"teacher_tea_pro\"><option value=\"0\">请选择</option><option value=\"1\">单纯讲练习，缺少相应技巧和知识点讲解</option><option value=\"2\">知识点讲解过多，缺少对应练习和方法技巧归纳</option><option value=\"3\">方法技巧、知识点讲解与对应练习比例得当，课程系统性良好</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_tea_pro_score\" class=\"class_score\" class=\"class_score\" /></div></div>");
                        var id_kt_fw =  $("<div><span >类型:</span><select id=\"teacher_kt_fw\"><option value=\"0\">请选择</option><option value=\"1\">填鸭式教学，鲜少询问学生接受情况</option><option value=\"2\">有互动，但互动方式和引导时机把握不合理，课堂氛围枯燥平淡</option><option value=\"3\">教师引导积极，师生互动紧密，课堂氛围融洽</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kt_fw_score\" class=\"class_score\" /></div></div>");
                        var id_bs =  $("<div><span >类型:</span><select id=\"teacher_bs\"><option value=\"0\">请选择</option><option value=\"1\">必要板书缺乏，圈画标示过少</option><option value=\"2\">有板书圈画书写，但书写堆砌凌乱，影响教学专业性</option><option value=\"3\">板书规范性良好，内容详实，要点清晰</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_bs_score\" class=\"class_score\" /></div></div>");
                        var id_kcjz =  $("<div><span >类型:</span><select id=\"teacher_kcjz\"><option value=\"0\">请选择</option><option value=\"1\">讲课节奏过慢</option><option value=\"2\">讲课节奏过快</option><option value=\"3\">讲课节奏适中</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kcjz_score\" class=\"class_score\" /></div></div>");
                        var id_jtff =  $("<div><span >类型:</span><select id=\"teacher_jtff\"><option value=\"1\" selected>讲题方法思路正误</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_jtff_score\" class=\"class_score\" /></div></div>");
                        var id_zsd =  $("<div><span >类型:</span><select id=\"teacher_zsd\"><option value=\"1\" selected>知识点讲解正误</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_zsd_score\" class=\"class_score\" /></div></div>");
                        var id_znd =  $("<div><span >类型:</span><select id=\"teacher_znd\"><option value=\"1\" selected>重难点把握是否到位</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_znd_score\" class=\"class_score\" /></div></div>");
                        var id_kbnr =  $("<div><span >类型:</span><select id=\"teacher_kbnr\"><option value=\"1\" selected>课本内容是否熟悉</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kbnr_score\" class=\"class_score\" /></div></div>");
                        var id_tmjd =  $("<div><span >类型:</span><select id=\"teacher_tmjd\"><option value=\"1\" selected>题目解答正误</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_tmjd_score\" class=\"class_score\" /></div></div>");
                        var id_yy =  $("<div><span >类型:</span><select id=\"teacher_yy\"><option value=\"0\">请选择</option><option value=\"1\">语言表达能力差，表述不清</option><option value=\"2\">语言表达尚可，但语言组织能力平庸，欠缺感染力</option><option value=\"3\">语言能力良好，讲解生动形象，富有感染力</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_yy_score\" class=\"class_score\" /></div></div>");
                        var id_jxtd =  $("<div><span >类型:</span><select id=\"teacher_jxtd\"><option value=\"0\">请选择</option><option value=\"1\">教学态度恶劣，侮辱谩骂学生，打击学生自信心</option><option value=\"2\">教学态度散漫随意，如疲态明显，哈欠连天或课堂随意嬉笑等</option><option value=\"3\">教学态度一般，无上课激情或带敷衍态度</option><option value=\"4\">教学态度基本端正，认真负责</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_jxtd_score\" class=\"class_score\" /></div></div>");
                        var id_jxzzd =  $("<div><span >类型:</span><select id=\"teacher_jxzzd\"><option value=\"0\">请选择</option><option value=\"1\">讲课过程中从事教学无关事务如吃东西、 接打电话、 闲聊等</option><option value=\"2\">讲课过程大量留白，延迟回答学生问题、无故让学生等待耽误上课时间</option><option value=\"3\">教学状态良好，课堂专注力佳</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_jxzzd_score\" class=\"class_score\" /></div></div>");
                        var id_jxsg =  $("<div><span >类型:</span><select id=\"teacher_jxsg\"><option value=\"0\">请选择</option><option value=\"1\">推荐其他机构，贬低公司价值</option><option value=\"2\">课件全程无理优logo或使用明显带有其他机构logo的资料</option><option value=\"3\">议论其他员工、泄露公司相关信息</option><option value=\"4\">课程顺利完成，无相关教学事故</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_jxsg_score\" class=\"class_score\" /></div></div>");
                        var id_rjcz =  $("<div><span >类型:</span><select id=\"teacher_rjcz\"><option value=\"0\">请选择</option><option value=\"1\">讲义无截图，纯拍照上传</option><option value=\"2\">讲义截图不清晰且放置位置不合理</option><option value=\"3\">讲义截图清晰但放置位置不合理</option><option value=\"4\">截图清晰且位置放置合理</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_rjcz_score\" class=\"class_score\" /></div></div>");
                        var id_kcyc =  $("<div><span >类型:</span><select id=\"teacher_kcyc\"><option value=\"0\">请选择</option><option value=\"1\">课中遇到网络卡断，音频问题，异常闪退或课程延迟时，慌乱抱怨</option><option value=\"2\">面对课程异常情况，虽有着手处理但处理过于缓慢，耽误上课时间</option><option value=\"3\">面对异常情况，及时冷静处理，顺利解决</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kcyc_score\" class=\"class_score\" /></div></div>");
                        var id_hj =  $("<div><span >类型:</span><select id=\"teacher_hj\"><option value=\"0\">请选择</option><option value=\"1\">教学环境嘈杂；网络音频状况不佳，影响课程体验</option><option value=\"2\">教学环境安静，教学设备状况调试良好</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_hj_score\" class=\"class_score\" /></div></div>");
                        
                        var id_sshd=$("<label><input name=\"Fruit\" type=\"checkbox\" value=\"1\" />鼓励发言 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"2\" />善于引导 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"3\" />提问形式多样 </label><label><input name=\"Fruit\" type=\"checkbox\" value=\"4\" />关注度高 </label>");
                        var id_sshd2=$("<label><input name=\"dog\" type=\"checkbox\" value=\"5\" />空话套话过多 </label> <label><input name=\"dog\" type=\"checkbox\" value=\"6\" />Yes/No问题过多 </label> <label><input name=\"dog\" type=\"checkbox\" value=\"7\" />提问形式单一 </label> <label><input name=\"dog\" type=\"checkbox\" value=\"8\" />关注度低 </label> ");

                        var id_ktfw=$("<label><input name=\"ktfw\" type=\"checkbox\" value=\"1\" />语速均匀 </label> <label><input name=\"ktfw\" type=\"checkbox\" value=\"2\" />轻松愉快 </label> <label><input name=\"ktfw\" type=\"checkbox\" value=\"3\" />节奏紧凑 </label> <label><input name=\"ktfw\" type=\"checkbox\" value=\"4\" />生动有趣 </label><label><input name=\"ktfw\" type=\"checkbox\" value=\"5\" />思路清晰</label> ");
                        var id_ktfw2=$("<label><input name=\"kt\" type=\"checkbox\" value=\"6\" />语速过慢/过快 </label> <label><input name=\"kt\" type=\"checkbox\" value=\"7\" />语调沉闷 </label> <label><input name=\"kt\" type=\"checkbox\" value=\"8\" />节奏拖沓 </label><label><input name=\"kt\" type=\"checkbox\" value=\"9\" />枯燥乏味 </label><label><input name=\"kt\" type=\"checkbox\" value=\"10\" />思路混乱 </label>  ");
                        var id_skgf=$("<label><input name=\"skgf\" type=\"checkbox\" value=\"1\" />考纲熟悉 </label> <label><input name=\"skgf\" type=\"checkbox\" value=\"2\" />软件使用熟练 </label> <label><input name=\"skgf\" type=\"checkbox\" value=\"3\" />讲义精美</label><label><input name=\"skgf\" type=\"checkbox\" value=\"4\" />截图合理 </label> <label><input name=\"skgf\" type=\"checkbox\" value=\"5\" />板书规范 </label><label><input name=\"skgf\" type=\"checkbox\" value=\"6\" />普通话标准 </label> ");
                        var id_skgf2=$("<label><input name=\"sk\" type=\"checkbox\" value=\"7\" />考纲不熟悉 </label> <label><input name=\"sk\" type=\"checkbox\" value=\"8\" />软件使用生疏 </label> <label><input name=\"sk\" type=\"checkbox\" value=\"9\" />讲义凌乱 </label><label><input name=\"sk\" type=\"checkbox\" value=\"10\" />截图不合理 </label><label><input name=\"sk\" type=\"checkbox\" value=\"11\" />板书不规范 </label> <label><input name=\"sk\" type=\"checkbox\" value=\"12\" />有口音 </label> ");
                        var id_jsfg=$("<label><input name=\"jsfg\" type=\"checkbox\" value=\"1\" />平易近人 </label> <label><input name=\"jsfg\" type=\"checkbox\" value=\"2\" />生动活泼</label> <label><input name=\"jsfg\" type=\"checkbox\" value=\"3\" />幽默风趣 </label> <label><input name=\"jsfg\" type=\"checkbox\" value=\"4\" />严谨认真 </label> ");
                        var id_jsfg2=$("<label><input name=\"js\" type=\"checkbox\" value=\"5\" />咄咄逼人</label> <label><input name=\"js\" type=\"checkbox\" value=\"6\" />沉闷乏味 </label> <label><input name=\"js\" type=\"checkbox\" value=\"7\" />缺乏课堂主导性 </label><label><input name=\"js\" type=\"checkbox\" value=\"8\" />散漫随性 </label>  ");


                        Enum_map.append_option_list("teacher_lecture_score",id_have_kj.find("#teacher_have_kj_score"),false,[0,1,2,3,4,5]);
                        Enum_map.append_option_list("teacher_lecture_score",id_jtff.find("#teacher_jtff_score"),false,[0,1,2,3,4,5]);
                        Enum_map.append_option_list("teacher_lecture_score",id_zsd.find("#teacher_zsd_score"),false,[0,1,2,3,4,5]);
                        Enum_map.append_option_list("teacher_lecture_score",id_znd.find("#teacher_znd_score"),false,[0,1,2,3,4,5]);
                        Enum_map.append_option_list("teacher_lecture_score",id_kbnr.find("#teacher_kbnr_score"),false,[0,1,2,3,4,5]);
                        Enum_map.append_option_list("teacher_lecture_score",id_tmjd.find("#teacher_tmjd_score"),false,[0,1,2,3,4,5]);
                        Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"));
                        Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"));
                        Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"));
                        Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"));
                        Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"));
                        Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"));
                        Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"));
                        Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);
                        Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);
                        Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"));
                        Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"));
                        Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30]);
                        Enum_map.append_option_list("test_lesson_score",id_hj.find("#teacher_hj_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
                        var id_score = $("<input readonly/>");
                        var id_rank = $("<input readonly/>");
                        var id_record = $("<textarea />");
                        var id_jkqk = $("<textarea />");

                        var arr=[
                            ["有无课件", id_have_kj],
                            ["备课内容与试听需求匹配", id_bk_pp],
                            ["课件质量", id_kj_zl],
                            ["教学过程设计", id_tea_pro],
                            ["课堂氛围", id_kt_fw],
                            ["板书书写", id_bs],
                            ["课程节奏", id_kcjz],
                            ["讲题方法思路", id_jtff],
                            ["知识点讲解", id_zsd],
                            ["重难点把握", id_znd],
                            ["课本内容熟悉程度", id_kbnr],
                            ["题目解答", id_tmjd],
                            ["语言表达和组织能力", id_yy],
                            ["教学态度", id_jxtd],
                            ["教学专注度", id_jxzzd],
                            ["教学事故", id_jxsg],
                            ["软件操作", id_rjcz],
                            ["课程异常情况处理", id_kcyc],
                            ["周边环境", id_hj],
                            ["总分",id_score],
                            ["等级",id_rank],
                            ["监课情况",id_jkqk],
                            ["意见或建议",id_record],
                            ["标签-师生互动(好)",id_sshd],
                            ["标签-师生互动(不好)",id_sshd2],
                            ["标签-课堂氛围(好)",id_ktfw],
                            ["标签-课堂氛围(不好)",id_ktfw2],
                            ["标签-授课规范(好)",id_skgf],
                            ["标签-授课规范(不好)",id_skgf2],
                            ["标签-教师风格(好)",id_jsfg],
                            ["标签-教师风格(不好)",id_jsfg2]
                        ];
                        
                        id_bk_pp.find("#teacher_bk_pp").on("change",function(){
                            if($(this).val() == 1){
                                id_bk_pp.find("#teacher_bk_pp_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"),false,[3,4,5]);
                            }else if($(this).val() == 2){
                                id_bk_pp.find("#teacher_bk_pp_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"),false,[6,7]);
                            }else if($(this).val() == 3){
                                id_bk_pp.find("#teacher_bk_pp_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"),false,[8,9,10]);
                            }else{
                                id_bk_pp.find("#teacher_bk_pp_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"));
                            }

                        });
                        id_kj_zl.find("#teacher_kj_zl").on("change",function(){
                            if($(this).val() == 1){
                                id_kj_zl.find("#teacher_kj_zl_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"),false,[3,4,5]);
                            }else if($(this).val() == 2){
                                id_kj_zl.find("#teacher_kj_zl_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"),false,[6,7]);
                            }else if($(this).val() == 3){
                                id_kj_zl.find("#teacher_kj_zl_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"),false,[8,9,10]);
                            }else{
                                id_kj_zl.find("#teacher_kj_zl_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"));
                            }

                        });
                        id_tea_pro.find("#teacher_tea_pro").on("change",function(){
                            if($(this).val() == 1){
                                id_tea_pro.find("#teacher_tea_pro_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"),false,[4,5,6]);
                            }else if($(this).val() == 2){
                                id_tea_pro.find("#teacher_tea_pro_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"),false,[4,5,6]);
                            }else if($(this).val() == 3){
                                id_tea_pro.find("#teacher_tea_pro_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"),false,[7,8,9,10]);
                            }else{
                                id_tea_pro.find("#teacher_tea_pro_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"));
                            }
                        });
                        id_kt_fw.find("#teacher_kt_fw").on("change",function(){
                            if($(this).val() == 1){
                                id_kt_fw.find("#teacher_kt_fw_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"),false,[3,4,5]);
                            }else if($(this).val() == 2){
                                id_kt_fw.find("#teacher_kt_fw_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"),false,[6,7]);
                            }else if($(this).val() == 3){
                                id_kt_fw.find("#teacher_kt_fw_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"),false,[8,9,10]);
                            }else{
                                id_kt_fw.find("#teacher_kt_fw_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"));
                            }

                        });
                        id_bs.find("#teacher_bs").on("change",function(){
                            if($(this).val() == 1){
                                id_bs.find("#teacher_bs_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"),false,[3,4,5]);
                            }else if($(this).val() == 2){
                                id_bs.find("#teacher_bs_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"),false,[6,7]);
                            }else if($(this).val() == 3){
                                id_bs.find("#teacher_bs_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"),false,[8,9,10]);
                            }else{
                                id_bs.find("#teacher_bs_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"));
                            }

                        });
                        id_kcjz.find("#teacher_kcjz").on("change",function(){
                            if($(this).val() == 1){
                                id_kcjz.find("#teacher_kcjz_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"),false,[4,5,6]);
                            }else if($(this).val() == 2){
                                id_kcjz.find("#teacher_kcjz_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"),false,[4,5,6]);
                            }else if($(this).val() == 3){
                                id_kcjz.find("#teacher_kcjz_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"),false,[7,8,9,10]);
                            }else{
                                id_kcjz.find("#teacher_kcjz_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"));
                            }

                        });
                        id_yy.find("#teacher_yy").on("change",function(){
                            if($(this).val() == 1){
                                id_yy.find("#teacher_yy_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"),false,[3,4,5]);
                            }else if($(this).val() == 2){
                                id_yy.find("#teacher_yy_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"),false,[6,7]);
                            }else if($(this).val() == 3){
                                id_yy.find("#teacher_yy_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"),false,[8,9,10]);
                            }else{
                                id_yy.find("#teacher_yy_score").find("option").remove(); 
                                Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"));
                            }

                        });
                        id_jxtd.find("#teacher_jxtd").on("change",function(){
                            if($(this).val() == 1){
                                id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),true,[0]);
                            }else if($(this).val() == 2){
                                id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[5,6,7,8,9,10]);
                            }else if($(this).val() == 3){
                                id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[10,15,20]);
                            }else if($(this).val() == 4){
                                id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[20,21,22,23,24,25]);

                            }else{
                                id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);
                            }

                        });
                        id_jxzzd.find("#teacher_jxzzd").on("change",function(){
                            if($(this).val() == 1){
                                id_jxzzd.find("#teacher_jxzzd_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[5,6,7,8,9,10]);
                            }else if($(this).val() == 2){
                                id_jxzzd.find("#teacher_jxzzd_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[10,15,20]);      
                            }else if($(this).val() == 3){
                                id_jxzzd.find("#teacher_jxzzd_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[20,21,22,23,24,25]);          
                            }else{
                                id_jxzzd.find("#teacher_jxzzd_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);
                                
                            }

                        });
                        id_jxsg.find("#teacher_jxsg").on("change",function(){
                            if($(this).val() == 1){
                                id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"),true,[0]);
                            }else if($(this).val() == 2){
                                id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"),false,[10,15,20]);
                            }else if($(this).val() == 3){
                                id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"),false,[20,25,30,35,40]);
                            }else if($(this).val() == 4){
                                id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"),true,[50]);
                            }else{
                                id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"));
                            }

                        });
                        id_rjcz.find("#teacher_rjcz").on("change",function(){
                            if($(this).val() == 1){
                                id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"),false,[0,1,2,3,4,5,6,7,8,9,10]);
                            }else if($(this).val() == 2){
                                id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"),false,[10,15,20]);     
                            }else if($(this).val() == 3){
                                id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"),false,[20,25,30,35,40]);         
                            }else if($(this).val() == 4){
                                id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"),false,[40,45,50]);         
                            }else{
                                id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"));
                                
                            }

                        });

                        id_kcyc.find("#teacher_kcyc").on("change",function(){
                            if($(this).val() == 1){
                                id_kcyc.find("#teacher_kcyc_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[5,6,7,8,9,10]);
                            }else if($(this).val() == 2){
                                id_kcyc.find("#teacher_kcyc_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[10,15,20]);
                            }else if($(this).val() == 3){
                                id_kcyc.find("#teacher_kcyc_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[20,25,30]);
                            }else{
                                id_kcyc.find("#teacher_kcyc_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30]);
                            }

                        });
                        id_hj.find("#teacher_hj").on("change",function(){
                            if($(this).val() == 1){
                                id_hj.find("#teacher_hj_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_hj.find("#teacher_hj_score"),false,[5,6,7,8,9,10]);
                            }else if($(this).val() == 2){
                                id_hj.find("#teacher_hj_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_hj.find("#teacher_hj_score"),false,[10,15,20]);
                            }else{
                                id_hj.find("#teacher_hj_score").find("option").remove(); 
                                Enum_map.append_option_list("test_lesson_score",id_hj.find("#teacher_hj_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
                            }

                        });

                        $.show_key_value_table("试听评价", arr,{
                            label    : '确认',
                            cssClass : 'btn-warning',
                            action   : function(dialog) {
                                var record_info = id_record.val();
                                if(record_info==""){
                                    BootstrapDialog.alert("请填写评价内容!");
                                    return ;
                                }
                                console.log(record_info.length);
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
                                if(sshd_bad.length==0 && sshd_good.length==0){
                                    BootstrapDialog.alert("请选择老师标签");
                                    return false;
                                }
                                var ktfw_good=[];
                                id_ktfw.find("input:checkbox[name='ktfw']:checked").each(function(i) {
                                    ktfw_good.push($(this).val());
                                });
                                var ktfw_bad=[];
                                id_ktfw2.find("input:checkbox[name='kt']:checked").each(function(i) {
                                    ktfw_bad.push($(this).val());
                                });
                                if(ktfw_bad.length==0 && ktfw_good.length==0){
                                    BootstrapDialog.alert("请选择老师标签");
                                    return false;
                                }

                                var skgf_good=[];
                                id_skgf.find("input:checkbox[name='skgf']:checked").each(function(i) {
                                    skgf_good.push($(this).val());
                                });
                                var skgf_bad=[];
                                id_skgf2.find("input:checkbox[name='sk']:checked").each(function(i) {
                                    skgf_bad.push($(this).val());
                                });
                                if(skgf_bad.length==0 && skgf_good.length==0){
                                    BootstrapDialog.alert("请选择老师标签");
                                    return false;
                                }

                                var jsfg_good=[];
                                id_jsfg.find("input:checkbox[name='jsfg']:checked").each(function(i) {
                                    jsfg_good.push($(this).val());
                                });
                                var jsfg_bad=[];
                                id_jsfg2.find("input:checkbox[name='js']:checked").each(function(i) {
                                    jsfg_bad.push($(this).val());
                                });
                                if(jsfg_bad.length==0 && jsfg_good.length==0){
                                    BootstrapDialog.alert("请选择老师标签");
                                    return false;
                                }

                                $.do_ajax("/human_resource/set_teacher_record_info",{
                                    "teacherid"    : teacherid,
                                    "type"         : 1,
                                    "courseware_flag"              : id_have_kj.find("#teacher_have_kj").find("option:selected").text(),
                                    "courseware_flag_score"        : id_have_kj.find("#teacher_have_kj_score").val(),
                                    "lesson_preparation_content"   : id_bk_pp.find("#teacher_bk_pp").find("option:selected").text(),
                                    "lesson_preparation_content_score"   : id_bk_pp.find("#teacher_bk_pp_score").val(),        
                                    "courseware_quality"          : id_kj_zl.find("#teacher_kj_zl").find("option:selected").text(),
                                    "courseware_quality_score"    : id_kj_zl.find("#teacher_kj_zl_score").val(),
                                    "tea_process_design"          : id_tea_pro.find("#teacher_tea_pro").find("option:selected").text(),
                                    "tea_process_design_score"    : id_tea_pro.find("#teacher_tea_pro_score").val(),     
                                    "class_atm"                   : id_kt_fw.find("#teacher_kt_fw").find("option:selected").text(),
                                    "class_atm_score"             : id_kt_fw.find("#teacher_kt_fw_score").val(),     
                                    "knw_point"                   : id_zsd.find("#teacher_zsd").find("option:selected").text(),
                                    "knw_point_score"             : id_zsd.find("#teacher_zsd_score").val(),     
                                    "dif_point"                   : id_znd.find("#teacher_znd").find("option:selected").text(),
                                    "dif_point_score"             : id_znd.find("#teacher_znd_score").val(),     
                                    "teacher_blackboard_writing"         : id_bs.find("#teacher_bs").find("option:selected").text(),
                                    "teacher_blackboard_writing_score"   : id_bs.find("#teacher_bs_score").val(),     
                                    "tea_rhythm"             : id_kcjz.find("#teacher_kcjz").find("option:selected").text(),
                                    "tea_rhythm_score"       : id_kcjz.find("#teacher_kcjz_score").val(),     
                                    "language_performance"       : id_yy.find("#teacher_yy").find("option:selected").text(),
                                    "language_performance_score" : id_yy.find("#teacher_yy_score").val(),
                                    "content_fam_degree"       : id_kbnr.find("#teacher_kbnr").find("option:selected").text(),
                                    "content_fam_degree_score" : id_kbnr.find("#teacher_kbnr_score").val(),     
                                    "answer_question_cre"       : id_tmjd.find("#teacher_tmjd").find("option:selected").text(),
                                    "answer_question_cre_score" : id_tmjd.find("#teacher_tmjd_score").val(),     
                                    "tea_attitude"       : id_jxtd.find("#teacher_jxtd").find("option:selected").text(),
                                    "tea_attitude_score" : id_jxtd.find("#teacher_jxtd_score").val(),
                                    "tea_method"       : id_jtff.find("#teacher_jtff").find("option:selected").text(),
                                    "tea_method_score" : id_jtff.find("#teacher_jtff_score").val(),
                                    "tea_concentration"       : id_jxzzd.find("#teacher_jxzzd").find("option:selected").text(),
                                    "tea_concentration_score" : id_jxzzd.find("#teacher_jxzzd_score").val(),
                                    "tea_accident"       : id_jxsg.find("#teacher_jxsg").find("option:selected").text(),
                                    "tea_accident_score" : id_jxsg.find("#teacher_jxsg_score").val(),     
                                    "tea_operation"                  : id_rjcz.find("#teacher_rjcz").find("option:selected").text(),
                                    "tea_operation_score"            : id_rjcz.find("#teacher_rjcz_score").val(),     
                                    "tea_environment"                : id_hj.find("#teacher_hj").find("option:selected").text(),
                                    "tea_environment_score"          : id_hj.find("#teacher_hj_score").val(),     
                                    "record_score"              : id_score.val(),
                                    "class_abnormality"              : id_kcyc.find("#teacher_kcyc").find("option:selected").text(),
                                    "class_abnormality_score"        : id_kcyc.find("#teacher_kcyc_score").val(), 
                                    "record_info"                    : id_record.val(),
                                    "record_monitor_class"           :id_jkqk.val(),
                                    "record_rank"                    :id_rank.val(),
                                    "record_lessonid_list"           :JSON.stringify(lessonid_list),
                                    "sshd_good"                          :JSON.stringify(sshd_good),
                                    "sshd_bad"                           :JSON.stringify(sshd_bad),
                                    "ktfw_good"                          :JSON.stringify(ktfw_good),
                                    "ktfw_bad"                           :JSON.stringify(ktfw_bad),
                                    "skgf_good"                          :JSON.stringify(skgf_good),
                                    "skgf_bad"                           :JSON.stringify(skgf_bad),
                                    "jsfg_good"                          :JSON.stringify(jsfg_good),
                                    "jsfg_bad"                           :JSON.stringify(jsfg_bad),
                                });
                            }
                        },function(){
                            id_score.attr("placeholder","满分100分");
                            id_record.attr("placeholder","字数不能超过150字");
                        });

                        //console.log(arr[0][1]);
                        arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
                            id_score.val(parseInt(0.6*(parseInt(id_bk_pp.find("#teacher_bk_pp_score").val())+parseInt(id_have_kj.find("#teacher_have_kj_score").val())+parseInt(id_kj_zl.find("#teacher_kj_zl_score").val())+parseInt(id_tea_pro.find("#teacher_tea_pro_score").val())+parseInt(id_kt_fw.find("#teacher_kt_fw_score").val())+parseInt(id_bs.find("#teacher_bs_score").val())+parseInt(id_kcjz.find("#teacher_kcjz_score").val())+parseInt(id_jtff.find("#teacher_jtff_score").val())+parseInt(id_zsd.find("#teacher_zsd_score").val())+parseInt(id_znd.find("#teacher_znd_score").val())+parseInt(id_kbnr.find("#teacher_kbnr_score").val())+parseInt(id_tmjd.find("#teacher_tmjd_score").val())+parseInt(id_yy.find("#teacher_yy_score").val()))+0.3*(parseInt(id_jxtd.find("#teacher_jxtd_score").val())+parseInt(id_jxzzd.find("#teacher_jxzzd_score").val())+parseInt(id_jxsg.find("#teacher_jxsg_score").val()))+0.1*(parseInt(id_rjcz.find("#teacher_rjcz_score").val())+parseInt(id_kcyc.find("#teacher_kcyc_score").val())+parseInt(id_hj.find("#teacher_hj_score").val()))));
                            if(id_score.val()>90 && id_score.val() <= 100){
                                id_rank.val("S");
                            }else if(id_score.val()>80 && id_score.val()<=90){
                                id_rank.val("A");
                            }else if(id_score.val()>70 && id_score.val()<=80){
                                id_rank.val("B");
                            }else{
                                id_rank.val("C");
                            }
                        });
                        arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("width",970);
                        arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("left",-200);

                    });
                    
                });

            });

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

            dlg.getModalDialog().css("width","1024px");

        }
        
    });

    $.each( $(".teacher_lesson_count_total"), function(i,item ){
        var teacherid = $(this).data("teacherid");
        if(teacherid > 0){
            $(item).admin_select_teacher_free_time_new({
                "teacherid" : teacherid
            });
        }
    });

    $.each($(".title_level_str"),function(){
        var aa = $(this).text();
        if(aa==" "   && g_tea_subject >0){
            $(this).parent().hide();
        }
        if(aa==" " && tea_right==2){
            $(this).parent().hide();
        }
        if(aa==" "){
            $(this).parent().find(".opt-teacher-freeze").hide();
            $(this).parent().find(".opt-set-teacher-record-new").hide();
            $(this).parent().find(".opt-limit-plan-lesson").hide();
        }
    });


    $(".opt-teacher-freeze").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var is_freeze = opt_data.is_freeze;
        if(is_freeze == 0){
            var id_freeze_reason=$("<textarea/>");        
            var arr=[
                ["冻结理由", id_freeze_reason]
            ];
            $.show_key_value_table("请输入冻结理由", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    $.do_ajax( '/ss_deal/update_freeze_teacher_info', {
                        "teacherid"     : teacherid,
                        "is_freeze"     : is_freeze,
                        "freeze_reason" : id_freeze_reason.val()
                    });
                }
            });
        }else{
            BootstrapDialog.confirm("确定要解除冻结？", function(val){
                if (val) {
                    $.do_ajax( '/ss_deal/update_freeze_teacher_info', {
                        'teacherid' : teacherid,
                        "is_freeze" : is_freeze,
                    });
                } 
            });
        }
    });

    $(".opt-set-teacher-record").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;

        var id_score  = $("<input/>");
        var id_record = $("<textarea style=\"width:350px; height:200px\" />");

        var arr=[
            ["评分",id_score],
            ["老师反馈",id_record]
        ];
        
        $.show_key_value_table("请对老师近期表现进行反馈", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var record_score = id_score.val();
                if(isNaN(record_score) || record_score=="" || record_score>100){
                    BootstrapDialog.alert("评分处请填写正确数字!");
                    return ;
                }

                var record_info = id_record.val();
                if(record_info==""){
                    BootstrapDialog.alert("请填写评价内容!");
                    return ;
                }
                if(record_info.length>150){
                    BootstrapDialog.alert("评价内容不能超过150字!");
                    return ;
                }

                $.do_ajax('/human_resource/set_teacher_record_info',{
                    "teacherid"    : teacherid,
                    "type"         : 1,
                    "record_info"  : record_info,
                    "record_score" : record_score
                },function(result){
                    if(result.ret==0){
                        load_data();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        },function(){
            id_score.attr("placeholder","满分100分");
            id_record.attr("placeholder","字数不能超过150字");
        });
    });

    if(tea_right==0){
        $(".opt-teacher-freeze").hide();
        $(".opt-limit-plan-lesson").hide();
    }

                          

    $(".opt-set-teacher-record-new").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        $.do_ajax("/tongji_ss/get_week_test_lesson_list",{
            "teacherid" : teacherid
        },function(response){
            //console.log(response.data);return;
            var data_list   = [];
            var select_list = [];
            $.each( response.data,function(){
                data_list.push([this["lessonid"], this["nick"],this["lesson_start_str"],this["subject_str"],this["grade_str"]]);               
            });

            $(this).admin_select_dlg({
                header_list     : [ "lessonid","学生","时间","科目","年级"],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                onChange        : function( select_list,dlg) {
                    console.log(teacherid);
                    dlg.close();
                    /*$.do_ajax("/authority/set_permission",{
                        "uid": uid,
                        "groupid_list":JSON.stringify(select_list)
                    });*/
                  
                    var id_have_kj =  $("<div><span >类型:</span><select id=\"teacher_have_kj\"><option value=\"1\" selected>有无基本上课讲义</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_have_kj_score\" class=\"class_score\" /></div></div>");
                    var id_bk_pp =  $("<div><span >类型:</span><select id=\"teacher_bk_pp\"><option value=\"0\">请选择</option><option value=\"1\">匹配度极差</option><option value=\"2\">匹配度一般</option><option value=\"3\">匹配度良好</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_bk_pp_score\" class=\"class_score\" /></div></div>");
                    var id_kj_zl =  $("<div><span >类型:</span><select id=\"teacher_kj_zl\"><option value=\"0\">请选择</option><option value=\"1\">课件内容层次不清，逻辑混乱</option><option value=\"2\">课件内容层次基本合理，符合教学逻辑</option><option value=\"3\">课件内容层次清晰，难度上循序渐进，重点突出</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kj_zl_score\" class=\"class_score\" /></div></div>");
                    var id_tea_pro =  $("<div><span >类型:</span><select id=\"teacher_tea_pro\"><option value=\"0\">请选择</option><option value=\"1\">单纯讲练习，缺少相应技巧和知识点讲解</option><option value=\"2\">知识点讲解过多，缺少对应练习和方法技巧归纳</option><option value=\"3\">方法技巧、知识点讲解与对应练习比例得当，课程系统性良好</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_tea_pro_score\" class=\"class_score\" class=\"class_score\" /></div></div>");
                    var id_kt_fw =  $("<div><span >类型:</span><select id=\"teacher_kt_fw\"><option value=\"0\">请选择</option><option value=\"1\">填鸭式教学，鲜少询问学生接受情况</option><option value=\"2\">有互动，但互动方式和引导时机把握不合理，课堂氛围枯燥平淡</option><option value=\"3\">教师引导积极，师生互动紧密，课堂氛围融洽</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kt_fw_score\" class=\"class_score\" /></div></div>");
                    var id_bs =  $("<div><span >类型:</span><select id=\"teacher_bs\"><option value=\"0\">请选择</option><option value=\"1\">必要板书缺乏，圈画标示过少</option><option value=\"2\">有板书圈画书写，但书写堆砌凌乱，影响教学专业性</option><option value=\"3\">板书规范性良好，内容详实，要点清晰</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_bs_score\" class=\"class_score\" /></div></div>");
                    var id_kcjz =  $("<div><span >类型:</span><select id=\"teacher_kcjz\"><option value=\"0\">请选择</option><option value=\"1\">讲课节奏过慢</option><option value=\"2\">讲课节奏过快</option><option value=\"3\">讲课节奏适中</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kcjz_score\" class=\"class_score\" /></div></div>");
                    var id_jtff =  $("<div><span >类型:</span><select id=\"teacher_jtff\"><option value=\"1\" selected>讲题方法思路正误</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_jtff_score\" class=\"class_score\" /></div></div>");
                    var id_zsd =  $("<div><span >类型:</span><select id=\"teacher_zsd\"><option value=\"1\" selected>知识点讲解正误</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_zsd_score\" class=\"class_score\" /></div></div>");
                    var id_znd =  $("<div><span >类型:</span><select id=\"teacher_znd\"><option value=\"1\" selected>重难点把握是否到位</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_znd_score\" class=\"class_score\" /></div></div>");
                    var id_kbnr =  $("<div><span >类型:</span><select id=\"teacher_kbnr\"><option value=\"1\" selected>课本内容是否熟悉</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kbnr_score\" class=\"class_score\" /></div></div>");
                    var id_tmjd =  $("<div><span >类型:</span><select id=\"teacher_tmjd\"><option value=\"1\" selected>题目解答正误</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_tmjd_score\" class=\"class_score\" /></div></div>");
                    var id_yy =  $("<div><span >类型:</span><select id=\"teacher_yy\"><option value=\"0\">请选择</option><option value=\"1\">语言表达能力差，表述不清</option><option value=\"2\">语言表达尚可，但语言组织能力平庸，欠缺感染力</option><option value=\"3\">语言能力良好，讲解生动形象，富有感染力</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_yy_score\" class=\"class_score\" /></div></div>");
                    var id_jxtd =  $("<div><span >类型:</span><select id=\"teacher_jxtd\"><option value=\"0\">请选择</option><option value=\"1\">教学态度恶劣，侮辱谩骂学生，打击学生自信心</option><option value=\"2\">教学态度散漫随意，如疲态明显，哈欠连天或课堂随意嬉笑等</option><option value=\"3\">教学态度一般，无上课激情或带敷衍态度</option><option value=\"4\">教学态度基本端正，认真负责</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_jxtd_score\" class=\"class_score\" /></div></div>");
                    var id_jxzzd =  $("<div><span >类型:</span><select id=\"teacher_jxzzd\"><option value=\"0\">请选择</option><option value=\"1\">讲课过程中从事教学无关事务如吃东西、 接打电话、 闲聊等</option><option value=\"2\">讲课过程大量留白，延迟回答学生问题、无故让学生等待耽误上课时间</option><option value=\"3\">教学状态良好，课堂专注力佳</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_jxzzd_score\" class=\"class_score\" /></div></div>");
                    var id_jxsg =  $("<div><span >类型:</span><select id=\"teacher_jxsg\"><option value=\"0\">请选择</option><option value=\"1\">推荐其他机构，贬低公司价值</option><option value=\"2\">课件全程无理优logo或使用明显带有其他机构logo的资料</option><option value=\"3\">议论其他员工、泄露公司相关信息</option><option value=\"4\">课程顺利完成，无相关教学事故</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_jxsg_score\" class=\"class_score\" /></div></div>");
                    var id_rjcz =  $("<div><span >类型:</span><select id=\"teacher_rjcz\"><option value=\"0\">请选择</option><option value=\"1\">讲义无截图，纯拍照上传</option><option value=\"2\">讲义截图不清晰且放置位置不合理</option><option value=\"3\">讲义截图清晰但放置位置不合理</option><option value=\"4\">截图清晰且位置放置合理</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_rjcz_score\" class=\"class_score\" /></div></div>");
                    var id_kcyc =  $("<div><span >类型:</span><select id=\"teacher_kcyc\"><option value=\"0\">请选择</option><option value=\"1\">课中遇到网络卡断，音频问题，异常闪退或课程延迟时，慌乱抱怨</option><option value=\"2\">面对课程异常情况，虽有着手处理但处理过于缓慢，耽误上课时间</option><option value=\"3\">面对异常情况，及时冷静处理，顺利解决</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kcyc_score\" class=\"class_score\" /></div></div>");
                    var id_hj =  $("<div><span >类型:</span><select id=\"teacher_hj\"><option value=\"0\">请选择</option><option value=\"1\">教学环境嘈杂；网络音频状况不佳，影响课程体验</option><option value=\"2\">教学环境安静，教学设备状况调试良好</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_hj_score\" class=\"class_score\" /></div></div>");
                    
                    var id_sshd=$("<label><input name=\"Fruit\" type=\"checkbox\" value=\"1\" />鼓励发言 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"2\" />善于引导 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"3\" />提问形式多样 </label><label><input name=\"Fruit\" type=\"checkbox\" value=\"4\" />关注度高 </label>");
                    var id_sshd2=$("<label><input name=\"dog\" type=\"checkbox\" value=\"5\" />空话套话过多 </label> <label><input name=\"dog\" type=\"checkbox\" value=\"6\" />Yes/No问题过多 </label> <label><input name=\"dog\" type=\"checkbox\" value=\"7\" />提问形式单一 </label> <label><input name=\"dog\" type=\"checkbox\" value=\"8\" />关注度低 </label> ");

                    var id_ktfw=$("<label><input name=\"ktfw\" type=\"checkbox\" value=\"1\" />语速均匀 </label> <label><input name=\"ktfw\" type=\"checkbox\" value=\"2\" />轻松愉快 </label> <label><input name=\"ktfw\" type=\"checkbox\" value=\"3\" />节奏紧凑 </label> <label><input name=\"ktfw\" type=\"checkbox\" value=\"4\" />生动有趣 </label><label><input name=\"ktfw\" type=\"checkbox\" value=\"5\" />思路清晰</label> ");
                    var id_ktfw2=$("<label><input name=\"kt\" type=\"checkbox\" value=\"6\" />语速过慢/过快 </label> <label><input name=\"kt\" type=\"checkbox\" value=\"7\" />语调沉闷 </label> <label><input name=\"kt\" type=\"checkbox\" value=\"8\" />节奏拖沓 </label><label><input name=\"kt\" type=\"checkbox\" value=\"9\" />枯燥乏味 </label><label><input name=\"kt\" type=\"checkbox\" value=\"10\" />思路混乱 </label>  ");
                    var id_skgf=$("<label><input name=\"skgf\" type=\"checkbox\" value=\"1\" />考纲熟悉 </label> <label><input name=\"skgf\" type=\"checkbox\" value=\"2\" />软件使用熟练 </label> <label><input name=\"skgf\" type=\"checkbox\" value=\"3\" />讲义精美</label><label><input name=\"skgf\" type=\"checkbox\" value=\"4\" />截图合理 </label> <label><input name=\"skgf\" type=\"checkbox\" value=\"5\" />板书规范 </label><label><input name=\"skgf\" type=\"checkbox\" value=\"6\" />普通话标准 </label> ");
                    var id_skgf2=$("<label><input name=\"sk\" type=\"checkbox\" value=\"7\" />考纲不熟悉 </label> <label><input name=\"sk\" type=\"checkbox\" value=\"8\" />软件使用生疏 </label> <label><input name=\"sk\" type=\"checkbox\" value=\"9\" />讲义凌乱 </label><label><input name=\"sk\" type=\"checkbox\" value=\"10\" />截图不合理 </label><label><input name=\"sk\" type=\"checkbox\" value=\"11\" />板书不规范 </label> <label><input name=\"sk\" type=\"checkbox\" value=\"12\" />有口音 </label> ");
                    var id_jsfg=$("<label><input name=\"jsfg\" type=\"checkbox\" value=\"1\" />平易近人 </label> <label><input name=\"jsfg\" type=\"checkbox\" value=\"2\" />生动活泼</label> <label><input name=\"jsfg\" type=\"checkbox\" value=\"3\" />幽默风趣 </label> <label><input name=\"jsfg\" type=\"checkbox\" value=\"4\" />严谨认真 </label> ");
                    var id_jsfg2=$("<label><input name=\"js\" type=\"checkbox\" value=\"5\" />咄咄逼人</label> <label><input name=\"js\" type=\"checkbox\" value=\"6\" />沉闷乏味 </label> <label><input name=\"js\" type=\"checkbox\" value=\"7\" />缺乏课堂主导性 </label><label><input name=\"js\" type=\"checkbox\" value=\"8\" />散漫随性 </label>  ");


                    Enum_map.append_option_list("teacher_lecture_score",id_have_kj.find("#teacher_have_kj_score"),false,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_jtff.find("#teacher_jtff_score"),false,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_zsd.find("#teacher_zsd_score"),false,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_znd.find("#teacher_znd_score"),false,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_kbnr.find("#teacher_kbnr_score"),false,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_tmjd.find("#teacher_tmjd_score"),false,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"));
                    Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"));
                    Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"));
                    Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"));
                    Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"));
                    Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"));
                    Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"));
                    Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);
                    Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);
                    Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"));
                    Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"));
                    Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30]);
                    Enum_map.append_option_list("test_lesson_score",id_hj.find("#teacher_hj_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
                    var id_score = $("<input readonly/>");
                    var id_rank = $("<input readonly/>");
                    var id_record = $("<textarea />");
                    var id_jkqk = $("<textarea />");

                    var arr=[
                        ["有无课件", id_have_kj],
                        ["备课内容与试听需求匹配", id_bk_pp],
                        ["课件质量", id_kj_zl],
                        ["教学过程设计", id_tea_pro],
                        ["课堂氛围", id_kt_fw],
                        ["板书书写", id_bs],
                        ["课程节奏", id_kcjz],
                        ["讲题方法思路", id_jtff],
                        ["知识点讲解", id_zsd],
                        ["重难点把握", id_znd],
                        ["课本内容熟悉程度", id_kbnr],
                        ["题目解答", id_tmjd],
                        ["语言表达和组织能力", id_yy],
                        ["教学态度", id_jxtd],
                        ["教学专注度", id_jxzzd],
                        ["教学事故", id_jxsg],
                        ["软件操作", id_rjcz],
                        ["课程异常情况处理", id_kcyc],
                        ["周边环境", id_hj],
                        ["总分",id_score],
                        ["等级",id_rank],
                        ["监课情况",id_jkqk],
                        ["意见或建议",id_record],
                        ["标签-师生互动(好)",id_sshd],
                        ["标签-师生互动(不好)",id_sshd2],
                        ["标签-课堂氛围(好)",id_ktfw],
                        ["标签-课堂氛围(不好)",id_ktfw2],
                        ["标签-授课规范(好)",id_skgf],
                        ["标签-授课规范(不好)",id_skgf2],
                        ["标签-教师风格(好)",id_jsfg],
                        ["标签-教师风格(不好)",id_jsfg2]
                    ];
                    
                    id_bk_pp.find("#teacher_bk_pp").on("change",function(){
                        if($(this).val() == 1){
                            id_bk_pp.find("#teacher_bk_pp_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"),false,[3,4,5]);
                        }else if($(this).val() == 2){
                            id_bk_pp.find("#teacher_bk_pp_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"),false,[6,7]);
                        }else if($(this).val() == 3){
                            id_bk_pp.find("#teacher_bk_pp_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"),false,[8,9,10]);
                        }else{
                            id_bk_pp.find("#teacher_bk_pp_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"));
                        }

                    });
                    id_kj_zl.find("#teacher_kj_zl").on("change",function(){
                        if($(this).val() == 1){
                            id_kj_zl.find("#teacher_kj_zl_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"),false,[3,4,5]);
                        }else if($(this).val() == 2){
                            id_kj_zl.find("#teacher_kj_zl_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"),false,[6,7]);
                        }else if($(this).val() == 3){
                            id_kj_zl.find("#teacher_kj_zl_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"),false,[8,9,10]);
                        }else{
                            id_kj_zl.find("#teacher_kj_zl_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"));
                        }

                    });
                    id_tea_pro.find("#teacher_tea_pro").on("change",function(){
                        if($(this).val() == 1){
                            id_tea_pro.find("#teacher_tea_pro_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"),false,[4,5,6]);
                        }else if($(this).val() == 2){
                            id_tea_pro.find("#teacher_tea_pro_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"),false,[4,5,6]);
                        }else if($(this).val() == 3){
                            id_tea_pro.find("#teacher_tea_pro_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"),false,[7,8,9,10]);
                        }else{
                            id_tea_pro.find("#teacher_tea_pro_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"));
                        }
                    });
                    id_kt_fw.find("#teacher_kt_fw").on("change",function(){
                        if($(this).val() == 1){
                            id_kt_fw.find("#teacher_kt_fw_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"),false,[3,4,5]);
                        }else if($(this).val() == 2){
                            id_kt_fw.find("#teacher_kt_fw_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"),false,[6,7]);
                        }else if($(this).val() == 3){
                            id_kt_fw.find("#teacher_kt_fw_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"),false,[8,9,10]);
                        }else{
                            id_kt_fw.find("#teacher_kt_fw_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"));
                        }

                    });
                    id_bs.find("#teacher_bs").on("change",function(){
                        if($(this).val() == 1){
                            id_bs.find("#teacher_bs_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"),false,[3,4,5]);
                        }else if($(this).val() == 2){
                            id_bs.find("#teacher_bs_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"),false,[6,7]);
                        }else if($(this).val() == 3){
                            id_bs.find("#teacher_bs_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"),false,[8,9,10]);
                        }else{
                            id_bs.find("#teacher_bs_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"));
                        }

                    });
                    id_kcjz.find("#teacher_kcjz").on("change",function(){
                        if($(this).val() == 1){
                            id_kcjz.find("#teacher_kcjz_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"),false,[4,5,6]);
                        }else if($(this).val() == 2){
                            id_kcjz.find("#teacher_kcjz_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"),false,[4,5,6]);
                        }else if($(this).val() == 3){
                            id_kcjz.find("#teacher_kcjz_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"),false,[7,8,9,10]);
                        }else{
                            id_kcjz.find("#teacher_kcjz_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"));
                        }

                    });
                    id_yy.find("#teacher_yy").on("change",function(){
                        if($(this).val() == 1){
                            id_yy.find("#teacher_yy_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"),false,[3,4,5]);
                        }else if($(this).val() == 2){
                            id_yy.find("#teacher_yy_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"),false,[6,7]);
                        }else if($(this).val() == 3){
                            id_yy.find("#teacher_yy_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"),false,[8,9,10]);
                        }else{
                            id_yy.find("#teacher_yy_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"));
                        }

                    });
                    id_jxtd.find("#teacher_jxtd").on("change",function(){
                        if($(this).val() == 1){
                            id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),true,[0]);
                        }else if($(this).val() == 2){
                            id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[5,6,7,8,9,10]);
                        }else if($(this).val() == 3){
                            id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[10,15,20]);
                        }else if($(this).val() == 4){
                            id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[20,21,22,23,24,25]);

                        }else{
                            id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);
                        }

                    });
                    id_jxzzd.find("#teacher_jxzzd").on("change",function(){
                        if($(this).val() == 1){
                            id_jxzzd.find("#teacher_jxzzd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[5,6,7,8,9,10]);
                        }else if($(this).val() == 2){
                            id_jxzzd.find("#teacher_jxzzd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[10,15,20]);      
                        }else if($(this).val() == 3){
                            id_jxzzd.find("#teacher_jxzzd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[20,21,22,23,24,25]);          
                        }else{
                            id_jxzzd.find("#teacher_jxzzd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);
                            
                        }

                    });
                    id_jxsg.find("#teacher_jxsg").on("change",function(){
                        if($(this).val() == 1){
                            id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"),true,[0]);
                        }else if($(this).val() == 2){
                            id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"),false,[10,15,20]);
                        }else if($(this).val() == 3){
                            id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"),false,[20,25,30,35,40]);
                        }else if($(this).val() == 4){
                            id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"),true,[50]);
                        }else{
                            id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"));
                        }

                    });
                    id_rjcz.find("#teacher_rjcz").on("change",function(){
                        if($(this).val() == 1){
                            id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"),false,[0,1,2,3,4,5,6,7,8,9,10]);
                        }else if($(this).val() == 2){
                            id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"),false,[10,15,20]);     
                        }else if($(this).val() == 3){
                            id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"),false,[20,25,30,35,40]);         
                        }else if($(this).val() == 4){
                            id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"),false,[40,45,50]);         
                        }else{
                            id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"));
                            
                        }

                    });

                    id_kcyc.find("#teacher_kcyc").on("change",function(){
                        if($(this).val() == 1){
                            id_kcyc.find("#teacher_kcyc_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[5,6,7,8,9,10]);
                        }else if($(this).val() == 2){
                            id_kcyc.find("#teacher_kcyc_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[10,15,20]);
                        }else if($(this).val() == 3){
                            id_kcyc.find("#teacher_kcyc_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[20,25,30]);
                        }else{
                            id_kcyc.find("#teacher_kcyc_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30]);
                        }

                    });
                    id_hj.find("#teacher_hj").on("change",function(){
                        if($(this).val() == 1){
                            id_hj.find("#teacher_hj_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_hj.find("#teacher_hj_score"),false,[5,6,7,8,9,10]);
                        }else if($(this).val() == 2){
                            id_hj.find("#teacher_hj_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_hj.find("#teacher_hj_score"),false,[10,15,20]);
                        }else{
                            id_hj.find("#teacher_hj_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_hj.find("#teacher_hj_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
                        }

                    });

                    $.show_key_value_table("试听评价", arr,{
                        label    : '确认',
                        cssClass : 'btn-warning',
                        action   : function(dialog) {
                            var record_info = id_record.val();
                            if(record_info==""){
                                BootstrapDialog.alert("请填写评价内容!");
                                return ;
                            }
                            console.log(record_info.length);
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
                            if(sshd_bad.length==0 && sshd_good.length==0){
                                BootstrapDialog.alert("请选择老师标签");
                                return false;
                            }
                            var ktfw_good=[];
                            id_ktfw.find("input:checkbox[name='ktfw']:checked").each(function(i) {
                                ktfw_good.push($(this).val());
                            });
                            var ktfw_bad=[];
                            id_ktfw2.find("input:checkbox[name='kt']:checked").each(function(i) {
                                ktfw_bad.push($(this).val());
                            });
                            if(ktfw_bad.length==0 && ktfw_good.length==0){
                                BootstrapDialog.alert("请选择老师标签");
                                return false;
                            }

                            var skgf_good=[];
                            id_skgf.find("input:checkbox[name='skgf']:checked").each(function(i) {
                                skgf_good.push($(this).val());
                            });
                            var skgf_bad=[];
                            id_skgf2.find("input:checkbox[name='sk']:checked").each(function(i) {
                                skgf_bad.push($(this).val());
                            });
                            if(skgf_bad.length==0 && skgf_good.length==0){
                                BootstrapDialog.alert("请选择老师标签");
                                return false;
                            }

                            var jsfg_good=[];
                            id_jsfg.find("input:checkbox[name='jsfg']:checked").each(function(i) {
                                jsfg_good.push($(this).val());
                            });
                            var jsfg_bad=[];
                            id_jsfg2.find("input:checkbox[name='js']:checked").each(function(i) {
                                jsfg_bad.push($(this).val());
                            });
                            if(jsfg_bad.length==0 && jsfg_good.length==0){
                                BootstrapDialog.alert("请选择老师标签");
                                return false;
                            }

                            $.do_ajax("/human_resource/set_teacher_record_info",{
                                "teacherid"    : teacherid,
                                "type"         : 1,
                                "courseware_flag"              : id_have_kj.find("#teacher_have_kj").find("option:selected").text(),
                                "courseware_flag_score"        : id_have_kj.find("#teacher_have_kj_score").val(),
                                "lesson_preparation_content"   : id_bk_pp.find("#teacher_bk_pp").find("option:selected").text(),
                                "lesson_preparation_content_score"   : id_bk_pp.find("#teacher_bk_pp_score").val(),        
                                "courseware_quality"          : id_kj_zl.find("#teacher_kj_zl").find("option:selected").text(),
                                "courseware_quality_score"    : id_kj_zl.find("#teacher_kj_zl_score").val(),
                                "tea_process_design"          : id_tea_pro.find("#teacher_tea_pro").find("option:selected").text(),
                                "tea_process_design_score"    : id_tea_pro.find("#teacher_tea_pro_score").val(),     
                                "class_atm"                   : id_kt_fw.find("#teacher_kt_fw").find("option:selected").text(),
                                "class_atm_score"             : id_kt_fw.find("#teacher_kt_fw_score").val(),     
                                "knw_point"                   : id_zsd.find("#teacher_zsd").find("option:selected").text(),
                                "knw_point_score"             : id_zsd.find("#teacher_zsd_score").val(),     
                                "dif_point"                   : id_znd.find("#teacher_znd").find("option:selected").text(),
                                "dif_point_score"             : id_znd.find("#teacher_znd_score").val(),     
                                "teacher_blackboard_writing"         : id_bs.find("#teacher_bs").find("option:selected").text(),
                                "teacher_blackboard_writing_score"   : id_bs.find("#teacher_bs_score").val(),     
                                "tea_rhythm"             : id_kcjz.find("#teacher_kcjz").find("option:selected").text(),
                                "tea_rhythm_score"       : id_kcjz.find("#teacher_kcjz_score").val(),     
                                "language_performance"       : id_yy.find("#teacher_yy").find("option:selected").text(),
                                "language_performance_score" : id_yy.find("#teacher_yy_score").val(),
                                "content_fam_degree"       : id_kbnr.find("#teacher_kbnr").find("option:selected").text(),
                                "content_fam_degree_score" : id_kbnr.find("#teacher_kbnr_score").val(),     
                                "answer_question_cre"       : id_tmjd.find("#teacher_tmjd").find("option:selected").text(),
                                "answer_question_cre_score" : id_tmjd.find("#teacher_tmjd_score").val(),     
                                "tea_attitude"       : id_jxtd.find("#teacher_jxtd").find("option:selected").text(),
                                "tea_attitude_score" : id_jxtd.find("#teacher_jxtd_score").val(),
                                "tea_method"       : id_jtff.find("#teacher_jtff").find("option:selected").text(),
                                "tea_method_score" : id_jtff.find("#teacher_jtff_score").val(),
                                "tea_concentration"       : id_jxzzd.find("#teacher_jxzzd").find("option:selected").text(),
                                "tea_concentration_score" : id_jxzzd.find("#teacher_jxzzd_score").val(),
                                "tea_accident"       : id_jxsg.find("#teacher_jxsg").find("option:selected").text(),
                                "tea_accident_score" : id_jxsg.find("#teacher_jxsg_score").val(),     
                                "tea_operation"                  : id_rjcz.find("#teacher_rjcz").find("option:selected").text(),
                                "tea_operation_score"            : id_rjcz.find("#teacher_rjcz_score").val(),     
                                "tea_environment"                : id_hj.find("#teacher_hj").find("option:selected").text(),
                                "tea_environment_score"          : id_hj.find("#teacher_hj_score").val(),     
                                "record_score"              : id_score.val(),
                                "class_abnormality"              : id_kcyc.find("#teacher_kcyc").find("option:selected").text(),
                                "class_abnormality_score"        : id_kcyc.find("#teacher_kcyc_score").val(), 
                                "record_info"                    : id_record.val(),
                                "record_monitor_class"           :id_jkqk.val(),
                                "record_rank"                    :id_rank.val(),
                                "record_lessonid_list"           :JSON.stringify(select_list),
                                "sshd_good"                          :JSON.stringify(sshd_good),
                                "sshd_bad"                           :JSON.stringify(sshd_bad),
                                "ktfw_good"                          :JSON.stringify(ktfw_good),
                                "ktfw_bad"                           :JSON.stringify(ktfw_bad),
                                "skgf_good"                          :JSON.stringify(skgf_good),
                                "skgf_bad"                           :JSON.stringify(skgf_bad),
                                "jsfg_good"                          :JSON.stringify(jsfg_good),
                                "jsfg_bad"                           :JSON.stringify(jsfg_bad),
                            });
                        }
                    },function(){
                        id_score.attr("placeholder","满分100分");
                        id_record.attr("placeholder","字数不能超过150字");
                    });

                    //console.log(arr[0][1]);
                    arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
                        id_score.val(parseInt(0.6*(parseInt(id_bk_pp.find("#teacher_bk_pp_score").val())+parseInt(id_have_kj.find("#teacher_have_kj_score").val())+parseInt(id_kj_zl.find("#teacher_kj_zl_score").val())+parseInt(id_tea_pro.find("#teacher_tea_pro_score").val())+parseInt(id_kt_fw.find("#teacher_kt_fw_score").val())+parseInt(id_bs.find("#teacher_bs_score").val())+parseInt(id_kcjz.find("#teacher_kcjz_score").val())+parseInt(id_jtff.find("#teacher_jtff_score").val())+parseInt(id_zsd.find("#teacher_zsd_score").val())+parseInt(id_znd.find("#teacher_znd_score").val())+parseInt(id_kbnr.find("#teacher_kbnr_score").val())+parseInt(id_tmjd.find("#teacher_tmjd_score").val())+parseInt(id_yy.find("#teacher_yy_score").val()))+0.3*(parseInt(id_jxtd.find("#teacher_jxtd_score").val())+parseInt(id_jxzzd.find("#teacher_jxzzd_score").val())+parseInt(id_jxsg.find("#teacher_jxsg_score").val()))+0.1*(parseInt(id_rjcz.find("#teacher_rjcz_score").val())+parseInt(id_kcyc.find("#teacher_kcyc_score").val())+parseInt(id_hj.find("#teacher_hj_score").val()))));
                        if(id_score.val()>90 && id_score.val() <= 100){
                            id_rank.val("S");
                        }else if(id_score.val()>80 && id_score.val()<=90){
                            id_rank.val("A");
                        }else if(id_score.val()>70 && id_score.val()<=80){
                            id_rank.val("B");
                        }else{
                            id_rank.val("C");
                        }
                    });
                    arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("width",970);
                    arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("left",-200);


                }
            });
        }) ;

        
        
    });


    $.each($("tr"),function(i,item){
        var success_lesson = $(this).children().find(".data").data("success_lesson");
        var have_order     = $(this).children().find(".data").data("have_order");
        var order_number     = $(this).children().find(".data").data("order_number");
        var is_freeze      = $(this).children().find(".data").data("is_freeze");
        var limit          = $(this).children().find(".data").data("limit_plan_lesson_type");
        var order_per      = $(this).children().find(".data").data("order_per");
        var lesson_num= $(this).children().find(".data").data("order_per");

        if(i>1){
            if(order_per>=30 && tea_right==2){
                $(this).hide();
            }else if(order_per>=20 && g_tea_subject>0){
                $(this).hide();
                if(order_per>=25 && lesson_num>10){
                    $(this).show();
                }
            }else{
                if(is_freeze>0){
                    $(this).addClass("bg_orange");
                }else if(limit>0){
                    $(this).addClass("bg_orange_red");
                }else if(success_lesson>=10 && order_number<1){
                    $(this).addClass("bg_red");
                    $(this).find(".status_str").text("预警");
                }
            }
        }
        if(g_teacher_test_status==1){
            if(is_freeze <=0){
                $(this).hide();
            } 
        }else if(g_teacher_test_status==2){
            if(limit <=0){
                $(this).hide();
            } 

        }else if(g_teacher_test_status==3){
            if(success_lesson<10 || order_number>=1){
                $(this).hide();
            } 
        }
    });

    $('.opt-change').set_input_change_event(load_data);
});
