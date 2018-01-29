/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-select_teacher_for_test_lesson.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    var refresh_flag = $("#id_refresh_flag").data("refresh_flag");
    $.reload_self_page({
		    teacher_info  : $('#id_teacher_info').val(),
		    identity      : $('#id_identity').val(),
		    gender        : $('#id_gender').val(),
		    tea_age       : $('#id_tea_age').val(),
		    teacher_type  : $('#id_teacher_type').val(),
		    teacher_tags  : $('#id_teacher_tags').val(),
		    lesson_tags   : $('#id_lesson_tags').val(),
		    teaching_tags : $('#id_teaching_tags').val(),
		    require_id    : g_args.require_id,
		    refresh_flag  : refresh_flag,
		    region_version: $('#id_region_version').val(),
        userid        : $('#id_userid').val(),
    });
}

$(function(){
    var require_info = $("#id_require_info").data();
    $("#id_refresh_flag").on("click",function(){
        $(this).data("refresh_flag",1);
        load_data();
    });

    Enum_map.append_option_list("identity",$("#id_identity"),true,[5,6,7,8]);
    Enum_map.append_option_list("gender",$("#id_gender"),true,[1,2]);
    Enum_map.append_option_list("tea_age",$("#id_tea_age"),true,[1,2,3,4]);
    Enum_map.append_option_list("teacher_type",$("#id_teacher_type"),true,[1,3]);
    Enum_map.append_option_list_by_not_id("region_version",$("#id_region_version"),true,[0]);
    $("#id_teacher_info").val(g_args.teacher_info);
    $("#id_identity").val(g_args.identity);
    $("#id_gender").val(g_args.gender);
    $("#id_tea_age").val(g_args.tea_age);
    $("#id_teacher_type").val(g_args.teacher_type);
    $("#id_teacher_tags").val(g_args.teacher_tags);
    $("#id_teaching_tags").val(g_args.teaching_tags);
    $("#id_lesson_tags").val(g_args.lesson_tags);
    $("#id_teacherid").val();
    $('#id_region_version').val(g_args.region_version);
    $('#id_refresh_flag').val(g_args.refresh_flag);
    $('#id_userid').val(g_args.userid);

    $('.opt-change').set_input_change_event(load_data);
    $("#id_lesson_time").datetimepicker();

    if(g_args.require_id==0){
        var notice_html = "请选择需要排课的试听需求！";
        $(".require_content").html(notice_html);
    }
    if(g_account_role!=12 && g_account_role!=3){
        var notice_html = "所在角色组没有权限！";
        $(".require_content").html(notice_html);
    }
    $(".require_content").show();

    $(".show_phone").on("click",function(){
        var phone = $(this).data("phone");
        BootstrapDialog.alert(phone);
    });

    $(".show_tea_free_time").on("click",function(){
	      var teacherid = $(this).data("teacherid");
        var jump_url = "/teacher_info_admin/free_time?teacherid="+teacherid;
        window.open(jump_url);
    });

    var no_select_teacher = function(){
        $(".red-border").each(function(){
            $(this).removeClass("red-border");
        });
    }

    var select_teacher = function(){
        var select_teacherid = parseInt($("#id_teacherid").val());
        $(".teacher-info").each(function(){
            var teacherid = $(this).data("teacherid");
            if(select_teacherid==teacherid){
                $(this).addClass("red-border");
                $("#id_teacher_info").data("select_flag",1);
            }
        });
    }
    no_select_teacher();
    select_teacher();

    var get_alert_info = function(){
        var show_title = "非待排课状态，若更换试听课，请取消课程，重新排课!";
        if(require_info.accept_status==1){
            show_title = "老师已经确认该课程，若更换试听课，请取消课程，重新排课!";
        }
        BootstrapDialog.alert(show_title);
    }

    var check_require_status = function(){
        if(require_info.test_lesson_student_status!=200 && require_info.test_lesson_student_status!=120 && require_info.accept_status==1){
            return true;
        }
        return false;
    }

    if(check_require_status()){
        $(".require_status").hide();
        $("#id_teacher_info").attr("readOnly","true");
        $("#id_lesson_time").attr("readOnly","true");
    }else{
        $("#id_teacher_info").keydown(function(event){
            var val = $(this).val();
            var select_flag = $(this).data("select_flag");
            //判断退格键是全部清空还是正常退格 0 正常退格 1 全部清空
	          if(event.keyCode==8 && select_flag==1){
                no_select_teacher();
                $("#id_teacher_info").val('');
                $("#id_teacherid").val(0);
                $(this).data("select_flag",0);
            }
            if(event.keyCode==13){
                load_data();
            }
        });
    }


    $(".opt-set-teacher").on("click",function(){
        if(!check_require_status()){
            var data = $(this).get_opt_data();
            no_select_teacher();
            var teacher_info = data.realname+"/"+data.phone;
            $(this).parents("tr").addClass("red-border");
            $("#id_teacherid").val(data.teacherid);
            $("#id_teacher_info").val(teacher_info);
            select_teacher();
        }else{
            get_alert_info();
        }
    });


    //排课
    $("#id_set_lesson_time").on("click",function(){
        var lesson_time  = $("#id_lesson_time").val();
        var teacherid    = $("#id_teacherid").val();
        var teacher_info = $("#id_teacher_info").val();

        if(teacherid==0 || teacher_info==""){
            BootstrapDialog.alert("请选择老师!");
        }else if(lesson_time==""){
            BootstrapDialog.alert("请选择时间!");
        }else{
            var notice_html = "确定排课老师为："+teacher_info+"时间为："+lesson_time+"？";
            BootstrapDialog.show({
	              title   : "信息确认",
	              message : notice_html,
	              buttons : [{
		                label  : "返回",
		                action : function(dialog) {
			                  dialog.close();
		                }
	              }, {
		                label    : "确认",
		                cssClass : "btn-warning",
		                action   : function(dialog) {
                        if(check_require_status()){
                            var do_post = function(){
                                $.do_ajax("/ss_deal/course_set_new",{
                                    'require_id'      : g_args.require_id,
                                    'grade'           : require_info.grade,
                                    'teacherid'       : teacherid,
                                    'lesson_start'    : lesson_time,
                                    'top_seller_flag' : require_info.seller_top_flag
                                });
                            };

                            var now        = (new Date()).getTime()/1000;
                            var start_time = $.strtotime(lesson_time);
                            if ( now > start_time ) {
                                BootstrapDialog.alert("上课时间比现在还小.");
                                return ;
                            } else if ( now + 5*3600  > start_time ) {
                                BootstrapDialog.confirm("上课时间离现在很近了,要提交吗?!",function(val){
                                    if(val) {
                                        do_post();
                                    }
                                });
                            }else{
                                do_post();
                            }
                        }
		                }
	              }]
            });
        }
    });

    //驳回
    $("#id_refund_lesson").on("click",function(){
        var nick        = require_info.nick;
        var subject_str = require_info.subject_str;
        var require_id  = g_args.require_id;
        var $input      = $("<input style=\"width:180px\"  placeholder=\"驳回理由\"/>");
        if(check_require_status()){
            $.show_input(
                nick+" : "+ subject_str+ ",要驳回, 不计算排课数?! ",
                "",function(val){
                    $.do_ajax("/ss_deal/set_no_accpect",{
                        'require_id'  : require_id,
                        'fail_reason' : val
                    });
                }, $input);
            $input.val("未排课,期待时间已到");
        }
    });




});
