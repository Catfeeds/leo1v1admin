/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_fifth_regular_lesson_info.d.ts" />

var Cwhiteboard=null;
var notify_cur_playpostion =null;
$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			teacherid:	$('#id_teacherid').val(),
			userid:	$('#id_userid').val(),
			subject:	$('#id_subject').val(),
			record_flag:	$('#id_record_flag').val()
        });
    }
    //audiojs 时间回调, 每秒3-4次
    //$(".tea_cw_url[data-v = 0], .stu_cw_url[data-v=0],.homework_url[data-v=0]" ) .parent().addClass("danger");
    //=======================================================
    notify_cur_playpostion = function (cur_play_time){
        Cwhiteboard.play_next( cur_play_time );
    };

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

    Enum_map.append_option_list("subject", $('#id_subject'));
    Enum_map.append_option_list("boolean", $('#id_record_flag'));

	$('#id_teacherid').val(g_args.teacherid);
	$('#id_userid').val(g_args.userid);
	$('#id_subject').val(g_args.subject);
	$('#id_record_flag').val(g_args.record_flag);
    $.admin_select_user($("#id_teacherid"), "teacher", load_data);
    $.admin_select_user($("#id_userid"), "student", load_data);
    $(".opt-fifth-lesson-video").on("click",function(){
        var opt_data = $(this).get_opt_data();
        $.do_ajax("/teacher_level/set_teacher_record_acc",{
            "teacherid"    : opt_data.teacherid,
            "type"         : 1,
            "lesson_style" : 4,
            "lessonid"     :opt_data.lessonid,
            "lesson_list"  :JSON.stringify(opt_data.lessonid),
            "acc"          :opt_data.acc
        },function(result){
            var acc= result.acc;
            if(acc != "" && acc != g_args.acc){
                alert("该视频已有审核人");
                return;
            }
            $.ajax({
                type     : "post",
                url      : "/tea_manage/get_lesson_reply",
                dataType : "json",
                data     : {"lessonid":opt_data.lessonid},
                success  : function(result){
                    if(result.ret == 0){
                        console.log("http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                                    +"&audio="+encodeURIComponent(result.audio_url)
                                    +"&start="+result.real_begin_time);
                        if ( false && !$.check_in_phone() ) {

                            // console.log("http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(result.draw_url)
                            //             +"&audio="+encodeURIComponent(result.audio_url)
                            //             +"&start="+result.real_begin_time);
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
                                title    : '课程回放:lessonid:'+opt_data.lessonid+", 学生:" + result.stu_nick,
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
            
        });       

    });

    $(".opt-fifth-lesson-record").on("click",function(){
        var opt_data = $(this).get_opt_data();
        console.log(opt_data.id);
        $.do_ajax("/teacher_level/set_teacher_record_acc",{
            "teacherid"    : opt_data.teacherid,
            "type"         : 1,
            "lesson_style" : 4,
            "lessonid"     :opt_data.lessonid,
            "lesson_list"  :JSON.stringify(opt_data.lessonid),
        },function(result){
            var acc= result.acc;
            if(acc != "" && acc != g_args.acc){
                alert("该视频已有审核人");
                return;
            }
            var lessonid = opt_data.lessonid;
            var teacherid = opt_data.teacherid;
            var id_jysj =  $("<select class=\"class_score\" />");
            var id_yybd =  $("<select class=\"class_score\" />");
            var id_zyzs =  $("<select class=\"class_score\" />");
            var id_jxjz =  $("<select class=\"class_score\" />");
            var id_hdqk =  $("<select class=\"class_score\" />");
            var id_bsqk =  $("<select class=\"class_score\" />");
            var id_rjcz =  $("<select class=\"class_score\" />");
            var id_skhj =  $("<select class=\"class_score\" />");
            var id_khfk =  $("<select class=\"class_score\" />");
            var id_lcgf =  $("<select class=\"class_score\" />");
            var id_lesson_invalid_flag =  $("<select ><option value=\"1\">有效课程</option><option value=\"2\">无效课程</option></select>");                     
            var id_sshd=$("<label><input name=\"Fruit\" type=\"checkbox\" value=\"1\" />自然型 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"2\" />逻辑型 </label><label><input name=\"Fruit\" type=\"checkbox\" value=\"4\" />技巧型 </label><label><input name=\"Fruit\" type=\"checkbox\" value=\"5\" />情感型 </label>");

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
            var id_score = $("<input readonly/>");
            var id_no_tea_score = $("<input readonly/>");
            var id_record = $("<textarea />");
            var id_jkqk = $("<textarea />");

            var arr=[
                ["课程有效性", id_lesson_invalid_flag],
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
                ["监课情况",id_jkqk],
                ["意见或建议",id_record],
                ["标签",id_sshd],
            ];
            
                       
            id_lesson_invalid_flag.on("change",function(){
                if($(this).val() ==2){
                    id_jysj.parent().parent().hide();  
                    id_yybd.parent().parent().hide();  
                    id_zyzs.parent().parent().hide();  
                    id_jxjz.parent().parent().hide();  
                    id_hdqk.parent().parent().hide();  
                    id_bsqk.parent().parent().hide();  
                    id_skhj.parent().parent().hide();  
                    id_khfk.parent().parent().hide();  
                    id_lcgf.parent().parent().hide();  
                    id_score.parent().parent().hide();  
                    id_no_tea_score.parent().parent().hide();  
                    id_jkqk.parent().parent().hide();  
                    id_sshd.parent().parent().hide();  
                    id_rjcz.parent().parent().hide();  
                    
                }else{
                    id_jysj.parent().parent().show();  
                    id_yybd.parent().parent().show();  
                    id_zyzs.parent().parent().show();  
                    id_jxjz.parent().parent().show();  
                    id_hdqk.parent().parent().show();  
                    id_bsqk.parent().parent().show();  
                    id_skhj.parent().parent().show();  
                    id_khfk.parent().parent().show();  
                    id_lcgf.parent().parent().show();  
                    id_score.parent().parent().show();  
                    id_no_tea_score.parent().parent().show();  
                    id_jkqk.parent().parent().show();  
                    id_sshd.parent().parent().show();  
                    id_rjcz.parent().parent().show();  
                    
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
                    
                    $.do_ajax("/teacher_level/set_teacher_record_info",{
                        "teacherid"    : teacherid,
                        "lesson_invalid_flag"    : id_lesson_invalid_flag.val(),
                        "userid"    : opt_data.userid,
                        "id"    : opt_data.id,
                        "type"         : 1,
                        "lesson_style" : 4,
                        "tea_process_design_score"         : id_jysj.val(),
                        "language_performance_score"         : id_yybd.val(),
                        "knw_point_score"         : id_zyzs.val(),
                        "tea_rhythm_score"         : id_jxjz.val(),
                        "tea_concentration_score"         : id_hdqk.val(),
                        "teacher_blackboard_writing_score"         : id_bsqk.val(),
                        "tea_operation_score"         : id_rjcz.val(),
                        "tea_environment_score"         : id_skhj.val(),
                        "answer_question_cre_score"         : id_khfk.val(),
                        "class_abnormality_score"         : id_lcgf.val(),
                        "score"         : id_score.val(),
                        "no_tea_related_score"                       : id_no_tea_score.val(),
                        "record_info"                        : id_record.val(),
                        "record_monitor_class"               : id_jkqk.val(),
                        "sshd_good"                          :JSON.stringify(sshd_good),
                        "lessonid"                           :lessonid,
                        "lesson_list"                        :JSON.stringify(lessonid),
                        "train_type"                         :'',
                        "subject"                            :'',
                    });
                }
            },function(){
                id_score.attr("placeholder","满分100分");
                id_record.attr("placeholder","字数不能超过150字");
            });

            //console.log(arr[0][1]);
            arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
                id_score.val(parseInt(id_jysj.val())+parseInt(id_yybd.val())+parseInt(id_zyzs.val())+parseInt(id_jxjz.val())+parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));
                id_no_tea_score.val(parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));

                
            });

        });

        
    });


    $(".opt-fifth-lesson-record-list").on("click",function(){
        var id        = $(this).get_opt_data("id");
        var lessonid        = $(this).get_opt_data("lessonid");
        console.log(id);
        
        $.do_ajax('/ss_deal/get_train_lesson_record_info',{
            "id" : id,
            "lessonid":lessonid
        },function(resp) {
            var title = "反馈详情";
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
                    $.do_ajax( '/teacher_level/reset_record_acc', {
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

    $(".opt-out-link").on("click",function(){
        var lessonid = $(this).get_opt_data("lessonid");
        $.do_ajax( "/common/encode_text",{
            "text" : lessonid
        }, function(ret){
            BootstrapDialog.alert("对外链接 : http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text  );
        });
    });

    $(".opt-play-new").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var lessonid = $(this).get_opt_data("lessonid");
        var id = $(this).get_opt_data("id");
        $.do_ajax( "/common/encode_text",{
            "text" : lessonid
        }, function(ret){
            // BootstrapDialog.alert("对外链接 : http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text  );
            $.wopen("http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text);
        });
        $.do_ajax("/teacher_level/set_teacher_record_acc",{
            "teacherid"    : opt_data.teacherid,
            "type"         : 1,
            "lesson_style" : 4,
            "lessonid"     :opt_data.lessonid,
            "lesson_list"  :JSON.stringify(opt_data.lessonid),
        },function(result){
            window.location.reload(); 
        });

    });



	$('.opt-change').set_input_change_event(load_data);
});










