/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-index.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page ( {
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
			have_wx:	$('#id_have_wx').val()
        });
    }

    $('#id_teacherid').val(g_args.teacherid);
    $('#id_reference_teacherid').val(g_args.reference_teacherid);
    Enum_map.append_option_list("teacher_money_type", $("#id_teacher_money_type") );
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

        Enum_map.append_option_list("teacher_money_type", id_teacher_money_type );
        Enum_map.append_option_list("level", id_level );
        Enum_map.append_option_list("teacher_ref_type", id_teacher_ref_type);
        Enum_map.append_option_list("identity", id_identity);

        Enum_map.append_option_list("gender", id_gender, true );
        Enum_map.append_option_list("subject", id_subject, true );
        Enum_map.append_option_list("grade_part_ex", id_grade_part_ex, true );
        Enum_map.append_option_list("teacher_type", id_teacher_type, true );

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
            ["面试评价", id_interview_assess],
        ];

        $.show_key_value_table("新增老师", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var birth = ""+id_birth.val();
                birth     = birth.substr(0,4)+birth.substr(5,2)+birth.substr(8,2);

                $.do_ajax('/tea_manage/add_teacher',{
                    "tea_nick" : id_tea_nick.val(),
                    "gender"   : id_gender.val(),
                    "level"    : id_level.val(),
                    "teacher_money_type" : id_teacher_money_type.val(),
                    "teacher_ref_type" : id_teacher_ref_type.val(),
                    "identity"  : id_identity.val(),
                    "birth"     : birth,
                    "work_year" : id_work_year.val(),
                    "phone"     : id_phone.val(),
                    "phone_spare": id_phone_spare.val(),
                    "email"     : id_email.val(),
                    "address"   : id_address.val(),
                    "school"    : id_school.val(),
                    "subject"   : id_subject.val(),
                    "interview_access" : id_interview_assess.val(),
                    "grade_part_ex"    : id_grade_part_ex.val(),
                    "teacher_type"     : id_teacher_type.val(),
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
        var id_work_year              = $("<input/>");
        var id_email                  = $("<input/>");
        var id_realname               = $("<input/>");
        var id_advantage              = $("<input/>");
        var id_base_intro             = $("<textarea/>");
        var id_need_test_lesson_flag  = $("<select/>");
        var id_wx_openid              = $("<input/>");
        var id_address                = $("<input/>");
        var id_school                 = $("<input/>");
        var id_limit_day_lesson_num   = $("<input/>");
        var id_limit_week_lesson_num  = $("<input/>");
        var id_limit_month_lesson_num = $("<input/>");
        var id_teacher_textbook       = $("<input/>");
        var id_subject                = $("<select/>");
        var id_second_subject         = $("<select/>");
        var id_third_subject          = $("<select/>");
        var id_grade_part_ex          = $("<select/>");
        var id_second_grade_part_ex   = $("<select/>");
        var id_third_grade_part_ex    = $("<select/>");
        var id_textbook_type          = $("<select />");

        Enum_map.append_option_list("textbook_type",id_textbook_type,true);
        Enum_map.append_option_list("gender", id_gender, true );
        Enum_map.append_option_list("level", id_level, true );
        Enum_map.append_option_list("teacher_money_type", id_teacher_money_type, true );
        Enum_map.append_option_list("boolean", id_need_test_lesson_flag,true );
        Enum_map.append_option_list("subject", id_subject, true );
        Enum_map.append_option_list("subject", id_second_subject, true );
        Enum_map.append_option_list("subject", id_third_subject, true );
        Enum_map.append_option_list("grade_part_ex", id_grade_part_ex, true );
        Enum_map.append_option_list("grade_part_ex", id_second_grade_part_ex, true );
        Enum_map.append_option_list("grade_part_ex", id_third_grade_part_ex, true );

      id_birth.datetimepicker({
        lang       : 'ch',
        timepicker : false,
        format     : 'Y-m-d',
            "onChangeDateTime" : function() {
            }
      });

        id_tea_nick.val(opt_data.nick);
        id_gender.val(opt_data.gender);
        var birth=""+opt_data.birth;
        birth= birth.substr(0,4)+"-"+ birth.substr(4,2) + "-"+ birth.substr(6,2) ;
        id_birth.val( birth );
        id_work_year.val(opt_data.work_year );
        id_realname.val(opt_data.realname );
        id_phone_spare.val(opt_data.phone_spare);
        id_email.val(opt_data.email);
        id_teacher_money_type.val(opt_data.teacher_money_type);
        id_level.val(opt_data.level);
        id_advantage.val(opt_data.advantage );
        id_base_intro.val(opt_data.base_intro );
        id_need_test_lesson_flag.val(opt_data.need_test_lesson_flag );
        id_wx_openid.val(opt_data.wx_openid);
        id_address.val(opt_data.address);
        id_school.val(opt_data.school);
        id_subject.val(opt_data.subject);
        id_teacher_textbook.val(opt_data.teacher_textbook);
        id_second_subject.val(opt_data.second_subject);
        id_third_subject.val(opt_data.third_subject);
        id_grade_part_ex.val(opt_data.grade_part_ex);
        id_second_grade_part_ex.val(opt_data.second_grade);
        id_third_grade_part_ex.val(opt_data.third_grade);
        id_limit_day_lesson_num.val(opt_data.limit_day_lesson_num);
        id_limit_week_lesson_num.val(opt_data.limit_week_lesson_num);
        id_limit_month_lesson_num.val(opt_data.limit_month_lesson_num);
        id_textbook_type.val(opt_data.textbook_type);

        var arr=[
            ["昵称", id_tea_nick],
            ["姓名", id_realname],
            ["备用手机", id_phone_spare],
            ["性别", id_gender],
            ["出生年月", id_birth],
            ["工作年限", id_work_year],
            ["所在地", id_address],
            ["学校", id_school],
            ["第一科目", id_subject],
            ["第一科目对应年级段", id_grade_part_ex],
            ["第二科目", id_second_subject],
            ["第二科目对应年级段", id_second_grade_part_ex],
            ["第三科目", id_third_subject],
            ["第三科目对应年级段", id_third_grade_part_ex],
            ["擅长教材",id_teacher_textbook],
            ["教材版本",id_textbook_type],
            ["电子邮件", id_email],
            ["教学特长", id_advantage],
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
                    "birth"                 : birth,
                    "work_year"             : id_work_year.val(),
                    "email"                 : id_email.val(),
                    "advantage"             : id_advantage.val(),
                    "base_intro"            : id_base_intro.val(),
                    "need_test_lesson_flag" : id_need_test_lesson_flag.val(),
                    "address"               : id_address.val(),
                    "school"                : id_school.val(),
                    "subject"               : id_subject.val(),
                    "second_subject"        : id_second_subject.val(),
                    "third_subject"         : id_third_subject.val(),
                    "grade_part_ex"         : id_grade_part_ex.val(),
                    "second_grade"          : id_second_grade_part_ex.val(),
                    "third_grade"           : id_third_grade_part_ex.val(),
                    "teacher_textbook"      : id_teacher_textbook.val(),
                    "textbook_type"         : id_textbook_type.val(),
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
            ["教研老师周六排课数", id_saturday_lesson_num],
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

                $.do_ajax( '/tea_manage_new/update_tea_note',
                    {
                        "teacherid"          : opt_data.teacherid,
                        "tea_note"           : id_tea_note.val()
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


    $(".opt-old").on("click",function(){
        var opt_data=$(this).get_opt_data();
        opt_data.teacherid
        $.wopen("/human_resource/index_old?teacherid="+opt_data.teacherid ) ;

    });


    $('.opt-meeting').on('click', function(){
    var opt_data= $(this).get_opt_data();
        get_create_meeting(opt_data.teacherid);
    });

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

        var id_teacher_money_type = $("<select/>");
        var id_level              = $("<select/>");
        var id_start_time         = $("<input/>");

        Enum_map.append_option_list("level", id_level, true );
        Enum_map.append_option_list("teacher_money_type", id_teacher_money_type, true );

        id_teacher_money_type.val(opt_data.teacher_money_type);
        id_level.val(opt_data.level);
        id_start_time.datetimepicker({
            datepicker:true,
            timepicker:false,
            format:'Y-m-d'
        });
        id_start_time.val(opt_data.lesson_confirm_start_time );

        var arr = [
            ["工资类别", id_teacher_money_type],
            ["等级", id_level],
            ["重置课程开始时间", id_start_time],
        ];
        $.show_key_value_table("修改等级", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/tea_manage_new/update_teacher_level', {
                    "teacherid"          : opt_data.teacherid,
                    "start_time"         : id_start_time.val(),
                    "level"              : id_level.val(),
                    "teacher_money_type" : id_teacher_money_type.val()
                });
            }
        });
    });

    $(".opt-trial-pass").on("click",function(){
        var opt_data                 = $(this).get_opt_data();
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
    });

    if (window.location.pathname=="/human_resource/index_seller" || window.location.pathname=="/human_resource/index_seller/" || window.location.pathname=="/human_resource/index_new_seller_hold" || window.location.pathname=="/human_resource/index_new_seller_hold/") {
        $("#id_test_transfor_per").parent().parent().hide();
        $("#id_add_teacher").parent().hide();
        $("#id_need_test_lesson_flag").parent().parent().hide();
        $("#id_textbook_type").parent().parent().hide();
        $("#id_test_user").parent().parent().hide();
        $(".div_show").hide();
        $("#phone_num").show();
    }

    if (window.location.pathname=="/human_resource/index_new" || window.location.pathname=="/human_resource/index_new/") {
        $("#id_add_teacher").parent().hide();
    }
    if (window.location.pathname=="/human_resource/index_new_jw" || window.location.pathname=="/human_resource/index_new_jw/" || window.location.pathname=="/human_resource/index_jw" || window.location.pathname=="/human_resource/index_jw/") {
        $("#id_free_time").parent().parent().show();
        $(".jw_revisit_info").show();
        $("#id_lesson_hold_flag_adminid").parent().parent().hide();
        $(".test_transfor_per").show();
    }else if (window.location.pathname=="/human_resource/index_new_jw_hold" || window.location.pathname=="/human_resource/index_new_jw_hold/") {
        $(".jw_revisit_info").show();
        $(".lesson_hold_flag").show();
        $("#id_free_time").parent().parent().show();
        $(".test_transfor_per").show();
    }else{
        $(".opt-return-back-new").hide();
        $(".opt-return-back-list").hide();
        $(".opt-complaints-teacher").hide();
        $("#id_lesson_hold_flag_adminid").parent().parent().hide();
        $("#id_set_leave_flag").parent().parent().hide();
    }



    if (window.location.pathname=="/human_resource/index_tea_qua" || window.location.pathname=="/human_resource/index_tea_qua/" || window.location.pathname=="/human_resource/index_fulltime" || window.location.pathname=="/human_resource/index_fulltime/") {
        $("#id_add_teacher").parent().hide();
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
    }else{
        $(".opt-meeting").hide();
        $(".opt-interview-assess").hide();
        $(".opt-edit").hide();
        $(".opt-set-grade-range").hide();
        $(".opt-teacher-freeze").hide();
        $(".opt-set-teacher-record-new").hide();
        $(".opt-get-teacher-record").hide();
        $(".opt-set-research_note").hide();
        $(".opt-limit-plan-lesson").hide();
    }

    if(tea_right==0 ){
        $(".opt-teacher-freeze").hide();
        $(".opt-limit-plan-lesson").hide();
        $(".opt-set-teacher-record-new").hide();
    }

    if(acc=="adrian" || acc=="seven"){
        $(".opt-edit").show();
    }

    $(".opt-return-back-new").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        //alert(teacherid);
        var id_revisit_note = $("<textarea />");
        var id_class_will_type = $("<select />");
        var id_class_will_sub_type = $("<select />");
        var id_recover_class_time = $("<input />");
        Enum_map.append_option_list( "class_will_type",id_class_will_type,true);
        var arr = [
            [ "接课意愿",  id_class_will_type],
            [ "接课意愿详情",  id_class_will_sub_type],
            [ "恢复接课时间",  id_recover_class_time],
            [ "回访信息",  id_revisit_note]
        ];
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


        $.show_key_value_table("录入回访信息", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/human_resource/add_new_teacher_revisit_record", {
                    "teacherid"               : teacherid,
                    "revisit_note"            : id_revisit_note.val(),
                    "class_will_type"         : id_class_will_type.val(),
                    "class_will_sub_type"     : id_class_will_sub_type.val(),
                    "recover_class_time"      : id_recover_class_time.val()
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
                //BootstrapDialog.alert(phone);
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
        // alert(teacherid);
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
        var teacherid  = $(this).get_opt_data("teacherid");
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
    });

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
        var opt_data     = $(this).get_opt_data();
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
    });

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
      var data                = $(this).get_opt_data();
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
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    })
                }
            });
        })
    });

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
      var data            = $(this).get_opt_data();
        var id_new_phone    = $("<input/>");
        var id_lesson_start = $("<input/>");
        var id_lesson_end   = $("<input/>");

        id_lesson_start.datetimepicker({
            lang       : 'ch',
            timepicker : false,
            format     : 'Y-m-d ',
        });

        id_lesson_end.datetimepicker({
            lang       : 'ch',
            timepicker : false,
            format     : 'Y-m-d ',
        });

        var arr = [
            ["老师新账号",id_new_phone],
            ["需要转移的课程时间选择","如果不填则默认当天之后的未上课程"],
            ["开始时间",id_lesson_start],
        ];

        $.show_key_value_table("转移老师信息至新账号",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                var lesson_start  = id_lesson_start.val();
                var lesson_end    = id_lesson_end.val();
                data.lesson_start = lesson_start;

                var new_phone = id_new_phone.val();
                check_new_phone(new_phone,data);
            }
        });
    });

    var check_new_phone = function(new_phone,old_info){
        $.do_ajax("/human_resource/change_teacher_to_new",{
            "new_phone" : new_phone,
            "teacherid" : old_info.phone,
        },function(result){
            var new_teacherid = 0;
            if(result.ret==0){
                $.do_ajax("/tea_manage/add_teacher",{
                    "tea_nick"              : old_info.realname,
                    "teacher_money_type"    : 4,
                    "level"                 : 1,
                    "phone"                 : new_phone,
                    "phone_spare"           : old_info.phone,
                    "email"                 : old_info.email,
                    "identity"              : old_info.identity,
                    "school"                : old_info.school,
                    "trial_lecture_is_pass" : old_info.trial_lecture_is_pass,
                    "train_through_new"     : old_info.train_through_new,
                    "work_year"             : old_info.work_year,
                    "gender"                : old_info.gender,
                    "birth"                 : old_info.birth,
                    "address"               : old_info.address,
                    "subject"               : old_info.subject,
                    "grade_part_ex"         : old_info.grade_part_ex,
                    "grade_start"           : old_info.grade_start,
                    "grade_end"             : old_info.grade_end,
                    "teacher_type"          : 0,
                },function(new_result){
                    if(new_result.ret==0){
                        new_teacherid = new_result.teacherid;
                        change_tea_to_new(old_info,new_teacherid);
                    }else{
                        BootstrapDialog.alert(new_result.info);
                    }
                });
            }else{
                if(result.new_teacherid>0){
                    new_teacherid = result.new_teacherid;
                    change_tea_to_new(old_info,new_teacherid);
                }else{
                    BootstrapDialog.alert(result.info);
                }
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

    $(".opt-set-remark").on("click",function(){
	    var data = $(this).get_opt_data();
        var id_part_remarks = $("<textarea/>");

        var arr = [
            ["其他机构信息",id_part_remarks]
        ];

        $.show_key_value_table("编辑机构信息",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/tea_manage/set_teacher_part_remark",{
                    "teacherid"   : data.teacherid,
                    "part_remarks" : id_part_remarks.val(),
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

    if(acc=="alan"){
        $(".opt-edit").show();
    }

    $(".opt-account-number").on("click",function(){
	    var data = $(this).get_opt_data();
    });

});
