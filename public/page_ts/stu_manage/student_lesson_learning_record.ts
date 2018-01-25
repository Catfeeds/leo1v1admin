///<reference path="../common.d.ts" />
///<reference path="../g_args.d.ts/stu_manage-student_lesson_learning_record.d.ts" />
function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        order_by_str : g_args.order_by_str,
        sid:	g_args.sid,
        // date_type_config:	$('#id_date_type_config').val(),
        // date_type:	$('#id_date_type').val(),
        // opt_date_type:	$('#id_opt_date_type').val(),
        // start_time:	$('#id_start_time').val(),
        // end_time:	$('#id_end_time').val(),
        subject:	$('#id_subject').val(),
        grade:	$('#id_grade').val(),
        cw_status:	$('#id_cw_status').val(),
        preview_status:	$('#id_preview_status').val(),
        current_id:	$(".current").data("id"),
        start_date:	$('#id_start_date').val(),
        end_date:	$('#id_end_date').val(),
        current_table_id:$(".current_score").data("table_id"),
    });

}

$(function(){

    window["g_load_data_flag"]=1;
    // $('#id_date_range').select_date_range({
    //     'date_type' : g_args.date_type,
    //     'opt_date_type' : g_args.opt_date_type,
    //     'start_time'    : g_args.start_time,
    //     'end_time'      : g_args.end_time,
    //     date_type_config : JSON.parse( g_args.date_type_config),
    //     onQuery :function() {
    //         load_data();
    //     }
    // });

    var get_arr_from_obj = function(objj){
        var arr = []
        for (var i in objj) {
            arr.push(parseInt(objj[i])); //属性
            //arr.push(object[i]); //值
        }
        return arr;
    };
    var subject_list_arr =get_arr_from_obj(window["g_subject_list"]);
    var grade_list_arr =get_arr_from_obj(window["g_grade_list"]);
    Enum_map.append_option_list("subject",$("#id_subject"),false,subject_list_arr);
    Enum_map.append_option_list("grade",$("#id_grade"),false,grade_list_arr);


    $('#id_grade').val(g_args.grade);
    $('#id_subject').val(g_args.subject);
    $('#id_cw_status').val(g_args.cw_status);
    $('#id_preview_status').val(g_args.preview_status);
    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    //时间控件
    $('#id_start_date').datetimepicker({
        lang:'ch',
        timepicker:false,
        format:'Y-m-d',
        onChangeDateTime :function(){
           load_data();
        }
    });

    $('#id_end_date').datetimepicker({
        lang:'ch',
        timepicker:false,
        format:'Y-m-d',
        onChangeDateTime :function(){
           load_data();
        }
    });



    $("#id_search").on("click",function(){       

        window["g_load_data_flag"] = 0;
        load_data();

    });



    $('.stu_tab04 td').on('click', function() {
        $(this).addClass('current');
        $(this).siblings().removeClass('current');
        $(this).siblings().css({
            "background-color":"white",
        });
        $(this).siblings().find("a").css({
            "color":"#000",
        });
        $(".current").css({
            "background-color":"#00E5EE",
        });
        $(".current a").css({
            "color":"white",
        });
        var current_id =  $(".current").data("id");
        if(current_id==5){
            $("#id_add_stu_score").parent().show();
        }else{
            $("#id_add_stu_score").parent().hide();
        }
        window["g_load_data_flag"] = 0;
        load_data();




        // var show_id = $(this).attr('data-id');
        // $(show_id).removeClass('hide');
        // $(this).siblings().each(function(){
        //     var hide_id = $(this).attr('data-id');
        //     $(hide_id).addClass('hide');
        // });
    });
    $('.stu_tab04 td').each(function(){
        var current_id = $(this).data("id");
        if(current_id==g_args.current_id){
            $(this).addClass('current');
            $(this).siblings().removeClass('current');
            $(this).siblings().css({
                "background-color":"white",
            });
            $(this).siblings().find("a").css({
                "color":"#000",
            });
            $(".current").css({
                "background-color":"#00E5EE",
            });
            $(".current a").css({
                "color":"white",
            });
            if(current_id==5){
                $("#id_add_stu_score").parent().show();
            }else{
                $("#id_add_stu_score").parent().hide();
            }

        }
    });
    var current_id =  $(".current").data("id");
    if(current_id==5){
        $("#id_add_stu_score").parent().show();
    }else{
        $("#id_add_stu_score").parent().hide();
    }

   
    $(".preview_table_flag,.lesson_table_flag,.performance_table_flag,.homework_table_flag,.score_table_flag").each(function(){
        var class_id =$(this).data("class_id");
        if(current_id==class_id){
            $(this).show();
        }else{
            $(this).hide();
        }
    });




    $(".current").css({
        "background-color":"#00E5EE",
    });
    $(".current a").css({
        "color":"white",
    });

    $("#id_cw_status,#id_preview_status").change(function(){
        window["g_load_data_flag"] = 0;
        load_data();
    });

    $('.opt-change').set_input_change_event(load_data);
    $('#id_grade').change(function(){
        var grade=$(this).val();
        var vv = $(this).find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        if(grade==-1){
            $("#id_grade_show").hide();
        }else{
            $("#id_grade_show").html(htm);
            $("#id_grade_show").show();
        }
    });
    $('#id_subject').change(function(){
        var subject=$(this).val();
        var vv = $(this).find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        if(subject==-1){
            $("#id_subject_show").hide();
        }else{
            $("#id_subject_show").html(htm);
            $("#id_subject_show").show();
        }
    });
    $('#id_start_date,#id_end_date').change(function(){
        var start=$("#id_start_date").val();
        var end=$("#id_end_date").val();
        var vv = start+"~"+end;
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        if(start=="" && end==""){
            $("#id_date_show").hide();
        }else{
            $("#id_date_show").html(htm);
            $("#id_date_show").show();
        }


    });

    if(g_args.grade==-1){
        $("#id_grade_show").hide();
    }else{
        var vv = $("#id_grade").find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        $("#id_grade_show").html(htm);
        $("#id_grade_show").show();
    }
    if(g_args.subject==-1){
        $("#id_subject_show").hide();
    }else{
        var vv = $("#id_subject").find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        $("#id_subject_show").html(htm);
        $("#id_subject_show").show();
    }
    if(g_args.start_date=="" && g_args.end_date==""){
        $("#id_date_show").hide();
    }else{
        var vv = $("#id_start_date").val()+"~"+$("#id_end_date").val();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        $("#id_date_show").html(htm);
        $("#id_date_show").show();
    }
    if(g_args.subject==-1){
        $("#id_subject_show").hide();
    }else{
        var vv = $("#id_subject").find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        $("#id_subject_show").html(htm);
        $("#id_subject_show").show();
    }


    $("#id_grade_show").on("click",function(){
        $(this).hide();
        $("#id_grade").val(-1);
        window["g_load_data_flag"] = 0;
        load_data();

    });

    $("#id_subject_show").on("click",function(){
        $(this).hide();
        $("#id_subject").val(-1);
        window["g_load_data_flag"] = 0;
        load_data();
    });
    $("#id_date_show").on("click",function(){
        $(this).hide();
        $("#id_start_date").val("");
        $("#id_end_date").val("");
        window["g_load_data_flag"] = 0;
        load_data();
    });


    $(".show_cw_content,.show_issue_content").on("click",function(){
        var url = $(this).data("url");
        $.wopen(url);
    });
    $("#id_show_all").on("click",function(){
        var userid = g_args.sid;

        $.do_ajax('/ajax_deal2/get_student_deatil_lesson_info',{
            "userid"   : userid
        },function(resp) {
            var list = resp.data;
            var title = "成长轨迹";
            var html_node= $("<div class=\"row\" ><div class=\"col-xs-6 col-md-12\" style=\"text-align:center;\" ><div class=\"header_img\"><img  style=\"border-radius:130px;width:120px; border: 3px solid #ccc;\"  src=\""+list.face+"\" /></div></div><div class=\"col-xs-6 col-md-12\" style=\"text-align:center;margin-top:10px\" ><div class=\"header_img\">"+list.realname+"</div></div><div class=\"col-xs-6 col-md-12\" style=\"margin-top:40px\" ><p>"+list.str1+"</p><p>"+list.str2+"</p></div<p>"+list.str3+"</p><p>"+list.str4+"</p></div>");

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

            dlg.getModalDialog().css("width","600px");

        });

    });
    $(".show_lesson_detail").on("click",function(){
        var lessonid = $(this).data("lessonid");
        $.do_ajax('/ajax_deal3/get_student_lesson_info_by_lessonid',{
            "lessonid"   : lessonid
        },function(resp) {
            var list = resp.data;
            var title = "课程信息";
            var html_node= $("<div class=\"row\" >"
                             +"<div class=\"col-xs-6 col-md-12\"  >"
                             +"<a class=\"btn btn-warning show_lesson_video\" href=\"javascript:;\" style=\"float:right\">课程回访</a>"
                             +"</div><div class=\"col-xs-12 col-md-12  \">"
                             +"<span><font size=\"3\" color=\"black\">基本信息</font></span> "
                             +"</div>"
                             +"<div class=\"col-xs-12 col-md-12  \" >"
                             +"<table style=\"margin-left:10px\" class=\"table table-bordered\"> "
                             +"<tr>"+
                             "<td ><font size=\"2\" color=\"black\">序号</font></td>"+
                             "<td>"+list.lesson_num+"</td>"+
                             "<td><font size=\"2\" color=\"black\">时间</font></td>"
                             +"<td>"+list.lesson_time+"</td>"
                             +"<td><font size=\"2\" color=\"black\">年级</font></td>"
                             +"<td>"+list.grade_str+"</td>"
                             +"<td><font size=\"2\" color=\"black\">科目</font></td>"
                             +"<td>"+list.subject_str+"</td>"
                             +"<td><font size=\"2\" color=\"black\">老师</font></td>"
                             +"<td>"+list.realname+"</td>"
                             +"</tr>"
                             +"</table>"
                             +"</div>"
                             +"<div class=\"col-xs-12 col-md-12  \">"
                             +"<span><font size=\"3\" color=\"black\">预习</font></span> "
                             +"</div>"
                             +"<div class=\"col-xs-12 col-md-12  \" >"
                             +"<div class=\"col-xs-12 col-md-6  \" >"
                             +"<table style=\"margin-left:-5px\" class=\"table table-bordered\">"
                             +" <tr>"
                             +"<td><font size=\"2\" color=\"black\">讲义上传</font></td>"
                             +"<td>"
                             +"<a class=\"show_cw_content\" href=\"javascript:;\" data-url="+list.cw_url+" >"+list.cw_status_str+"</a>"
                             +"</td>"
                             +"<td><font size=\"2\" color=\"black\">预习情况</font></td>"
                             +"<td>"+list.preview_status_str+"</td>"
                             +"</tr>"
                             +"</table>"
                             +"</div>"
                             +"</div>"
                             +"<div class=\"col-xs-12 col-md-12  \">"
                             +"<span><font size=\"3\" color=\"black\">课堂情况</font></span>"
                             +" </div>"
                             +"<div class=\"col-xs-12 col-md-12  \" >"
                             +"<table style=\"margin-left:10px\" class=\"table table-bordered\">"
                             +" <tr>"
                             +"<td><font size=\"2\" color=\"black\">学生考勤</font></td>"
                             +"<td>"+list.stu_attend_str+"</td>"
                             +"<td><font size=\"2\" color=\"black\">学生登录</font></td>"
                             +"<td>"+list.stu_login_num+"</td>"
                             +"<td><font size=\"2\" color=\"black\">家长登录</font></td>"
                             +"<td>"+list.parent_login_num+"</td>"
                             +"<td><font size=\"2\" color=\"black\">学生画笔</font></td>"
                             +"<td>"+list.stu_draw +"</td>"
                             +"<td><font size=\"2\" color=\"black\">学生发言</font></td>"
                             +"<td>"+list.stu_voice+"</td>"
                             +"<td><font size=\"2\" color=\"black\">获赞</font></td>"
                             +"<td>"+list.stu_praise+"</td>"
                             +"</tr>"
                             +"<tr>"
                             +"<td><font size=\"2\" color=\"black\">老师考勤</font></td>"
                             +"<td>"+list.tea_attend_str+"</td>"
                             +"<td><font size=\"2\" color=\"black\">老师登录</font></td>"
                             +"<td>"+list.tea_login_num+"</td>"
                             +"<td></td>"
                             +"<td></td>"
                             +"<td><font size=\"2\" color=\"black\">老师画笔</font></td>"
                             +"<td>"+list.tea_draw+"</td>"
                             +"<td><font size=\"2\" color=\"black\">老师发言</font></td>"
                             +"<td>"+list.tea_voice+"</td>"
                             +"<td></td>"
                             +"<td></td>"
                             +"</tr>"
                             +"</table>"
                             +"</div>"
                             +"<div class=\"col-xs-12 col-md-12  \">"
                             +"<span><font size=\"3\" color=\"black\">课程评价</font></span> "
                             +"</div>"
                             +"<div class=\"col-xs-12 col-md-12  \" >"
                             +"<table style=\"margin-left:10px\" class=\"table table-bordered\">"
                             +" <tr>"
                             +"<td><font size=\"2\" color=\"black\">学生打分</font></td>"
                             +"<td>"
                             +"<a class=\"show_score\" href=\"javascript:;\"  >"+list.stu_score+"</a>"
                             +"</td>"
                             +"<td><font size=\"2\" color=\"black\">学生评价</font></td>"
                             +"<td>"+list.teacher_comment+"</td>"
                             +"<td><font size=\"2\" color=\"black\">老师评价</font></td>"
                             +"<td>"+list.stu_point_performance+"</td>"
                             +"</tr>"
                             +"</table>"
                             +"</div>"
                             +"<div class=\"col-xs-12 col-md-12  \">"
                             +"<span><font size=\"3\" color=\"black\">作业情况</font></span> "
                             +"</div>"
                             +"<div class=\"col-xs-12 col-md-12  \" >"
                             +"<table style=\"margin-left:10px\" class=\"table table-bordered\">"
                             +" <tr>"
                             +"<td><font size=\"2\" color=\"black\">布置作业</font></td>"
                             +"<td>"
                             +"<a class=\"show_issue\" href=\"javascript:;\"   >"+list.issue_flag+"</a>"
                             +"</td>"
                             +"<td><font size=\"2\" color=\"black\">下载作业</font></td>"
                             +"<td>"+list.download_flag+"</td>"
                             +"<td><font size=\"2\" color=\"black\">提交情况</font></td>"
                             +"<td>"
                             +"<a class=\"show_commit\" href=\"javascript:;\"   >"+list.commit_flag+"</a>"
                             +"</td>"
                             +"<td><font size=\"2\" color=\"black\">是否批改</font></td>"
                             +"<td>"
                             +"<a class=\"show_check\" href=\"javascript:;\"   >"+list.check_flag+"</a>"
                             +"</td>"
                             +"<td><font size=\"2\" color=\"black\">成绩</font></td>"
                             +"<td>"+list.score+"</td>"
                             +"<td><font size=\"2\" color=\"black\">查看批改</font></td>"
                             +"<td>"+list.stu_check_flag+"</td>"
                             +"</tr>"
                             +"</table>"
                             +"</div>"
                             +"</div>");
            html_node.find(".show_lesson_video").on("click",function(){
                $.do_ajax( "/common/encode_text",{
                    "text" : lessonid
                }, function(ret){
                    // BootstrapDialog.alert("对外链接 : http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text  );
                    $.wopen("http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text);
                });
 
            });
            html_node.find(".show_cw_content").on("click",function(){
                var url = $(this).data("url");
                if(list.tea_cw_url){
                    $.wopen(url); 
                }
                
            });
            html_node.find(".show_issue").on("click",function(){
                if(list.issue_url){
                    $.wopen(list.issue_url_str); 
                }
                
            });
            html_node.find(".show_commit").on("click",function(){
                if(list.finish_url){
                    $.wopen(list.finish_url_str); 
                }
                
            });
            html_node.find(".show_check").on("click",function(){
                if(list.check_url){
                    $.wopen(list.check_url_str); 
                }
                
            });




            html_node.find(".show_score").on("click",function(){
                var title1 = "打分详情";
                var html_node1= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>类型</td><td>得分</td></tr><tr><td>上课效果</td><td>"+list.teacher_effect+"</td></tr><tr><td>课件质量</td><td>"+list.teacher_quality+"</td></tr><tr><td>课堂互动</td><td>"+list.teacher_interact+"</td></tr><tr><td>系统稳定性</td><td>"+list.stu_stability+"</td></tr></table></div>");

                var dlg1=BootstrapDialog.show({
                    title:title1,
                    message :  html_node1   ,
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

                dlg1.getModalDialog().css("width","400px");
 
                
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

            dlg.getModalDialog().css("width","1000px");

        });

        
    });
    $(".show_login_info").on("click",function(){
         var lessonid = $(this).data("lessonid");
         var userid = $(this).data("userid");
         var role = $(this).data("role");
        var title = "登录日志";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>角色</td><td>进出</td><td>时间</td></tr></table></div>");

        $.do_ajax('/ajax_deal2/get_lesson_opt_detail_info',{
            "lessonid" : lessonid,
            "userid"   : userid
        },function(resp) {
            var list = resp.data;
            $.each(list,function(i,item){
                html_node.find("table").append("<tr><td>"+role+"</td><td>"+item["opt_type_str"]+"</td><td>"+item["opt_time_str"]+"</td></tr>");
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

        dlg.getModalDialog().css("width","600px");


    });

    $(".show_stu_score_detail").on("click",function(){
        var effect = $(this).data("effect");
        var quality = $(this).data("quality");
        var interact = $(this).data("interact");
        var stability = $(this).data("stability");
        var title = "打分详情";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>类型</td><td>得分</td></tr><tr><td>上课效果</td><td>"+effect+"</td></tr><tr><td>课件质量</td><td>"+quality+"</td></tr><tr><td>课堂互动</td><td>"+interact+"</td></tr><tr><td>系统稳定性</td><td>"+stability+"</td></tr></table></div>");

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

        dlg.getModalDialog().css("width","400px");


    });

    $(".show_lesson_video").on("click",function(){
        var lessonid = $(this).data("lessonid");
        $.do_ajax( "/common/encode_text",{
            "text" : lessonid
        }, function(ret){
            // BootstrapDialog.alert("对外链接 : http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text  );
            $.wopen("http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text);
        });


    });

    $("#id_add_stu_score").on("click",function(){
        var opt_data = $(this).get_opt_data;
        var id_subject        = $("<select/>");  //选择考试科目
        var id_grade          = $("<select/>");  //年级
        var id_semester       = $("<select/>");  //学期
        var id_stu_score_type = $("<select/>");  //选择考试类型
        var id_score          = $("<input placeholder=\"输入考试成绩\" />");   //输入考试分数
        var id_total_score    = $("<input placeholder=\"输入满分分数\" />");   //输入考试总分
        var id_rank           = $("<input placeholder=\"输入班级排名 格式:1\" />");   //输入班级排名
        var id_grade_rank     = $("<input placeholder=\"输入年级排名 格式:2\" />");   //输入年级排名
        var id_grade_rank_num = $("<input placeholder=\"输入年级人数 格式:257\" />");   //输入年级人数
        var id_rank_num       = $("<input placeholder=\"输入班级人数 格式:26\" />");   //输入班级人数

        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\"> </a>   </div>");
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;
        $upload_link.attr('href',"");
        //$upload_link.attr('href',opt_data.from_url);

        Enum_map.append_option_list("subject", id_subject, true,[1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("stu_score_type", id_stu_score_type, true);
        Enum_map.append_option_list("grade",id_grade,true,[101,102,103,104,105,106,201,202,203,301,302,303]);
        Enum_map.append_option_list("semester",id_semester,true);

        var arr = [
            ["考试科目", id_subject],
            ["年级",    id_grade],
            ["学期",    id_semester],
            ["考试类型", id_stu_score_type],

            ["考试成绩", id_score],
            ["试卷总分",id_total_score],
            ["班级排名",id_rank],
            ["班级人数",id_rank_num],
            ["年级排名",id_grade_rank],
            ["年级人数",id_grade_rank_num],
        ];
        
        arr.push(['学生试卷',$upload_div]);
        $.show_key_value_table("增加考试记录", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                if(id_subject.val() <= 0){
                    alert("请选择考试科目");
                    return;
                }
                if(id_score.val() === ''){
                    alert("请输入考试成绩");
                    return;
                }
                if(id_total_score.val() === ''){
                    alert("请输入试卷总分");
                    return;
                }
                var rank="";
                if(id_rank_num.val()>0){
                    rank = id_rank.val()+"/"+id_rank_num.val();
                }else{
                    rank = id_rank.val();
                }
                var grade_rank="";
                if(id_grade_rank_num.val()>0){
                    grade_rank = id_grade_rank.val()+"/"+id_grade_rank_num.val();
                }else{
                    grade_rank = id_grade_rank.val();
                }
                

                
                $.do_ajax("/ajax_deal2/score_add_new",{
                    "userid"        : g_sid,
                    "create_time"   : '0',
                    "create_adminid": '1',

                    "subject"       : id_subject.val(),
                    "stu_score_type": id_stu_score_type.val(),
                    "stu_score_time": '0',
                    "score"         : id_score.val(),

                    "rank"          : rank,
                    "file_url"      : $upload_link.attr('href'),
                    "semester"      : id_semester.val(),
                    "total_score"   : id_total_score.val(),
                    "grade"         : id_grade.val(),
                    "grade_rank"    : grade_rank,
                    "status"        : 0,

                });
            }
        },function(){
            console.log(id_grade.val())
            id_grade.change(function(){
                console.log($(this).val());
            });
            $.custom_upload_file(
                "id_upload_from_url" ,
                true,function( up, info, file ){
                    var res = $.parseJSON(info);
                    var url=res.key;
                    $.do_ajax("/common_new/get_qiniu_download",{
                        "file_url" :res.key ,
                        "public_flag" :1,
                    }, function(resp){
                        $upload_link.attr("href", resp.url);
                        $upload_link.html("查看");
                    })
                },null,
                ["png","jpg","jpeg","zip","rar","gz","pdf","doc"] );
        })
        
    });

    $(".score_table_flag_show").on("click",function(){
        $(this).addClass('current_score');
        $(this).siblings().removeClass('current_score');
        $(this).addClass("btn-warning");
        $(this).siblings().removeClass('btn-warning');          
        window["g_load_data_flag"] = 0;
    //         var current_score_id =  $(".current_score").data("table_id");
    // alert(current_score_id);

        load_data();
        
    });
    $(".score_table_flag_show").each(function(){
        var table_id = $(this).data("table_id");       
        if(g_args.current_table_id==table_id){
            $(this).addClass("btn-warning");
            $(this).addClass("current_score");           
        }else{
            $(this).removeClass("btn-warning");
            $(this).removeClass("current_score");
        }
    });


    //var current_score_id =  $(".current_score").data("table_id");
    // alert(current_score_id);
    if(g_args.current_id==5){
        
        
        if(g_args.current_table_id==2){
            $(".score_table").show();
            $(".score_pic").hide();
        }else{
            $(".score_pic").show();
            $(".score_table").hide();
        }
    }
   






});
