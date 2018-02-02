/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-index.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page({
            need_test_lesson_flag    : $('#id_need_test_lesson_flag').val(),
            teacher_money_type       : $("#id_teacher_money_type").val(),
            level                    : $("#id_level").val(),
            is_new_teacher           : $('#id_is_new_teacher').val(),
            textbook_type            : $('#id_textbook_type').val(),
            is_good_flag             : $('#id_is_good_flag').val(),
            grade_part_ex            : $('#id_grade_part_ex').val(),
            teacherid                : $('#id_teacherid').val(),
            free_time                : $('#id_free_time').val(),
            subject                  : $('#id_subject').val(),
            second_subject           : $('#id_second_subject').val(),
            gender                   : $('#id_gender').val(),
            is_test_user             : $('#id_test_user').val(),
            is_freeze                : $('#id_is_freeze').val(),
            is_record_flag           : $('#id_is_record_flag').val(),
            limit_plan_lesson_type   : $('#id_limit_plan_lesson_type').val(),
            address                  : $('#id_address').val(),
            test_lesson_full_flag    : $('#id_test_lesson_full_flag').val(),
            train_through_new        : $('#id_train_through_new').val(),
            lesson_hold_flag         : $('#id_lesson_hold_flag').val(),
            test_transfor_per        : $('#id_test_transfor_per').val(),
            week_liveness            : $('#id_week_liveness').val(),
            interview_score          : $('#id_interview_score').val(),
            second_interview_score   : $('#id_second_interview_score').val(),
            lesson_hold_flag_adminid : $('#id_lesson_hold_flag_adminid').val(),
            is_quit                  : $('#id_is_quit').val(),
            set_leave_flag           : $('#id_set_leave_flag').val(),
            teacher_type             : $('#id_teacher_type').val(),
            teacher_ref_type         : $('#id_teacher_ref_type').val(),
            reference_teacherid      : $('#id_reference_teacherid').val(),
			      have_wx                  : $('#id_have_wx').val(),
            grade_plan               : $('#id_grade_plan').val(),
			      subject_plan             : $('#id_subject_plan').val(),
			      fulltime_teacher_type    : $('#id_fulltime_teacher_type').val(),
            month_stu_num            : $('#id_month_stu_num').val(),
			      record_score_num         : $('#id_record_score_num').val(),
			      identity                 : $('#id_identity').val(),
			      plan_level               : $('#id_plan_level').val(),
			      teacher_textbook         : $('#id_teacher_textbook').val()
        });
    }

    $('#id_teacherid').val(g_args.teacherid);
    $('#id_reference_teacherid').val(g_args.reference_teacherid);
    Enum_map.append_option_list("teacher_money_type", $("#id_teacher_money_type"),false,[0,6,7] );
    Enum_map.append_option_list("teacher_type", $("#id_teacher_type") );
    Enum_map.append_option_list("teacher_ref_type", $("#id_teacher_ref_type") );
    Enum_map.append_option_list("level", $("#id_level") );
    Enum_map.append_option_list("test_user", $("#id_test_user"));
    Enum_map.append_option_list("textbook_type", $("#id_textbook_type") );
    Enum_map.append_option_list("teacher_is_good", $("#id_is_good_flag") );
    Enum_map.append_option_list("gender", $("#id_gender") );
    Enum_map.append_option_list("grade_part_ex", $("#id_grade_part_ex") );
    Enum_map.append_option_list("subject", $("#id_subject") );
    Enum_map.append_option_list("subject", $("#id_second_subject") );
    Enum_map.append_option_list("boolean", $("#id_is_freeze") );
    Enum_map.append_option_list("boolean", $("#id_is_record_flag") );
    Enum_map.append_option_list("boolean", $("#id_is_quit") );
    Enum_map.append_option_list("boolean", $("#id_set_leave_flag") );
    Enum_map.append_option_list("boolean", 	$('#id_have_wx') );
    Enum_map.append_option_list("grade", $("#id_grade_plan"),false,[101,102,103,104,105,106,201,202,203,301,302,303] );
    Enum_map.append_option_list("subject", $("#id_subject_plan") );
    Enum_map.append_option_list("fulltime_teacher_type", $("#id_fulltime_teacher_type"),false,[1,2] );
    Enum_map.append_option_list("identity", $("#id_identity") );
    Enum_map.append_option_list("region_version", $("#id_teacher_textbook") );

    $('#id_teacher_type').val(g_args.teacher_type);
    $('#id_teacher_ref_type').val(g_args.teacher_ref_type);
    $('#id_lesson_hold_flag').val(g_args.lesson_hold_flag);
    $('#id_teacher_money_type').val(g_args.teacher_money_type);
    $('#id_level').val(g_args.level);
    $('#id_test_user').val(g_args.is_test_user);
    $('#id_textbook_type').val(g_args.textbook_type);
    $('#id_is_good_flag').val(g_args.is_good_flag);
    $('#id_is_new_teacher').val(g_args.is_new_teacher);
    $('#id_gender').val(g_args.gender);
    $('#id_free_time').val(g_args.free_time);
    $('#id_grade_part_ex').val(g_args.grade_part_ex);
    $('#id_subject').val(g_args.subject);
    $('#id_second_subject').val(g_args.second_subject);
    $('#id_address').val(g_args.address);
    $('#id_is_freeze').val(g_args.is_freeze);
    $('#id_is_record_flag').val(g_args.is_record_flag);
    $('#id_limit_plan_lesson_type').val(g_args.limit_plan_lesson_type);
    $('#id_test_lesson_full_flag').val(g_args.test_lesson_full_flag);
    $('#id_test_transfor_per').val(g_args.test_transfor_per);
    $('#id_week_liveness').val(g_args.week_liveness);
    $('#id_interview_score').val(g_args.interview_score);
    $('#id_is_quit').val(g_args.is_quit);
    $('#id_second_interview_score').val(g_args.second_interview_score);
    $('#id_lesson_hold_flag_adminid').val(g_args.lesson_hold_flag_adminid);
    $('#id_set_leave_flag').val(g_args.set_leave_flag);
	  $('#id_grade_plan').val(g_args.grade_plan);
	  $('#id_subject_plan').val(g_args.subject_plan);
	  $('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);
	  $('#id_month_stu_num').val(g_args.month_stu_num);
	  $('#id_record_score_num').val(g_args.record_score_num);
	  $('#id_identity').val(g_args.identity);
	  $('#id_plan_level').val(g_args.plan_level);
	  $('#id_teacher_textbook').val(g_args.teacher_textbook);
	  $('#id_train_through_new').val(g_args.train_through_new);

    $.admin_select_user($("#id_teacherid"), "teacher", load_data);
    $.admin_select_user($("#id_reference_teacherid"), "teacher", load_data);
    Enum_map.append_option_list("boolean", $("#id_need_test_lesson_flag") );
    $('#id_need_test_lesson_flag').val(g_args.need_test_lesson_flag);
	  $('#id_have_wx').val(g_args.have_wx);

    if(g_args.teacherid>0){
        $("#lesson_plan_week").show();
    }

    $("#id_free_time").on("click",function(){
        var id_start=$("<input/>");
        var id_end=$("<input/>");
        id_start.datetimepicker({
        lang:'ch',
        timepicker:true,
        format:'Y-m-d H:i',
            "onChangeDateTime" : function() {
            }
      });

        id_end.datetimepicker({
        lang:'ch',
        timepicker:true,
        format:'Y-m-d H:i',
            "onChangeDateTime" : function() {
            }
      });


        var arr=[
            ["开始时间", id_start],
            ["结束时间", id_end],
        ];
        $.show_key_value_table("选择时间", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                if(id_start.val()=="" || id_end.val()==""){
                    alert("请选择时间!");
                    return;
                }
                var time = id_start.val()+","+id_end.val();
                $("#id_free_time").val(time);
                $.reload_self_page ( {
                    teacher_money_type    : $("#id_teacher_money_type").val(),
                    teacherid             : $('#id_teacherid').val(),
                    textbook_type         : $('#id_textbook_type').val(),
                    is_good_flag          : $('#id_is_good_flag').val(),
                    is_new_teacher        : $('#id_is_new_teacher').val(),
                    gender                : $('#id_gender').val(),
                    free_time             : $('#id_free_time').val(),
                    need_test_lesson_flag : $('#id_need_test_lesson_flag').val()
                });

                dialog.close();
            }
        });
    });

    $("#id_add_teacher").on("click",function(){
        var id_tea_nick           = $("<input/>");
        var id_teacher_money_type = $("<select/>");
        var id_level              = $("<select/>");
        var id_teacher_ref_type   = $("<select/>");
        var id_identity           = $("<select/>");
        var id_gender             = $("<select/>");
        var id_birth              = $("<input/>");
        var id_work_year          = $("<input/>");
        var id_phone              = $("<input/>");
        var id_phone_spare        = $("<input/>");
        var id_email              = $("<input/>");
        var id_address            = $("<input/>");
        var id_school             = $("<input/>");
        var id_subject            = $("<select/>");
        var id_grade_part_ex      = $("<select/>");
        var id_interview_assess   = $("<textarea/>")
        var id_teacher_type       = $("<select/>");
        var id_trial_lecture_is_pass = $("<select/>");
        var id_train_through_new     = $("<select/>");
        var id_is_test_user          = $("<select/>");

        Enum_map.append_option_list("teacher_money_type", id_teacher_money_type );
        Enum_map.append_option_list("level", id_level );
        Enum_map.append_option_list("teacher_ref_type", id_teacher_ref_type,true);
        Enum_map.append_option_list("identity", id_identity,true,[0,5,6,7,8]);

        Enum_map.append_option_list("gender", id_gender, true );
        Enum_map.append_option_list("subject", id_subject, true );
        Enum_map.append_option_list("grade_part_ex", id_grade_part_ex, true );
        Enum_map.append_option_list("teacher_type", id_teacher_type, true );
        Enum_map.append_option_list("boolean", id_trial_lecture_is_pass, true );
        Enum_map.append_option_list("boolean", id_train_through_new, true );
        Enum_map.append_option_list("boolean", id_is_test_user, true );

        id_work_year.val("1");
        id_birth.val("1989-01-01");

        id_birth.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            "onChangeDateTime" : function() {
            }
        });

        var arr = [
            ["电话", id_phone],
            ["备用电话", id_phone_spare],
            ["姓名", id_tea_nick],
            ["性别", id_gender],
            ["工资类别", id_teacher_money_type],
            ["等级", id_level],
            ["推荐人类型", id_teacher_ref_type],
            ["老师类型", id_teacher_type],
            ["身份", id_identity],
            ["出生年月", id_birth],
            ["工作年限", id_work_year],
            ["电子邮件", id_email],
            ["所在地", id_address],
            ["学校", id_school],
            ["年级段", id_grade_part_ex],
            ["第一科目", id_subject],
            ["试讲是否通过", id_trial_lecture_is_pass],
            ["培训是否通过", id_train_through_new],
            ["是否为测试账号", id_is_test_user],
            ["面试评价", id_interview_assess],
        ];

        $.show_key_value_table("新增老师", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var birth = ""+id_birth.val();
                birth     = birth.substr(0,4)+birth.substr(5,2)+birth.substr(8,2);

                $.do_ajax('/tea_manage/add_teacher',{
                    "tea_nick"              : id_tea_nick.val(),
                    "gender"                : id_gender.val(),
                    "level"                 : id_level.val(),
                    "teacher_money_type"    : id_teacher_money_type.val(),
                    "teacher_ref_type"      : id_teacher_ref_type.val(),
                    "identity"              : id_identity.val(),
                    "birth"                 : birth,
                    "work_year"             : id_work_year.val(),
                    "phone"                 : id_phone.val(),
                    "phone_spare"           : id_phone_spare.val(),
                    "email"                 : id_email.val(),
                    "address"               : id_address.val(),
                    "school"                : id_school.val(),
                    "subject"               : id_subject.val(),
                    "interview_access"      : id_interview_assess.val(),
                    "grade_part_ex"         : id_grade_part_ex.val(),
                    "teacher_type"          : id_teacher_type.val(),
                    "trial_lecture_is_pass" : id_trial_lecture_is_pass.val(),
                    "train_through_new"     : id_train_through_new.val(),
                    "is_test_user"          : id_is_test_user.val(),
                });
            }
        });
    });

    $('.opt-change').set_input_change_event(load_data);
    $(".opt-edit").on("click",function(){
        var opt_data                  = $(this).get_opt_data();
        var id_tea_nick               = $("<input/>");
        var id_phone_spare            = $("<input/>");
        var id_teacher_money_type     = $("<select/>");
        var id_level                  = $("<select/>");
        var id_gender                 = $("<select/>");
        var id_birth                  = $("<input/>");
        var id_age                    = $("<input/>");
        var id_work_year              = $("<input/>");
        var id_email                  = $("<input/>");
        var id_realname               = $("<input/>");
        var id_base_intro             = $("<textarea/>");
        var id_need_test_lesson_flag  = $("<select/>");
        var id_wx_openid              = $("<input/>");
        var id_address                = $("<input/>");
        var id_school                 = $("<input/>");
        var id_identity               = $("<select/>");
        var id_subject                = $("<select/>");
        var id_second_subject         = $("<select/>");
        var id_grade_part_ex          = $("<select/>");
        var id_second_grade_part_ex   = $("<select/>");

        Enum_map.append_option_list("gender", id_gender, true );
        Enum_map.append_option_list("level", id_level, true );
        Enum_map.append_option_list("teacher_money_type", id_teacher_money_type, true );
        Enum_map.append_option_list("boolean", id_need_test_lesson_flag,true );
        Enum_map.append_option_list("identity", id_identity, true );
        Enum_map.append_option_list("subject", id_subject, true );
        Enum_map.append_option_list("subject", id_second_subject, true );
        Enum_map.append_option_list("grade_part_ex", id_grade_part_ex, true );
        Enum_map.append_option_list("grade_part_ex", id_second_grade_part_ex, true );

        id_birth.datetimepicker({
            lang       : 'ch',
            timepicker : false,
            format     : 'Y-m-d',
            "onChangeDateTime" : function() {
            }
        });

        id_tea_nick.val(opt_data.nick);
        id_gender.val(opt_data.gender);
        var birth = ""+opt_data.birth;
        birth= birth.substr(0,4)+"-"+ birth.substr(4,2) + "-"+ birth.substr(6,2) ;
        id_birth.val( birth );
        id_work_year.val(opt_data.work_year );
        id_realname.val(opt_data.realname );
        id_phone_spare.val(opt_data.phone_spare);
        id_email.val(opt_data.email);
        id_teacher_money_type.val(opt_data.teacher_money_type);
        id_level.val(opt_data.level);
        id_base_intro.val(opt_data.base_intro );
        id_need_test_lesson_flag.val(opt_data.need_test_lesson_flag );
        id_wx_openid.val(opt_data.wx_openid);
        id_address.val(opt_data.address);
        id_school.val(opt_data.school);
        id_identity.val(opt_data.identity);
        id_subject.val(opt_data.subject);
        id_second_subject.val(opt_data.second_subject);
        id_grade_part_ex.val(opt_data.grade_part_ex);
        id_second_grade_part_ex.val(opt_data.second_grade);
        id_age.val(opt_data.age);

        var arr=[
            ["昵称", id_tea_nick],
            ["姓名", id_realname],
            ["备用手机", id_phone_spare],
            ["性别", id_gender],
            ["年龄", id_age],
            ["出生年月", id_birth],
            ["工作年限", id_work_year],
            ["所在地", id_address],
            ["学校", id_school],
            ["老师身份", id_identity],
            ["第一科目", id_subject],
            ["第一科目对应年级段", id_grade_part_ex],
            ["第二科目", id_second_subject],
            ["第二科目对应年级段", id_second_grade_part_ex],
            ["电子邮件", id_email],
            ["个人介绍", id_base_intro],
            ["是否需要试听课", id_need_test_lesson_flag],
            ["微信openid", id_wx_openid],
        ];

        $.show_key_value_table("修改老师信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var birth=""+ id_birth.val();
                birth = birth.substr(0,4)+birth.substr(5,2)+birth.substr(8,2);
                $.do_ajax( '/tea_manage_new/update_teacher_info',{
                    "teacherid"             : opt_data.teacherid,
                    "tea_nick"              : id_tea_nick.val(),
                    "realname"              : id_realname.val(),
                    "phone_spare"           : id_phone_spare.val(),
                    "gender"                : id_gender.val(),
                    "age"                   : id_age.val(),
                    "birth"                 : birth,
                    "work_year"             : id_work_year.val(),
                    "email"                 : id_email.val(),
                    "base_intro"            : id_base_intro.val(),
                    "need_test_lesson_flag" : id_need_test_lesson_flag.val(),
                    "address"               : id_address.val(),
                    "school"                : id_school.val(),
                    "identity"              : id_identity.val(),
                    "subject"               : id_subject.val(),
                    "second_subject"        : id_second_subject.val(),
                    "grade_part_ex"         : id_grade_part_ex.val(),
                    "second_grade"          : id_second_grade_part_ex.val(),
                    "wx_openid"             : id_wx_openid.val()
                });
            }
        });
    });

    $(".opt-change-lesson-num").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id_limit_day_lesson_num =$("<input/>");
        var id_limit_week_lesson_num =$("<input/>");
        var id_limit_month_lesson_num =$("<input/>");
        var id_saturday_lesson_num =$("<input/>");
        var id_week_lesson_count =$("<input/>");
        var id_seller_require_flag=$("<select/>");
        Enum_map.append_option_list("boolean", id_seller_require_flag, true );
        id_seller_require_flag.val(0);

        id_limit_day_lesson_num.val(opt_data.limit_day_lesson_num);
        id_limit_week_lesson_num.val(opt_data.limit_week_lesson_num);
        id_limit_month_lesson_num.val(opt_data.limit_month_lesson_num);
        id_saturday_lesson_num.val(opt_data.saturday_lesson_num);
        id_week_lesson_count.val(opt_data.week_lesson_count);

        var arr=[
            ["每日最大排课数", id_limit_day_lesson_num],
            ["每周最大排课数", id_limit_week_lesson_num],
            ["每月最大排课数", id_limit_month_lesson_num],
            ["教研老师周六可排课时", id_saturday_lesson_num],
            ["教研周课时上限", id_week_lesson_count],
            ["是否CC要求",id_seller_require_flag]
        ];
        $.show_key_value_table("修改排课数量", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                $.do_ajax( '/tea_manage_new/update_teacher_lesson_num',
                           {
                               "teacherid"          : opt_data.teacherid,
                               "limit_day_lesson_num" :id_limit_day_lesson_num.val(),
                               "limit_week_lesson_num" :id_limit_week_lesson_num.val(),
                               "limit_month_lesson_num" :id_limit_month_lesson_num.val(),
                               "saturday_lesson_num" :id_saturday_lesson_num.val(),
                               "week_lesson_count" :id_week_lesson_count.val(),
                               "seller_require_flag" :id_seller_require_flag.val()
                           });
            }
        });


    });

    $(".opt-change-good-teacher").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id_is_good_flag = $("<select/>");
        Enum_map.append_option_list("boolean", id_is_good_flag, true );
        var arr=[
            ["优秀老师", id_is_good_flag]
        ];
        id_is_good_flag.val(opt_data.is_good_flag);
        $.show_key_value_table("设置优秀老师", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                $.do_ajax( '/tea_manage_new/update_is_good_flag',
                           {
                               "teacherid"          : opt_data.teacherid,
                               "is_good_flag"       : id_is_good_flag.val()
                           });
            }
        });


    })

    $(".opt-tea-note").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var id_tea_note=$("<textarea/>");

        id_tea_note.val(opt_data.tea_note);

        var arr=[
            ["教务备注", id_tea_note]
        ];
        $.show_key_value_table("修改教务备注", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                $.do_ajax( '/tea_manage_new/update_tea_note',{
                    "teacherid" : opt_data.teacherid,
                    "tea_note"  : id_tea_note.val()
                });
            }
        });
    });

    $(".opt-interview-assess").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var id_interview_assess=$("<textarea/>");

        id_interview_assess.val(opt_data.interview_access);

        var arr=[
            ["面试评价", id_interview_assess]
        ];

        $.show_key_value_table("修改面试评价", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                $.do_ajax( '/tea_manage_new/update_interview_assess',{
                    "teacherid"        : opt_data.teacherid,
                    "interview_access" : id_interview_assess.val()
                });
            }
        });
    });

    $.each( $(".opt-show-lessons"), function(i,item ){
        $(item).admin_select_teacher_free_time({
            "teacherid" : $(item).get_opt_data("teacherid")
        });
    });
    $.each( $(".opt-show-lessons-new"), function(i,item ){
        $(item).admin_select_teacher_free_time_new({
            "teacherid" : $(item).get_opt_data("teacherid")
        });
    });

    $(".opt-set-tmp-passwd").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var id_tmp_passwd = $("<input/>");
        id_tmp_passwd.val("123456");

        var arr=[
            ["姓名",  opt_data.realname ],
            ["电话",  opt_data.phone ],
            ["临时密码", id_tmp_passwd ],
        ];
        $.show_key_value_table("临时密码", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
            $.ajax({
              type     :"post",
              url      :"/user_manage/set_dynamic_passwd",
              dataType :"json",
              data     :{
                        "phone"  : opt_data.phone,
                        "passwd" : id_tmp_passwd.val(),
                        "role"   : 2
                    },
                    success : function(result){
                        BootstrapDialog.alert(result['info']);
                        window.location.reload();
              }
                });
            }
        });
    });

    $(".opt-set-grade-range").on("click", function(){
        var opt_data = $(this).get_opt_data();
      /*  if(opt_data.second_subject >0){
            alert("抱歉,有第二科目的老师暂时不能设置新版年级段");
            return;
        }*/
        var id_grade_start = $("<select/>");
        var id_grade_end   = $("<select/>");

        Enum_map.append_option_list("grade_range", id_grade_start, true );
        Enum_map.append_option_list("grade_range", id_grade_end, true );

        var arr=[
            ["起始年级段",  id_grade_start ],
            ["结束年级段",  id_grade_end ],
        ];
        id_grade_start.val(opt_data.grade_start);
        id_grade_end.val(opt_data.grade_end);
        $.show_key_value_table("设置新版年级段", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
            $.ajax({
              type     :"post",
              url      :"/tea_manage_new/set_grade_range",
              dataType :"json",
              data     :{
                        "teacherid"   : opt_data.teacherid,
                        "grade_start" : id_grade_start.val(),
                        "grade_end"   : id_grade_end.val()
                    },
                    success : function(result){
                        BootstrapDialog.alert(result['info']);
                        if(result['ret']==0){
                            window.location.reload();
                        }
              }
                });
            }
        });
    });

    /**
     * 老师创建会议权限
     */
    var get_create_meeting = function(teacherid) {
        $.getJSON('/tea_manage/get_create_meeting', {
            'teacherid': teacherid
        }, function(result){
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
            } else {
                var message = '';
                if (result['create_meeting'] == 0) {
                    message = '<div class="text-center"><h3>开启</h3><br>当前老师创建会议权限</div>';
                } else {
                    message = '<div class="text-center"><h3>取消</h3><br>当前老师创建会议权限</div>';
                }

                BootstrapDialog.show({
                  title: "开启会议权限",
                  message : message,
                  buttons: [{
                    label: '返回',
                    action: function(dialog) {
                      dialog.close();
                    }
                  }, {
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                            set_create_meeting(teacherid);
                      dialog.close();
                    }
                  }]
                });
            }
        });
    };

    var set_create_meeting = function(teacherid) {
        $.getJSON('/tea_manage/set_create_meeting', {
            'teacherid': teacherid
        }, function(result){
            BootstrapDialog.alert(result['info']);
        });
    };

    $(".opt-level").on("click",function(){
        var opt_data = $(this).get_opt_data();
        set_teacher_level(opt_data);
    });

    var set_teacher_level = function(opt_data){
        var id_teacher_money_type = $("<select/>");
        var id_level              = $("<select/>");
        var id_start_time         = $("<input/>");

        Enum_map.append_option_list("teacher_money_type", id_teacher_money_type, true,[0,6,7] );

        id_teacher_money_type.val(opt_data.teacher_money_type);
        id_start_time.datetimepicker({
            datepicker : true,
            timepicker : false,
            format     : 'Y-m-d'
        });
        id_start_time.val(opt_data.lesson_confirm_start_time );

        var arr = [
            ["工资类别", id_teacher_money_type],
            ["等级", id_level],
            ["时间不填则不会重置课程时间",""],
            ["重置课程开始时间", id_start_time],
        ];

        $.show_key_value_table("修改等级", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax('/tea_manage_new/update_teacher_level',{
                    "teacherid"          : opt_data.teacherid,
                    "start_time"         : id_start_time.val(),
                    "level"              : id_level.val(),
                    "teacher_money_type" : id_teacher_money_type.val()
                });
            }
        },function(){
            var reset_level_map = function(){
                id_level.empty();
                if(id_teacher_money_type.val()==6){
                    Enum_map.append_option_list("new_level", id_level, true );
                }else{
                    Enum_map.append_option_list("level", id_level, true );
                }
                id_level.val(opt_data.level);
            }
            reset_level_map();
            id_teacher_money_type.on("change",function(){
                reset_level_map();
            });
        });
    }

    $(".opt-trial-pass").on("click",function(){
        var opt_data = $(this).get_opt_data();
        update_tea_pass_info(opt_data);
    });

    var update_tea_pass_info = function(opt_data){
         var id_trial_lecture_is_pass = $("<select/>");
        var id_train_through_new     = $("<select/>");
        var id_wx_use_flag           = $("<select/>");
        Enum_map.append_option_list("boolean", id_trial_lecture_is_pass, true );
        Enum_map.append_option_list("boolean", id_train_through_new, true );
        Enum_map.append_option_list("boolean", id_wx_use_flag, true );

        id_trial_lecture_is_pass.val(opt_data.trial_lecture_is_pass);
        id_train_through_new.val(opt_data.train_through_new);
        id_wx_use_flag.val(opt_data.wx_use_flag);
        var arr=[
            ["试讲通过",id_trial_lecture_is_pass],
            ["培训通过",id_train_through_new],
            ["微信老师帮使用权限",id_wx_use_flag]
        ];
        $.show_key_value_table("试讲通过", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/tea_manage_new/update_teacher_trial_lecture_is_pass', {
                    "teacherid"             : opt_data.teacherid,
                    "trial_lecture_is_pass" : id_trial_lecture_is_pass.val(),
                    "train_through_new"     : id_train_through_new.val(),
                    "wx_use_flag"           : id_wx_use_flag.val()
                });
            }
        });
    }

    if (window.location.pathname=="/human_resource/index_seller" || window.location.pathname=="/human_resource/index_seller/" || window.location.pathname=="/human_resource/index_new_seller_hold" || window.location.pathname=="/human_resource/index_new_seller_hold/") {
        $("#id_test_transfor_per").parent().parent().hide();
        $("#id_need_test_lesson_flag").parent().parent().hide();
        $("#id_textbook_type").parent().parent().hide();
        $("#id_test_user").parent().parent().hide();
        $(".div_show").hide();
        $("#phone_num").show();
    }

    if (window.location.pathname=="/human_resource/index_new_jw" || window.location.pathname=="/human_resource/index_new_jw/" || window.location.pathname=="/human_resource/index_jw" || window.location.pathname=="/human_resource/index_jw/") {
        $("#id_free_time").parent().parent().show();
        $(".jw_revisit_info").show();
        $("#id_lesson_hold_flag_adminid").parent().parent().hide();
        $(".test_transfor_per").show();
        $("#id_teacher_money_type").parent().parent().hide();
        $("#id_need_test_lesson_flag").parent().parent().hide();
        $("#id_teacher_ref_type").parent().parent().hide();
        $("#id_have_wx").parent().parent().hide();
        $("#id_test_user").parent().parent().hide();
        $("#id_lesson_hold_flag").parent().parent().hide();
        $("#id_test_transfor_per").parent().parent().hide();
        $("#id_week_liveness").parent().parent().hide();
        $("#id_interview_score").parent().parent().hide();
        $("#id_second_interview_score").parent().parent().hide();
        $("#id_id_teacher_type").parent().parent().hide();
        $("#id_reference_teacherid").parent().parent().hide();
        $("#id_fulltime_teacher_type").parent().parent().hide();
        $(".fulltime_flag_new").hide();     
        $(".interview_score").hide();     
        $(".second_interview_score").hide();     
    }else if (window.location.pathname=="/human_resource/index_new_jw_hold" || window.location.pathname=="/human_resource/index_new_jw_hold/") {
        $(".jw_revisit_info").show();
        $(".lesson_hold_flag").show();
        $("#id_free_time").parent().parent().show();
        $(".test_transfor_per").show();
        $("#id_teacher_money_type").parent().parent().hide();
        $("#id_need_test_lesson_flag").parent().parent().hide();
        $("#id_teacher_ref_type").parent().parent().hide();
        $("#id_have_wx").parent().parent().hide();
        $("#id_test_user").parent().parent().hide();
        $("#id_lesson_hold_flag").parent().parent().hide();
        $("#id_test_transfor_per").parent().parent().hide();
        $("#id_week_liveness").parent().parent().hide();
        $("#id_interview_score").parent().parent().hide();
        $("#id_second_interview_score").parent().parent().hide();
        $("#id_id_teacher_type").parent().parent().hide();
        $("#id_reference_teacherid").parent().parent().hide();
        $("#id_fulltime_teacher_type").parent().parent().hide();
        $(".fulltime_flag_new").hide();     
        $(".interview_score").hide();     
        $(".second_interview_score").hide();     
    }else{
        $("#id_grade_plan").parent().parent().hide();
        $("#id_subject_plan").parent().parent().hide();
        $(".opt-return-back-new").hide();
        $(".opt-return-back-list").hide();
        $(".opt-complaints-teacher").hide();
        $("#id_lesson_hold_flag_adminid").parent().parent().hide();
        $("#id_set_leave_flag").parent().parent().hide();
    }



    // if (window.location.pathname=="/human_resource/index_tea_qua" || window.location.pathname=="/human_resource/index_tea_qua/" || window.location.pathname=="/human_resource/index_fulltime" || window.location.pathname=="/human_resource/index_fulltime/") {
    if ( window.location.pathname=="/human_resource/index_fulltime" || window.location.pathname=="/human_resource/index_fulltime/") {
        $("#id_need_test_lesson_flag").parent().parent().hide();
        $("#id_textbook_type").parent().parent().hide();
        $("#id_test_user").parent().parent().hide();
        $(".opt-tea-note").hide();
        $(".opt-show-lessons").hide();
        $(".opt-set-tmp-passwd").hide();
        $(".opt-old").hide();
        $(".opt-test-user").hide();
        $(".tea_address").hide();
        $(".tea_school").hide();
        $(".tea_textbook").hide();
        $(".tea_is_need_test").hide();
        $(".opt-get-teacher-lesson-hold").hide();
        $(".opt-user-info").hide();
        $(".lesson_hold_flag").show();
        $(".test_transfor_per").show();
    }else if(window.location.pathname=="/human_resource/index_tea_qua" || window.location.pathname=="/human_resource/index_tea_qua/" || window.location.pathname=="/human_resource/index_tea_qua_zj" || window.location.pathname=="/human_resource/index_tea_qua_zj/" ){
        $("#id_need_test_lesson_flag").parent().parent().hide();
        $("#id_textbook_type").parent().parent().hide();
        $("#id_test_user").parent().parent().hide();
        $(".opt-tea-note").hide();
        $(".opt-show-lessons").hide();
        $(".opt-set-tmp-passwd").hide();
        $(".opt-old").hide();
        $(".opt-test-user").hide();
        $(".tea_address").hide();
        $(".tea_school").hide();
        $(".tea_textbook").hide();
        $(".tea_is_need_test").hide();
        $(".opt-get-teacher-lesson-hold").hide();
        $(".opt-user-info").show(); //　erick　修改
        $(".lesson_hold_flag").show();
        $(".test_transfor_per").show();
    }else{
        $(".opt-interview-assess").hide();
        $(".opt-edit").hide();
        $(".opt-set-grade-range").hide();
        $(".opt-teacher-freeze").hide();
        $(".opt-set-teacher-record-new").hide();
        $(".opt-get-teacher-record").hide();
        $(".opt-set-research_note").hide();
        $(".opt-limit-plan-lesson").hide();
    }




    if ( window.location.pathname=="/human_resource/index" || window.location.pathname=="/human_resource/index/") {
         $("#id_free_time").parent().parent().show();
    }




    if ( window.location.pathname=="/human_resource/index_fulltime" || window.location.pathname=="/human_resource/index_fulltime/") {
    }else{
         $("#id_fulltime_teacher_type").parent().parent().hide();
    }

    if ( window.location.pathname=="/human_resource/index_new_jw" || window.location.pathname=="/human_resource/index_new_jw/" ) {
        $(".opt-set-teacher-label").show();
    }else{
        if(acc!="jack"){
            
            $(".opt-set-teacher-label").hide();
        }
    }



    if(tea_right==0 ){
        $(".opt-teacher-freeze").hide();
        $(".opt-limit-plan-lesson").hide();
        $(".opt-set-teacher-record-new").hide();
    }

    
   

    $(".opt-return-back-new").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        //alert(teacherid);
        var id_free_time = $("<textarea />");
        var id_teacher_textbook = $("<input />");
        var id_work_year = $("<input />");
        var id_gender = $("<select />");
        var id_region = $("<input />");
        var id_revisit_note = $("<textarea />");
        var id_class_will_type = $("<select />");
        var id_class_will_sub_type = $("<select />");
        var id_recover_class_time = $("<input />");
        Enum_map.append_option_list( "class_will_type",id_class_will_type,true);
        Enum_map.append_option_list( "gender",id_gender,true,[1,2]);
        var arr = [
            [ "有效空闲时间",  id_free_time],
            [ "教材版本",  id_teacher_textbook],
            [ "教龄",  id_work_year],
            [ "性别",  id_gender],
            [ "地区",  id_region],
            [ "接课意愿",  id_class_will_type],
            [ "接课意愿详情",  id_class_will_sub_type],
            [ "恢复接课时间",  id_recover_class_time],
            [ "回访信息",  id_revisit_note]
        ];
        id_teacher_textbook.val(opt_data.teacher_textbook);
        id_work_year.val(opt_data.work_year);
        id_gender.val(opt_data.gender);
        id_region.val(opt_data.address);
        id_free_time.val(opt_data.free_time);
        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        var reset_ui=function() {
            var val=id_class_will_type.val();
            if (val==0) {
                show_field( id_class_will_sub_type ,false);
                show_field( id_recover_class_time ,false);
            }else if(val==1){
                show_field( id_class_will_sub_type ,true);
                show_field( id_recover_class_time ,false);
                id_class_will_sub_type.find("option").remove();
                Enum_map.append_option_list( "class_will_sub_type",id_class_will_sub_type,true,[1,2]);
            }else if(val==2){
                show_field( id_class_will_sub_type ,true);
                show_field( id_recover_class_time ,false);
                id_class_will_sub_type.find("option").remove();
                Enum_map.append_option_list( "class_will_sub_type",id_class_will_sub_type,true,[3,4,5,6]);
            }else if(val==3){
                show_field( id_class_will_sub_type ,true);
                show_field( id_recover_class_time ,false);
                id_class_will_sub_type.find("option").remove();
                Enum_map.append_option_list( "class_will_sub_type",id_class_will_sub_type,true,[7,8,9]);

            }


        };
        var reset_ui_sub=function() {
            var sub_type= id_class_will_sub_type.val();
            if ( sub_type ==5 || sub_type==2 ) {
                show_field( id_recover_class_time ,true);
            }else{
                show_field( id_recover_class_time ,false);
            }

        };



        id_class_will_type.on("change",function(){
            reset_ui();
        });
        id_class_will_sub_type.on("change",function(){
            reset_ui_sub();
        });

        id_teacher_textbook.on("click",function(){
            var textbook  = opt_data.teacher_textbook;
            console.log(textbook);
            $.do_ajax("/user_deal/get_teacher_textbook",{
                "textbook" : textbook
            },function(response){
                var data_list   = [];
                var select_list = [];
                $.each( response.data,function(){
                    data_list.push([this["num"], this["textbook"]  ]);

                    if (this["has_textbook"]) {
                        select_list.push (this["num"]) ;
                    }

                });

                $(this).admin_select_dlg({
                    header_list     : [ "id","教材版本" ],
                    data_list       : data_list,
                    multi_selection : true,
                    select_list     : select_list,
                    onChange        : function( select_list,dlg) {
                        id_teacher_textbook.val(select_list);
                        dlg.close();
                    }
                });
                
            });
        });



        $.show_key_value_table("录入回访信息", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/human_resource/add_new_teacher_revisit_record", {
                    "teacherid"               : teacherid,
                    "revisit_note"            : id_revisit_note.val(),
                    "class_will_type"         : id_class_will_type.val(),
                    "class_will_sub_type"     : id_class_will_sub_type.val(),
                    "recover_class_time"      : id_recover_class_time.val(),
                    "free_time"               : id_free_time.val(),
                    "teacher_textbook"        : id_teacher_textbook.val(),
                    "work_year"               : id_work_year.val(),
                    "gender"                  : id_gender.val(),
                    "region"                  : id_region.val()
                });
            }
        },function(){
            reset_ui();
            id_recover_class_time.datetimepicker({
                datepicker : true,
                timepicker : true,
                format     : 'Y-m-d H:i',
                step       : 30
            });
        });
    });

    $(".show_phone").on("click",function(){
        var phone = $(this).data("phone");
        $.do_ajax( "/user_deal/set_teacher_phone_click_info",{
        },function(resp){
            var num = resp.data;
            if(num>=5){
                BootstrapDialog.alert("您今天已经点击查看5次,已达上限");
            }else{
                BootstrapDialog.alert({
                    title: "数据",
                    message:phone ,
                    closable: true,
                    callback: function(){
                    }
                });
            }
        });
    });

    $(".opt-return-back-list-new").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var teacherid    = opt_data.teacherid;

        $.ajax({
            type     :"post",
            url      :"/human_resource/get_new_teacher_revisit_info",
            dataType :"json",
            size     : BootstrapDialog.SIZE_WIDE,
            data     : {"teacherid":teacherid},
            success  : function(result){
                console.log(result);
                var html_str="<table class=\"table table-bordered table-striped\"  > ";
                html_str+=" <tr><th> 时间  <th> 负责人 <th>内容 </tr>   ";
                $.each( result.revisit_list ,function(i,item){
                    //console.log(item);
                    //return;
                    var revisit_person  ="";
                    if(item.revisit_person  ) {
                        revisit_person  = item.revisit_person;
                    }
                    html_str=html_str+"<tr><td>"+item.add_time_str +"</td><td>"+ item.acc +"</td><td>"+item.record_info+" </td></tr>";
                } );



                var dlg=BootstrapDialog.show({
                    title: '回访记录',
                    message :  html_str ,
                    closable: true,
                    buttons: [{
                        label: '返回',
                        action: function(dialog) {
                            //dlg.setSize(BootstrapDialog.SIZE_WIDE);
                            dialog.close();
                        }
                    }]
                });

                if (!$.check_in_phone()) {
                    dlg.getModalDialog().css("width", "800px");
                }

            }
        });

    });


    $(".opt-test-user").on("click",function(){
        var data = $(this).get_opt_data();
        set_test_user(data);
    });

    var set_test_user = function(data){
        var teacherid = data.teacherid;
        var id_is_test = $("<select/>");
        var arr        = [
            ["测试老师",id_is_test]
        ];
        Enum_map.append_option_list("boolean",id_is_test,true);

        $.do_ajax("/tea_manage_new/get_teacher_info_by_teacherid",{
            "teacherid" : teacherid
        },function(result){
            if(result.ret!=0){
                BootstrapDialog.alert(result.info);
            }else{
                var data = result.data;
                id_is_test.val(data.is_test_user);
                $.show_key_value_table("测试老师",arr,{
                    label    : "确认",
                    cssClass : "btn-warning",
                    action   : function(dialog) {
                        $.do_ajax("/human_resource/set_teacher_is_test",{
                            "teacherid"    : teacherid,
                            "is_test_user" : id_is_test.val(),
                        },function(result){
                            BootstrapDialog.alert(result.info);
                            dialog.close();
                        });
                    }
                });
            }
        })
    }

    $(".opt-set-research-note").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;

        var id_research_note=$("<textarea/>");
        id_research_note.val(opt_data.research_note);
        var arr=[
            ["教研备注", id_research_note]
        ];
        $.show_key_value_table("请输入备注", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {

                $.do_ajax( '/ss_deal/update_research_note', {
                    "teacherid"     : teacherid,
                    "research_note" : id_research_note.val()
                });
            }
        });

    });

    $(".opt-set-teacher-record").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var id_score = $("<input />");
        var id_record = $("<textarea style=\"width:350px; height:200px\" />");

        var arr=[
            ["总分",id_score],
            ["反馈",id_record]
        ];


        $.show_key_value_table("反馈", arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                if(id_score.val() <0 || id_score.val()==null ){
                    BootstrapDialog.alert("请填写评分!");
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

                $.do_ajax("/human_resource/set_teacher_record_info",{
                    "teacherid"         : teacherid,
                    "type"              : 1,
                    "test_lesson_score" : id_score.val(),
                    "record_info"       : id_record.val()
                });
            }
        },function(){
            id_score.attr("placeholder","满分100分");
            id_record.attr("placeholder","字数不能超过150字");
        });
    });

    $(".opt-change-week-lesson-num-list").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var title     = " 周排课数修改记录";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>时间</td><td>修改前</td><td>修改后</td><td>理由</td><td>操作人</td><td>是否CC要求</td><tr></table></div>");



        $.do_ajax("/user_deal/get_teacher_week_lesson_num_change_list",{
            "teacherid" : teacherid,
            "type"      : 7
        },function(result){
            if(result.ret!=0){
                BootstrapDialog.alert(result.info);
                return ;
            }

            $.each(result.data,function(i,item){
                html_node.find("table").append("<tr><td>"+item['add_time_str']+"</td><td>"+item['limit_week_lesson_num_old']+"</td><td>"+item['limit_week_lesson_num_new']+"</td><td>"+item['record_info']+"</td><td>"+item['acc']+"</td><td>"+item['seller_require_flag_str']+"</td></tr>");

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


        });


    });







    $(".opt-get-teacher-lesson-hold").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var id_lesson_hold_flag=$("<select/>");
        Enum_map.append_option_list("boolean", id_lesson_hold_flag,true,[0,1]);

        id_lesson_hold_flag.val(opt_data.lesson_hold_flag);

        var arr=[
            ["暂停接课", id_lesson_hold_flag]
        ];
        $.show_key_value_table("设置暂停接课", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                $.do_ajax( '/ss_deal/update_teacher_lesson_hold_flag',
                           {
                               "teacherid"          : opt_data.teacherid,
                               "lesson_hold_flag"           : id_lesson_hold_flag.val()
                           });
            }
        });

    });


    $(".content_show").each(function(){
        var content = $(this).data("content");
        var len = content.length;
        if(len >=10){
            var con = content.substr(0,9)+"...";
        }else{
             con = content;
        }
        $(this).html(con);

        $(this).mouseover(function(){

          $(this).html(content);

        });
        $(this).mouseout(function(){
            $(this).html(con);
        });

    });

    $(".content_freeze").each(function(){
        var freeze = $(this).data("freeze");
        var free = $(this).data("free");
        var time = $(this).data("time");
        var reason = $(this).data("reason");
        var adminid = $(this).data("adminid");
        $(this).text(free);
        if(freeze==1){
            $(this).mouseover(function(){

                $(this).html(free+"<br/>操作时间:"+time+"<br/>原因:"+reason+"<br/>操作人:"+adminid);

            });
            $(this).mouseout(function(){
                $(this).text(free);
            });
        }
    });

    $(".content_limit").each(function(){
        var limit_type = $(this).data("type");
        var limit = $(this).data("limit");
        var time = $(this).data("time");
        var reason = $(this).data("reason");
        var adminid = $(this).data("adminid");
        if(limit_type>0){
            $(this).text(limit);
            $(this).mouseover(function(){

                $(this).html(limit+"<br/>操作时间:"+time+"<br/>原因:"+reason+"<br/>操作人:"+adminid);

            });
            $(this).mouseout(function(){
                $(this).text(limit);
            });
        }else{
            $(this).text("未限制");
        }
    });

    $(".opt-change-phone").on("click",function(){
        var opt_data = $(this).get_opt_data();
        change_phone(opt_data);
    });

    var change_phone = function(opt_data){
        var id_new_phone = $("<input/>");
        var arr          = [
            ["新的手机号",id_new_phone],
        ];

        $.show_key_value_table("更换手机", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/human_resource/change_phone",{
                    "userid"    : opt_data.teacherid,
                    "phone"     : opt_data.phone,
                    "new_phone" : id_new_phone.val(),
                    "role"      : 2,
                },function(result){
                    if(result.ret<0){
                        BootstrapDialog.alert(result.info);
                    }else{
                        window.location.reload();
                    }
                });
            }
        });
    }

    var switch_teacher_to_test = function(opt_data){
        BootstrapDialog.show({
	          title   : "设置为999开头的测试老师",
	          message : "是否设置为999开头的测试老师账号?",
	          buttons : [{
		            label  : "返回",
		            action : function(dialog) {
			              dialog.close();
		            }
	          }, {
		            label    : "确认",
		            cssClass : "btn-warning",
		            action   : function(dialog) {
                    $.do_ajax("/human_resource/switch_teacher_to_test",{
                        "teacherid" : opt_data.teacherid,
                        "phone"     : opt_data.phone,
                    },function(result){
                        BootstrapDialog.alert(result.info);
                    });
		            }
	          }]
        });
    }

    var update_tea_realname = function(opt_data){
        var id_nick     = $("<input>");
        var id_realname = $("<input>");
        var arr = [
            ["昵称",id_nick],
            ["姓名",id_realname],
        ];
        id_nick.val(opt_data.nick);
        id_realname.val(opt_data.realname);
        $.show_key_value_table("修改昵称/姓名",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/human_resource/update_tea_realname",{
                    "teacherid" : opt_data.teacherid,
                    "nick"      : id_nick.val(),
                    "realname"  : id_realname.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        });
    }

    $(".opt-change-level").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_level = $("<select/>");

        Enum_map.append_option_list("level",id_level,true);
        id_level.val(opt_data.level);

        var arr = [
            ["新等级",id_level],
        ];

        $.show_key_value_table("更改等级", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                BootstrapDialog.alert("正在更改，请稍后！");
                $.do_ajax("/human_resource/set_teacher_level",{
                    "teacherid" : opt_data.teacherid,
                    "old_level" : opt_data.level,
                    "level"     : id_level.val(),
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

    $(".opt-set-refuse-record").on("click",function(){
      var data = $(this).get_opt_data();
        add_teacher_record(data,2);
    });

    var add_teacher_record=function(data,record_type){
        var id_record_info = $("<textarea/>");

        var arr = [
            ["反馈内容",id_record_info],
        ];

        $.show_key_value_table("添加反馈记录",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                if(id_record_info.val()==""){
                    BootstrapDialog.alert("反馈内容不能为空！");
                    return ;
                }
                $.do_ajax("/human_resource/add_teacher_record",{
                    "record_info" : id_record_info.val(),
                    "type"        : record_type,
                    "teacherid"   : data.teacherid,
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        });
    }

    $(".opt-change-teacher_ref_type").on("click",function(){
        var data = $(this).get_opt_data();
        update_tea_ref_type(data);
    });

    var update_tea_ref_type = function(data){
        var id_teacher_ref_type = $("<select/>");
        var id_teacher_type     = $("<select/>");

        var arr = [
            ["老师推荐老师推荐类型",id_teacher_ref_type],
            ["老师类型",id_teacher_type],
        ];

        Enum_map.append_option_list("teacher_ref_type",id_teacher_ref_type,false);
        Enum_map.append_option_list("teacher_type",id_teacher_type,false);

        $.do_ajax("/tea_manage_new/get_teacher_info_by_teacherid",{
            "teacherid" : data.teacherid
        },function(result){
            if(result.ret==0){
                id_teacher_ref_type.val(result.data.teacher_ref_type);
                id_teacher_type.val(result.data.teacher_type);
            }else{
                BootstrapDialog.alert(result.info);
                return false;
            }

            $.show_key_value_table("更改老师信息",arr,{
                label    : "确认",
                cssClass : "btn-warning",
                action   : function(dialog) {
                    $.do_ajax("/human_resource/set_teacher_ref_type",{
                        "teacherid"        : data.teacherid,
                        "teacher_ref_type" : id_teacher_ref_type.val(),
                        "teacher_type"     : id_teacher_type.val(),
                    },function(result){
                        BootstrapDialog.alert(result.info);
                    })
                }
            });
        })

    }

    var init_noit_btn=function( id_name, title) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value =btn.data("value");
        btn.text(value);
        if (value >0 ) {
            btn.addClass("btn-warning");
        }
    };

    init_noit_btn("id_limit_week_lesson_num_person",    "每周限课次数超8次人数" );

    $(".opt-change-grade-range").on("click",function(){
      var data           = $(this).get_opt_data();
        var id_grade_start = $("<select/>");
        var id_grade_end   = $("<select/>");

        Enum_map.append_option_list("grade_range",id_grade_start,true);
        Enum_map.append_option_list("grade_range",id_grade_end,true);
        id_grade_start.val(data.grade_start);
        id_grade_end.val(data.grade_end);

        var arr = [
            ["年级开始",id_grade_start],
            ["年级结束",id_grade_end],
        ];

        $.show_key_value_table("设置年级范围",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/human_resource/set_teacher_grade_range",{
                    "teacherid"   : data.teacherid,
                    "grade_start" : id_grade_start.val(),
                    "grade_end"   : id_grade_end.val(),
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

     $(".opt-complaints-teacher").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id_complaints_info = $("<textarea />");
        var id_complaints_info_url = $("<div><input class=\"complaints_info_url\" id=\"complaints_info_url\" type=\"text\"readonly ><div><span ><a class=\"upload_gift_pic\" id=\"id_upload_complaints_info\" href=\"javascript:;\">上传</a></span><span><a style=\"margin-left:20px\" href=\"javascript:;\" id=\"id_del_complaints_info\">删除</a></span></div></div>");
        var arr = [
            ["投诉内容",  id_complaints_info],
            ["相关图片",  id_complaints_info_url ]
        ];
        id_complaints_info_url.find("#id_del_complaints_info").on("click",function(){
            id_complaints_info_url.find("#complaints_info_url").val("");
        });

        $.show_key_value_table("投诉", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var url = id_complaints_info_url.find("#complaints_info_url").val();

                $.do_ajax("/user_deal/add_complaints_teacher_info", {
                    "teacherid":opt_data.teacherid,
                    "subject":opt_data.subject,
                    "complaints_info": id_complaints_info.val(),
                    "complaints_info_url": id_complaints_info_url.find("#complaints_info_url").val()
                });
            }
        },function(){
            $.custom_upload_file('id_upload_complaints_info',true,function (up, info, file) {
                var res = $.parseJSON(info);

                $("#complaints_info_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

        });

    });

    $(".opt-set-quit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var id_is_quit =$("<select/>");
        var id_quit_info =$("<textarea/>");
        Enum_map.append_option_list( "boolean", id_is_quit,true);
        id_is_quit.val(opt_data.is_quit);
        var arr=[
            ["teacherid",teacherid] ,
            ["realname",opt_data.realname] ,
            [" 是否离职",id_is_quit],
            ["确认信息",id_quit_info]
        ];

        $.show_key_value_table("更改老师状态", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/tea_manage_new/set_teacher_quit_info', {
                    'teacherid': teacherid,
                    'is_quit': id_is_quit.val(),
                    'quit_info':id_quit_info.val()
                });
            }
        });

    });

    $(".opt-set-leave").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var id_leave_start_time = $("<input />");
        var id_leave_end_time = $("<input />");
        var id_leave_reason = $("<textarea />");
        id_leave_start_time.datetimepicker({
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i',
            step:30,
            onChangeDateTime :function(){

            }

        });
        id_leave_end_time.datetimepicker({
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i',
            step:30,
            onChangeDateTime :function(){

            }
        });
        var arr = [
            [ "请假开始时间",  id_leave_start_time] ,
            [ "请假结束时间",  id_leave_end_time] ,
            [ "请假理由",  id_leave_reason] ,
        ];

        $.show_key_value_table("老师请假信息录入", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var now        = (new Date()).getTime()/1000;
                var leave_start_time = $.strtotime(id_leave_start_time.val());
                var leave_end_time = $.strtotime(id_leave_end_time.val());
                if ( now > leave_start_time ) {
                    alert("请假时间时间比现在还小.");
                    return ;
                }
                if (  leave_start_time >= leave_end_time ) {
                    alert("结束时间比开始时间还小.");
                    return ;
                }


                $.do_ajax('/tea_manage_new/set_teacher_leave_info', {
                    'teacherid': teacherid,
                    'leave_start_time': id_leave_start_time.val(),
                    'leave_end_time': id_leave_end_time.val(),
                    'leave_reason': id_leave_reason.val(),
                });
            }
        });

    });

    $(".opt-teacher-leave-list").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var title = "请假记录";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>开始时间</td><td>结束时间</td><td>请假事由</td><td>操作人</td><td>操作时间</td><tr></table></div>");

        $.do_ajax("/tea_manage_new/get_teacher_leave_list",{
            "teacherid" : teacherid
        },function(result){
            if(result.ret!=0){
                BootstrapDialog.alert(result.info);
                return ;
            }

            $.each(result.data,function(i,item){
                html_node.find("table").append("<tr><td>"+item['leave_start_time_str']+"</td><td>"+item['leave_end_time_str']+"</td><td>"+item['leave_reason']+"</td><td>"+item['account']+"</td><td>"+item['leave_set_time_str']+"</td></tr>");


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
        });
    });

    $(".opt-require-teacher-quit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var flow_type=4001;
        var  from_key_int= opt_data.teacherid;
        $.flow_dlg_show("申请 老师离职",function(){
            var $input=$("<input style=\"width:180px\"  placeholder=\"理由\"/>");
            $.show_input( "要申请  老师离职 :"+
                          opt_data.realname ,
                          "",function(val){
                              $.do_ajax("/user_deal/flow_add_flow",{
                                  "from_key_int":  from_key_int ,
                                  'reason'  :val,
                                  'flow_type' : flow_type
                              });
                          }, $input  );

        }, flow_type , from_key_int );
    });

    $(".opt-change_tea_to_new").on("click",function(){
        var data = $(this).get_opt_data();
        opt_change_tea_to_new(data);
    });

    var opt_change_tea_to_new = function(data){
        var id_new_phone    = $("<input/>");
        var id_lesson_start = $("<input/>");

        id_lesson_start.datetimepicker({
            lang       : 'ch',
            timepicker : false,
            format     : 'Y-m-d ',
        });

        var arr = [
            ["老师新账号",id_new_phone],
            ["需要转移的课程开始时间","如果不填则默认当天之后的未上课程"],
            ["开始时间",id_lesson_start],
        ];

        $.show_key_value_table("转移老师信息至新账号",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                var lesson_start  = id_lesson_start.val();
                data.lesson_start = lesson_start;
                var new_phone = id_new_phone.val();
                check_new_phone(new_phone,data);
            }
        });
    }

    var check_new_phone = function(new_phone,old_info){
        $.do_ajax("/human_resource/change_teacher_to_new",{
            "new_phone" : new_phone,
            "phone"     : old_info.phone,
        },function(result){
            if(result.ret==0){
                var new_teacherid = result.new_teacherid;
                change_tea_to_new(old_info,new_teacherid);
            }else{
                BootstrapDialog.alert(result.info);
            }
        });
    }

    var change_tea_to_new = function(old_info,new_teacherid){
        $.do_ajax("/human_resource/transfer_teacher_info",{
            "old_teacherid" : old_info.teacherid,
            "new_teacherid" : new_teacherid,
            "lesson_start"  : old_info.lesson_start,
        },function(result){
            if(result.ret==0){
                BootstrapDialog.alert("转移完成!");
                sleep(1000);
                window.location.reload();
            }else{
                BootstrapDialog.alert(result.info);
            }
        })
    }

    $(".opt-account-number").on("click",function(){
	      var data = $(this).get_opt_data();

        var id_tea_realname            = $("<button class='btn btn-primary'>修改老师姓名</button>");
        var id_send_offer_info         = $("<button class='btn btn-primary'>发送入职信息</button>");
        var id_switch_teacher_to_test  = $("<button class='btn btn-danger'>一键转为测试老师</button>");
        var id_change_phone            = $("<button class='btn btn-danger'>更换手机</button>");
        var id_change_tea_to_new       = $("<button class='btn btn-danger'>账号转移</button>");
        var id_subject_info            = $("<button class='btn btn-danger'>年级/科目修改</button>");

        var id_update_tea_level        = $("<button class='btn btn-danger'>老师工资类型</button>");
        var id_update_tea_bank         = $("<button class='btn btn-danger'>银行卡</button>");
        var id_update_tea_pass_info    = $("<button class='btn btn-danger'>通过信息</button>");
        var id_set_test_user           = $("<button class='btn btn-danger'>设为测试</button>");
        var id_update_check_subject    = $("<button class='btn btn-primary'>审核信息</button>");
        var id_update_tea_ref_type     = $("<button class='btn btn-primary'>渠道信息</button>");
        var id_part_to_full            = $("<button class='btn btn-danger'>一键转全职</button>");

        id_subject_info.on("click",function(){update_subject_info(data);});
        id_change_tea_to_new.on("click",function(){opt_change_tea_to_new(data);});
        id_change_phone.on("click",function(){change_phone(data);});
        id_update_tea_level.on("click",function(){set_teacher_level(data);});
        id_update_tea_bank.on("click",function(){update_tea_bank_info(data);});
        id_update_tea_pass_info.on("click",function(){update_tea_pass_info(data);});
        id_set_test_user.on("click",function(){set_test_user(data);});
        id_update_check_subject.on("click",function(){update_tea_check_info(data);});
        id_update_tea_ref_type.on("click",function(){update_tea_ref_type(data);});
        id_switch_teacher_to_test.on("click",function(){switch_teacher_to_test(data);});
        id_send_offer_info.on("click",function(){send_offer_info(data);});
        id_part_to_full.on('click',function(){part_to_full(data);});
        id_tea_realname.on('click',function(){update_tea_realname(data);});

        var arr = [
            ["",id_send_offer_info],
            ["",id_switch_teacher_to_test],
            ["",id_change_phone],
            ["",id_change_tea_to_new],
            ["",id_subject_info],
            ["",id_tea_realname],
        ];

        if(account_role == 12){
            var extra_arr = [
                ["",id_update_tea_level],
                ["",id_update_tea_bank],
                ["",id_update_tea_pass_info],
                ["",id_update_check_subject],
                ["",id_set_test_user],
                ["",id_update_tea_ref_type],
            ];
            arr = arr.concat(extra_arr);
        }else if(acc=="陆小梅" || acc=="朱丽莎"){
            arr = [
                ["",id_send_offer_info],
                ["",id_change_phone],
                ["",id_subject_info],
            ];

        }
        if(acc=="jim" || acc=="宫卫彬") {
            var extra_arr = [
                ["",id_part_to_full],
            ];
            arr = arr.concat(extra_arr);
        }

        $.show_key_value_table("账号信息修改",arr);
    });

    // 一键转全职
    var part_to_full = function(data){
        var teacherid = data.teacherid;
        $.do_ajax('/teacher_trans/one_part_to_full', {
            "teacherid":teacherid
        });
    }

    //发送入职邮件
    var send_offer_info = function(data){
        var name = data.nick;
        BootstrapDialog.show({
	          title   : "发送入职邮件和微信推送",
	          message : "确定给"+name+"发送入职邮件和微信推送么?",
	          buttons : [{
		            label  : "返回",
		            action : function(dialog) {
			              dialog.close();
		            }
	          }, {
		            label    : "确认",
		            cssClass : "btn-warning",
		            action   : function(dialog) {
                    $.do_ajax("/common/send_offer_info_by_teacherid",{
                        "teacherid" : data.teacherid
                    },function(result){
                        BootstrapDialog.alert(result.info);
                    })
		            }
	          }]
        });
    }

    //更新老师的科目和年级信息
    var update_subject_info = function(data){
        var id_subject            = $("<select>");
        var id_grade_start        = $("<select>");
        var id_grade_end          = $("<select>");
        var id_second_subject     = $("<select>");
        var id_second_grade_start = $("<select>");
        var id_second_grade_end   = $("<select>");

        Enum_map.append_option_list("subject",id_subject,true);
        Enum_map.append_option_list("subject",id_second_subject,true);
        Enum_map.append_option_list("grade_range",id_grade_start,true);
        Enum_map.append_option_list("grade_range",id_grade_end,true);
        Enum_map.append_option_list("grade_range",id_second_grade_start,true);
        Enum_map.append_option_list("grade_range",id_second_grade_end,true);

        var arr = [
            ["第一科目",id_subject],
            ["开始年级",id_grade_start],
            ["结束年级",id_grade_end],
            ["第二科目",id_second_subject],
            ["开始年级",id_second_grade_start],
            ["结束年级",id_second_grade_end],
        ];
        id_subject.val(data.subject);
        id_second_subject.val(data.second_subject);
        id_grade_start.val(data.grade_start);
        id_grade_end.val(data.grade_end);
        id_second_grade_start.val(data.second_grade_start);
        id_second_grade_end.val(data.second_grade_end);

        $.show_key_value_table("更新老师的科目和年级信息",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_info_admin/update_teacher_subject_info",{
                    "teacherid"          : data.teacherid,
                    "subject"            : id_subject.val(),
                    "second_subject"     : id_second_subject.val(),
                    "grade_start"        : id_grade_start.val(),
                    "grade_end"          : id_grade_end.val(),
                    "second_grade_start" : id_second_grade_start.val(),
                    "second_grade_end"   : id_second_grade_end.val(),
                },function(result){
                    if(result.ret==0){
                        BootstrapDialog.alert("更新成功！");
                        dialog.close();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        });
    }

    $(".opt-regular-lesson-detele-list").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var title = "常规课表删除记录";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>操作时间</td><td>内容</td><td>学生</td><td>操作人</td></tr></table></div>");

        $.do_ajax("/user_deal/get_teacher_regular_lesson_del_list",{
            "teacherid" : teacherid,
            "type"      : 11
        },function(result){
            if(result.ret!=0){
                BootstrapDialog.alert(result.info);
                return ;
            }

            $.each(result.data,function(i,item){
                html_node.find("table").append("<tr><td>"+item['add_time_str']+"</td><td>"+item['record_info']+"</td><td>"+item['current_acc']+"</td><td>"+item['acc']+"</td></tr>");

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
        });
    });

    $(".opt-teacher-cancel-lesson-list").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var title = "老师取消课程记录";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>记录时间</td><td>记录理由</td><td>操作人</td><tr></table></div>");

        $.do_ajax("/ajax_deal2/get_teacher_cancel_lesson_list",{
            "teacherid" : teacherid
        },function(result){
            if(result.ret!=0){
                BootstrapDialog.alert(result.info);
                return ;
            }

            $.each(result.data,function(i,item){
                html_node.find("table").append(
                    "<tr><td>"+item['add_time']+"</td><td>"+item['record_info']+"</td><td>"+item['acc']+"</td></tr>"
                );
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
        });
    });

    $(".opt-set_bank_info").on("click",function(){
	      var data = $(this).get_opt_data();
        update_tea_bank_info(data);
    });

    var update_tea_bank_info = function(data){
        var id_idcard        = $("<input/>");
        var id_bankcard      = $("<input/>");
        var id_bank_address  = $("<input/>");
        var id_bank_account  = $("<input/>");
        var id_bank_phone    = $("<input/>");
        var id_bank_type     = $("<input/>");
        var id_bank_province = $("<input/>");
        var id_bank_city     = $("<input/>");

        id_idcard.val(data.idcard);
        id_bankcard.val(data.bankcard);
        id_bank_address.val(data.bank_address);
        id_bank_account.val(data.bank_account);
        id_bank_phone.val(data.bank_phone);
        id_bank_type.val(data.bank_type);
        id_bank_province.val(data.bank_province);
        id_bank_city.val(data.bank_city);

        var arr = [
            ["身份证",id_idcard],
            ["银行卡",id_bankcard],
            ["支行地址",id_bank_address],
            ["开户人",id_bank_account],
            ["开户手机号",id_bank_phone],
            ["银行类型",id_bank_type],
            ["开户省",id_bank_province],
            ["开户市",id_bank_city],
        ];

        $.show_key_value_table("更改老师银行卡信息",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_money/update_teacher_bank_info",{
                    "type"          : "admin",
                    "teacherid"     : data.teacherid,
                    "idcard"        : id_idcard.val(),
                    "bankcard"      : id_bankcard.val(),
                    "bank_address"  : id_bank_address.val(),
                    "bank_account"  : id_bank_account.val(),
                    "bank_phone"    : id_bank_phone.val(),
                    "bank_type"     : id_bank_type.val(),
                    "bank_province" : id_bank_province.val(),
                    "bank_city"     : id_bank_city.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        });
    }

    $(".opt-set_check_info").on("click",function(){
	      var data = $(this).get_opt_data();
        update_tea_check_info(data);
    });

    var update_tea_check_info = function(data){
        var id_subject = $("<select />");
        var id_grade   = $("<div>");

        Enum_map.append_option_list("subject",id_subject,true);
        Enum_map.append_checkbox_list("grade",id_grade,"check_grade",["100","200","300"]);

        var arr = [
            ["审核科目",id_subject],
            ["审核年级",id_grade],
        ];
        $.show_key_value_table("审核信息",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                var grade_str = "";
                $("input[name='check_grade']:checked").each(function(){
                    if(grade_str== ""){
                        grade_str = $(this).val();
                    }else{
                        grade_str += ","+$(this).val();
                    }
                });

                $.do_ajax("/tea_manage_new/set_teacher_check_info",{
                    "teacherid" : data.teacherid,
                    "subject"   : id_subject.val(),
                    "grade"     : grade_str,
                },function(result){
                    BootstrapDialog.alert(result.info);
                })
            }
        });
    }

    $("#id_plan_level").parent().parent().show();
    $(".opt-identity").on("click",function(){
	      var data = $(this).get_opt_data();

        var id_identity = $("<select/>");
        var arr = [
            ["老师身份",id_identity]
        ];
        Enum_map.append_option_list("identity",id_identity,true);
        id_identity.val(data.identity);

        $.show_key_value_table("设置身份",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/user_manage_new/update_teacher_identity",{
                    "teacherid" : data.teacherid,
                    "identity"  : id_identity.val(),
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

    $(".opt-jianli").on("click",function(){
	      var data = $(this).get_opt_data();
        var jianli = data.jianli;
        if(jianli==""){
            BootstrapDialog.alert("此老师没有简历");
        }else{
            $.wopen(jianli);
        }
    });

    $(".opt-full-to-part").on("click", function() {
        var data = $(this).get_opt_data();
        var require_reason = $("<textarea></textarea>");
        var arr = [
            ['申请原因', require_reason]
        ];

        $.show_key_value_table("全转兼申请",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(diolog) {
                $.do_ajax('/teacher_trans/full_to_part', {
                    'teacherid'          : data.teacherid,
                    'level'              : data.level,
                    'teacher_money_type' : data.teacher_money_type,
                    'require_reason'     : require_reason.val()
                });
            }
        });
    });

    $(".opt-set-teacher-label").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var tag_info = opt_data.tag_info;
        console.log(tag_info);

        $.do_ajax('/ajax_deal2/get_teacher_tag_info',{
        },function(resp) {
            var list = resp.data;

            var teacher_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>风格性格:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"style_character\"></div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>专业能力:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"professional_ability\"> </div><div>");
            var class_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>课堂气氛:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"classroom_atmosphere\"></div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>课件要求:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"courseware_requirements\"> </div><div>");
            var teaching_related_labels=$("<div><div class=\"col-xs-6 col-md-3\"><font style=\"color:red\">*</font>素质培养:</div><div class=\"col-xs-6 col-md-9\" style=\"margin-top:-8px;\" id=\"diathesis_cultivation\"></div>");


            $.each(list,function(i,item){
                console.log(i);
                var ti="";
                if(i=="风格性格"){
                    var ti = "style_character";
                }else if(i=="专业能力"){
                    var ti = "professional_ability";
                }else if(i=="课堂气氛"){
                    var ti = "classroom_atmosphere";
                }else if(i=="课件要求"){
                    var ti = "courseware_requirements";
                }else if(i=="素质培养"){
                    var ti = "diathesis_cultivation";
                }

                var str="";
                $.each(item,function(ii,item_p){
                    console.log(item_p);
                    var check_flag=0;
                    var check_again=0;
                    $.each(tag_info,function(iii,item_po){
                        console.log(item_po);
                        if(iii==ti){
                            check_flag=1;
                        }
                        var arr_item = JSON.parse(item_po);
                        console.log(arr_item);
                        $.each(arr_item,function(iy,item_poo){
                            console.log(item_poo);
                            if(item_poo==item_p){
                                check_again=1;
                            }
                            
                            

                        });

                        

                    });
                    
                    console.log(check_flag);
                    console.log(check_again);
                    if(check_again==1 && check_flag==1){
                        str += "<label style=\"margin-left:6px\"><input name=\""+i+"\" type=\"checkbox\" value=\""+item_p+"\" style=\"margin-top:-3px;\" checked /> "+item_p+"</label>";
                    }else{
                        str += "<label style=\"margin-left:6px\"><input name=\""+i+"\" type=\"checkbox\" value=\""+item_p+"\" style=\"margin-top:-3px;\" /> "+item_p+"</label>";
                    }
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

            // console.log(teaching_related_labels);
            
            var arr = [
                ["教师相关标签",teacher_related_labels],
                ["课堂相关标签",class_related_labels],
                ["教学相关标签",teaching_related_labels],
            ];

            $.show_key_value_table("标签", arr,{
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


                    $.do_ajax("/ajax_deal2/set_teacher_tag_info",{
                        "teacherid"                        : teacherid,                       
                        "style_character"                  : JSON.stringify(style_character),
                        "professional_ability"             : JSON.stringify(professional_ability),
                        "classroom_atmosphere"             : JSON.stringify(classroom_atmosphere),
                        "courseware_requirements"          : JSON.stringify(courseware_requirements),
                        "diathesis_cultivation"            : JSON.stringify(diathesis_cultivation)
                    });
                }
            });

        });
    });


    download_hide();
    if(g_account_role==12){
        $(".opt-edit").show();
    }

});

