/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_feedback-teacher_feedback_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      date_type_config : $('#id_date_type_config').val(),
			      date_type        : $('#id_date_type').val(),
			      opt_date_type    : $('#id_opt_date_type').val(),
			      start_time       : $('#id_start_time').val(),
			      end_time         : $('#id_end_time').val(),
			      teacherid        : $('#id_teacherid').val(),
			      lessonid         : $('#id_lessonid').val(),
			      status           : $('#id_status').val(),
			      feedback_type    : $('#id_feedback_type').val(),
			      del_flag         : $('#id_del_flag').val()
        });
    }

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

    Enum_map.append_option_list("feedback_type",$("#id_feedback_type"));
    Enum_map.append_option_list("boolean",$("#id_del_flag"));

    $('#id_del_flag').val(g_args.del_flag);
    $('#id_teacherid').val(g_args.teacherid);
	  $('#id_lessonid').val(g_args.lessonid);
	  $('#id_status').val(g_args.status);
	  $('#id_feedback_type').val(g_args.feedback_type);
    $.admin_select_user($("#id_teacherid"),"teacher",load_data);
	  $('.opt-change').set_input_change_event(load_data);

    $("#id_lesson").on("keypress",function(e){
        if (e.keyCode == 13){
            var id_lesson = $(this).val();
            if( id_lesson == "" ){
               BootstrapDialog.alert("请输入课程ID");
            }else{
                var url = "/teacher_feedback/teacher_feedback_list?lessonid="+id_lesson;
                window.location.href = url;
            }
        }
    });

    $("#id_search_lesson").on("click", function(){
        var id_lesson = $("#id_lesson").val();
        if( id_lesson == ""){
            BootstrapDialog.alert("请输入课程ID");
        }else{
            var url = "/teacher_feedback/teacher_feedback_list?lessonid="+id_lesson;
            window.location.href = url;
        }
    });

    $(".opt-edit").on("click",function(){
        var opt_data          = $(this).get_opt_data();
        var id                = opt_data.id;
        var feedback_type     = opt_data.feedback_type;
        var feedback_type_str = opt_data.feedback_type_str;
        var check_status      = opt_data.status;
        var check_time        = opt_data.check_time;
        var tea_reason        = opt_data.tea_reason;
        var back_reason       = opt_data.back_reason;
        var lessonid          = opt_data.lessonid;

        $.do_ajax("/teacher_feedback/get_teacher_feedback_lesson_info",{
            "lessonid"      : lessonid,
            "feedback_type" : feedback_type
        },function(result){
            var id_lesson_info       = $("<div />");
            var id_feedback_type_str = $("<div />");
            var id_tea_reason        = $("<div />");
            var id_check_status      = $("<select />");
            var id_back_reason       = $("<textarea />");
            var lesson_info          = result.data;

            Enum_map.append_option_list("check_status",id_check_status,true,[0,1,2]);
            id_feedback_type_str.html(feedback_type_str);
            id_tea_reason.html(tea_reason);
            id_back_reason.html(back_reason);
            id_check_status.val(check_status);

            var lesson_html =
                "老师信息 : "+lesson_info.teacher_money_type_str+"/"+lesson_info.level_str+
                "<br>课程时间 : "+lesson_info.lesson_time+"/"+lesson_info.lesson_type_str+
                "<br>真实时间 : "+lesson_info.real_lesson_time+
                "<br>课程课时 : "+lesson_info.lesson_count+
                "<br>累计课时 : "+lesson_info.already_lesson_count+
                "<br>全勤课次 : "+lesson_info.lesson_full_num+
                "<br>老师评价学生时间 : "+lesson_info.tea_rate_time_str+
                "<br>老师进入课程时间 : "+lesson_info.tea_attend_str+
                "<br>学生进入课程时间 : "+lesson_info.stu_attend_str+
                "<br>上传老师讲义时间 : "+lesson_info.tea_cw_upload_time_str+
                "<br>上传学生讲义时间 : "+lesson_info.stu_cw_upload_time_str;
            id_lesson_info.append(lesson_html);
            var arr = [
                ["课程信息",id_lesson_info],
                ["反馈类型",id_feedback_type_str],
                ["反馈原因",id_tea_reason],
                ["审核状态",id_check_status],
                ["审核原因",id_back_reason],
            ];
            $.show_key_value_table("处理反馈",arr,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
		            $.ajax({
			            type     :"post",
			            url      :"/teacher_feedback/check_teacher_feedback",
			            dataType :"json",
			            data : {
                            "id"            : id,
                            "lessonid"      : lessonid,
                            "check_status"  : id_check_status.val(),
                            "back_reason"   : id_back_reason.val(),
                            "feedback_type" : feedback_type,
                            "check_time"    : check_time,
                        },
                        success : function(result){
                            if(result.ret<0){
                                BootstrapDialog.alert(result.info);
                            }else{
                                window.location.reload();
                            }
			            }
                    });
                }
            });
       });
    });

    $(".opt-delete").on("click",function(){
        var data = $(this).get_opt_data();
        var id_del_flag = $("<select/>");
        var arr = [
            ["是否删除",id_del_flag]
        ];

        Enum_map.append_option_list("boolean",id_del_flag,true);
        id_del_flag.val(data.del_flag);

        $.show_key_value_table("删除确认",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_feedback/delete_teacher_feedback_info",{
                    "id"       : data.id,
                    "status"   : data.status,
                    "del_flag" : id_del_flag.val(),
                },function(result){
                    if(result.ret<0){
                        BootstrapDialog.alert(result.info);
                    }else{
                        window.location.reload();
                    }
                });

            }
        });
    });

    $(".opt-lesson_info").on("click",function(){
        var lessonid = $(this).get_opt_data("lessonid");
        var url="/tea_manage/lesson_list?lessonid="+lessonid;
        if(assistantid>0){
            url="/tea_manage/lesson_list_ass?lessonid="+lessonid;
        }
        window.open(url);
    });

    $(".opt-full_lesson").on("click",function(){
        var data             = $(this).get_opt_data();
        var month_start_str  = data.month_start_str;
        var lesson_start_str = data.lesson_start_str;
        var teacherid        = data.teacherid;

        var url="/tea_manage/lesson_list?teacherid="+teacherid
            +"&opt_date_type=3&start_time="+month_start_str
            +"&end_time="+lesson_start_str;
        window.open(url);
    });

    $(".opt-log-list").on("click", function () {
        var lessonid     = $(this).parent().data("lessonid");
        var teacherid    = $(this).parent().data("teacherid");
        var stu_id       = $(this).parent().data("studentid");
        var lesson_start = $(this).parent().data("lesson_start");
        var lesson_end   = $(this).parent().data("lesson_end");
        var lesson_type  = $(this).get_opt_data("lesson_type");

        var html_node = $.obj_copy_node("#id_lesson_log");

        $.do_ajax("/lesson_manage/get_lesson_user_list_for_login_log", {
            "lessonid": lessonid
        }, function (ret) {
            var html_str = "";
            $.each(ret.list, function () {
                var userid  = this[0];
                var name    = this[1];
                html_str   += "<option value=\"" + userid + "\">" + name + "</option>";
            });
            html_node.find(".opt-userid").html(html_str);
        });


        html_node.find(".form-control").on("change", function () {
            var userid = html_node.find(".opt-userid").val();
            var server_type = html_node.find(".opt-server-type").val();
            load_data_ex(lessonid, userid, server_type);
        });

        BootstrapDialog.show({
            title: "进出列表",
            message: html_node,
            closable: true
        });

        var load_data_ex = function (lessonid, userid, server_type) {
            $.ajax({
                type: "post",
                url: "/supervisor/lesson_get_log",
                dataType: "json",
                data: {
                    'lessonid'     : lessonid,
                    "userid"       : userid,
                    "server_type"  : server_type,
                    "teacher_id"   : teacherid,
                    "stu_id"       : stu_id,
                    "lesson_start" : lesson_start,
                    "lesson_end"   : lesson_end
                },
                success: function (result) {
                    if (result['ret'] == 0) {
                        var data = result['data'];

                        var html_str = "";
                        $.each(data, function (i, item) {
                            var cls = "warning";
                            if (item.opt_type == "login") {
                                cls = "success";
                            }
                            if (item.opt_type == "register") {
                                cls = "warning";
                            }

                            if (item.opt_type == "logout") {
                                cls = "danger";
                            }

                            var rule_str = "";
                            if (item.userid == stu_id) {
                                rule_str = "学生";
                            } else if (item.userid == teacherid) {
                                rule_str = "老师";
                            }

                            html_str += "<tr class=\"" + cls + "\" > <td>" + item.opt_time
                                + "<td>" + rule_str
                                + "<td>" + item.userid
                                + "<td>" + item.server_type
                                + "<td>" + item.opt_type
                                + "<td>" + item.server_ip
                                + "</tr>";
                        });
                        html_node.find(".data-body").html(html_str);
                    }
                }
            });
        };
        load_data_ex(lessonid, -1, -1);
    });

    $(".opt-teacher_money").on("click",function(){
        var teacherid = $(this).get_opt_data("teacherid");
        var url = "/user_manage_new/tea_wages_info?teacherid="+teacherid;
        window.open(url);
    });

    $(".opt-trial_reward").on("click",function(){
	    var data = $(this).get_opt_data();
        var url  = "/user_manage_new/teacher_trial_reward_list?teacherid="+data.teacherid;
        window.open(url);
    });

    $(".opt-change_type").on("click",function(){
	    var data = $(this).get_opt_data();
        var id_feedback_type = $("<select/>");
        var arr = [
            ["反馈类型",id_feedback_type]
        ];
        Enum_map.append_option_list("feedback_type",id_feedback_type);

        $.show_key_value_table("更改反馈类型",arr,{
            label:"确认",
            cssClass:"btn-warning",
            action:function(dialog) {
                $.do_ajax("/teacher_feedback/update_teacher_feedback_type",{
                    "id"            : data.id,
                    "feedback_type" : id_feedback_type.val()
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        });
    });

    $(".opt-add_reward_90").on("click",function(){
	      var data = $(this).get_opt_data();
        BootstrapDialog.show({
	          title   : "操作确认",
	          message : "是否添加此老师90分钟的补偿金额?",
	          buttons : [{
		            label  : "返回",
		            action : function(dialog) {
			              dialog.close();
		            }
	          },{
		            label    : "确认",
		            cssClass : "btn-warning",
		            action   : function(dialog) {
                    $.do_ajax("/teacher_money/add_teacher_reward",{
                        'add_time'   : data.lesson_time,
                        "money_info" : data.lessonid,
                        "type"       : 3,
                        "teacherid"  : data.teacherid
                    },function(result){
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    });
		            }
	          }]
        });
    });

    $(".opt-update-lesson-info").on("click",function(){
	      var data            = $(this).get_opt_data();
        var id_grade        = $("<select/>");
        var id_lesson_count = $("<input/>");

        Enum_map.append_option_list("grade",id_grade,true,[101,102,103,104,105,106,201,202,203,301,302,303]);
        var arr = [
            ["年级",id_grade],
            ["课时(不填则不更改)",id_lesson_count],
        ];
        id_grade.val(data.grade);

        $.show_key_value_table("更改课程信息",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/lesson_info/update_lesson_info",{
                    "lessonid"     : data.lessonid,
                    "grade"        : id_grade.val(),
                    "lesson_count" : id_lesson_count.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        },function(){
            id_grade.val(data.grade);
        });
    });

    $(".opt-check_trial_lesson").on("click",function(){
        var opt_data = $(this).get_opt_data();
	    $.do_ajax("/teacher_feedback/check_teacher_trial_lesson",{
            "teacherid" : opt_data.teacherid,
            "lessonid"  : opt_data.lessonid,
        },function(result){
            if(result.ret==0){
                window.location.reload();
            }else{
                BootstrapDialog.alert(result.info);
            }
        })

    });

    $(".opt-reset_lesson_money").on("click",function(){
        var data = $(this).get_opt_data();

	      BootstrapDialog.show({
	          title   : "重置课程金额",
	          message : "是否重置课程金额",
	          buttons : [{
		            label  : "返回",
		            action : function(dialog) {
			              dialog.close();
		            }
	          }, {
		            label    : "确认",
		            cssClass : "btn-warning",
		            action   : function(dialog) {
                    $.do_ajax("/lesson_info/reset_lesson_money",{
                        "lessonid" : data.lessonid
                    },function(result){
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    })
		            }
	          }]
        });
    });

});
