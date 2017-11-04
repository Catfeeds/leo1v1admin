/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_info_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ({
            teacherid              : $('#id_teacherid').val(),
            is_freeze              : $('#id_is_freeze').val(),
            free_time              : $('#id_free_time').val(),
            page_count             : $('#id_page_count').val(),
            is_test_user           : $('#id_is_test_user').val(),
            gender                 : $('#id_gender').val(),
            grade_part_ex          : $('#id_grade_part_ex').val(),
            subject                : $('#id_subject').val(),
            second_subject         : $('#id_second_subject').val(),
            address                : $('#id_address').val(),
            limit_plan_lesson_type : $('#id_limit_plan_lesson_type').val(),
            lesson_hold_flag       : $('#id_lesson_hold_flag').val(),
            train_through_new      : $('#id_train_through_new').val(),
		    sleep_teacher_flag:	$('#id_sleep_teacher_flag').val()
        });
    }


    Enum_map.append_option_list("test_user", $("#id_is_test_user"));
    Enum_map.append_option_list("gender", $("#id_gender") );
    Enum_map.append_option_list("grade_part_ex", $("#id_grade_part_ex") );
    Enum_map.append_option_list("subject", $("#id_subject") );
    Enum_map.append_option_list("subject", $("#id_second_subject") );
    Enum_map.append_option_list("boolean", $("#id_is_freeze") );

    $('#id_teacherid').val(g_args.teacherid);
    $('#id_is_freeze').val(g_args.is_freeze);
    $('#id_free_time').val(g_args.free_time);
    $('#id_page_count').val(g_args.page_count);
    $('#id_is_test_user').val(g_args.is_test_user);
    $('#id_gender').val(g_args.gender);
    $('#id_grade_part_ex').val(g_args.grade_part_ex);
    $('#id_subject').val(g_args.subject);
    $('#id_second_subject').val(g_args.second_subject);
    $('#id_address').val(g_args.address);
    $('#id_limit_plan_lesson_type').val(g_args.limit_plan_lesson_type);
    $('#id_lesson_hold_flag').val(g_args.lesson_hold_flag);
    $('#id_train_through_new').val(g_args.train_through_new);
	$('#id_sleep_teacher_flag').val(g_args.sleep_teacher_flag);
    $.admin_select_user( $("#id_teacherid"), "teacher", load_data);

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var id_tea_nick=$("<input/>");
        var id_teacher_money_type=$("<select/>");
        var id_level=$("<select/>");
        var id_gender=$("<select/>");
        var id_birth=$("<input/>");
        var id_work_year=$("<input/>");
        var id_email=$("<input/>");
        var id_realname=$("<input/>");
        var id_teacher_type=$("<select/>");
        var id_advantage=$("<input/>");
        var id_base_intro=$("<textarea/>");
        var id_need_test_lesson_flag=$("<select/>");
        var id_wx_openid=$("<input/>");
        var id_address=$("<input/>");
        var id_school=$("<input/>");
        var id_limit_day_lesson_num =$("<input/>");
        var id_limit_week_lesson_num =$("<input/>");
        var id_limit_month_lesson_num =$("<input/>");
        var id_teacher_textbook=$("<input/>");
        var id_subject=$("<select/>");
        var id_second_subject=$("<select/>");
        var id_third_subject=$("<select/>");
        var id_grade_part_ex=$("<select/>");
        var id_second_grade_part_ex=$("<select/>");
        var id_third_grade_part_ex=$("<select/>");

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
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            "onChangeDateTime" : function() {
            }
        });

        Enum_map.append_option_list("boolean", id_teacher_type, true );

        id_tea_nick.val(opt_data.nick);
        id_gender.val(opt_data.gender);
        var birth=""+opt_data.birth;
        birth= birth.substr(0,4)+"-"+ birth.substr(4,2) + "-"+ birth.substr(6,2) ;
        id_birth.val( birth );
        id_work_year.val(opt_data.work_year );
        id_realname.val(opt_data.realname );

        id_email.val(opt_data.email);
        id_teacher_type.val(opt_data.teacher_type);
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

        var arr=[
            ["全职", id_teacher_type],
            ["昵称", id_tea_nick],
            ["姓名", id_realname],
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
                birth=birth.substr(0,4)+birth.substr(5,2)+birth.substr(8,2);

                $.do_ajax( '/tea_manage_new/update_teacher_info',{
                    "teacherid"             : opt_data.teacherid,
                    "tea_nick"              : id_tea_nick.val(),
                    "realname"              : id_realname.val(),
                    "gender"                : id_gender.val(),
                    "birth"                 : birth,
                    "work_year"             : id_work_year.val(),
                    "email"                 : id_email.val(),
                    "advantage"             : id_advantage.val(),
                    "base_intro"            : id_base_intro.val(),
                    "teacher_type"          : id_teacher_type.val(),
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
                    "wx_openid"             : id_wx_openid.val()
                });
            }
        });
    });



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
                $.reload_self_page ({
                    teacherid              : $('#id_teacherid').val(),
                    is_freeze              : $('#id_is_freeze').val(),
                    free_time              : $('#id_free_time').val(),
                    page_count             : $('#id_page_count').val(),
                    is_test_user           : $('#id_is_test_user').val(),
                    gender                 : $('#id_gender').val(),
                    grade_part_ex          : $('#id_grade_part_ex').val(),
                    subject                : $('#id_subject').val(),
                    second_subject         : $('#id_second_subject').val(),
                    address                : $('#id_address').val(),
                    limit_plan_lesson_type : $('#id_limit_plan_lesson_type').val(),
                    lesson_hold_flag       : $('#id_lesson_hold_flag').val(),
                    train_through_new      : $('#id_train_through_new').val()
                });

                dialog.close();
            }
        });
    });







    $.each( $(".opt-show-lessons-new"), function(i,item ){
        $(item).admin_select_teacher_free_time_new({
            "teacherid" : $(item).get_opt_data("teacherid")
        });
    });


    $(".opt-old").on("click",function(){
        var opt_data=$(this).get_opt_data();
        opt_data.teacherid
        $.wopen("/human_resource/index_old?teacherid="+opt_data.teacherid ) ;

    });



    $(".opt-trial-pass").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var id_trial_lecture_is_pass =$("<select/>");
        Enum_map.append_option_list("boolean", id_trial_lecture_is_pass, true );

        id_trial_lecture_is_pass.val(1);
        var arr=[
            ["试讲通过", id_trial_lecture_is_pass   ]
        ];
        $.show_key_value_table("试讲通过", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                $.do_ajax( '/tea_manage_new/update_teacher_trial_lecture_is_pass', {
                    "teacherid"             : opt_data.teacherid,
                    "trial_lecture_is_pass" : id_trial_lecture_is_pass.val()
                });
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

     if (window.location.pathname=="/human_resource/index_seller" || window.location.pathname=="/human_resource/index_seller/") {
        $("#id_test_transfor_per").parent().parent().hide();
        $("#id_add_teacher").parent().hide();
        $("#id_need_test_lesson_flag").parent().parent().hide();
        $("#id_textbook_type").parent().parent().hide();
        $("#id_test_user").parent().parent().hide();
        $(".div_show").hide();
    }
    if(window.location.pathname=="/human_resource/index_tea_qua" || window.location.pathname=="/human_resource/index_tea_qua/"){

    }else{
        $(".opt-edit").hide();
    }
    if(window.location.pathname=="/human_resource/index_jw" || window.location.pathname=="/human_resource/index_jw/"){

    }else{
        $(".opt-complaints-teacher").hide();
    }


    $('.opt-change').set_input_change_event(load_data);

    $("#id_add_other_teacher").on("click",function(){
        var id_phone              = $("<input/>");
        var id_tea_nick           = $("<input/>");
        var id_teacher_type       = $("<select/>");
        var id_teacher_ref_type   = $("<select/>");
        var id_email              = $("<input/>");

        Enum_map.append_option_list("teacher_ref_type", id_teacher_ref_type);
        Enum_map.append_option_list("teacher_type", id_teacher_type,true,[0,21,22,31,41]);

        var arr = [
            ["电话", id_phone],
            ["姓名", id_tea_nick],
            ["老师类型", id_teacher_type],
            ["推荐人类型", id_teacher_ref_type],
            ["电子邮件", id_email],
        ];

        $.show_key_value_table("新增老师", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var teacher_type       = id_teacher_type.val();
                var teacher_money_type = 4;
                $.do_ajax( '/tea_manage/add_teacher',{
                    "phone"              : id_phone.val(),
                    "tea_nick"           : id_tea_nick.val(),
                    "teacher_type"       : teacher_type,
                    "teacher_ref_type"   : id_teacher_ref_type.val(),
                    "email"              : id_email.val(),
                    "teacher_money_type" : teacher_money_type,
                    "level"              : 0,
                    "identity"           : 0,
                    "add_type"           : 1,
                    "wx_use_flag"        : 0,
                });
            }
        });
    });

    $(".opt-tea_origin_url").on("click",function(){
	      var phone = $(this).get_opt_data("phone");
        var url   = "http://wx-teacher-web.leo1v1.com/tea.html?"+phone;
        BootstrapDialog.alert(url);
    });

    download_hide();

});
