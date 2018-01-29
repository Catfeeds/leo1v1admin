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

     if (window.location.pathname=="/human_resource/index_seller" || window.location.pathname=="/human_resource/index_seller/" || window.location.pathname=="/human_resource/get_elite_teacher_list" || window.location.pathname=="/human_resource/get_elite_teacher_list/") {
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
                },function(result){
                    if(result.ret!=0){
                        BootstrapDialog.alert(result.info);
                    }else{
                        load_data();
                    }
                });
            }
        });
    });

    $(".opt-tea_origin_url").on("click",function(){
	      var phone = $(this).get_opt_data("phone");
        var url   = "http://wx-teacher-web.leo1v1.com/tea.html?"+phone;
        BootstrapDialog.alert(url);
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

    $(".opt-account-number").on("click",function(){
	      var data = $(this).get_opt_data();

        
        var id_change_phone            = $("<button class='btn btn-danger'>更换手机</button>");
        var id_change_tea_to_new       = $("<button class='btn btn-primary'>账号转移</button>");
        var id_subject_info            = $("<button class='btn btn-danger'>年级/科目修改</button>");

        var id_identity                = $("<button class='btn btn-danger'>老师信息修改</button>");
       

       
        id_change_phone.on("click",function(){change_phone(data);});
        id_identity.on("click",function(){set_teacher_identity(data);});
        id_subject_info.on("click",function(){update_subject_info(data);});
        id_change_tea_to_new.on("click",function(){opt_change_tea_to_new(data);});

       

        var arr = [
            ["",id_change_phone],
            ["",id_change_tea_to_new],
            ["",id_subject_info],
            ["",id_identity]
        ];
      

        $.show_key_value_table("账号信息修改",arr);
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

    var set_teacher_identity = function(opt_data){
        var id_identity_new = $("<select/>");
        var id_realname = $("<input/>");
        var id_wx_openid = $("<input/>");
        Enum_map.append_option_list("identity", id_identity_new, true,[5,6,7,8] );
        var arr          = [
            ["姓名", id_realname],
            ["老师身份",id_identity_new],
            ["微信openid", id_wx_openid],

        ];
        id_identity_new.val(opt_data.identity);
        id_realname.val(opt_data.realname);
        id_wx_openid.val(opt_data.wx_openid);

        $.show_key_value_table("修改老师信息", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/ajax_deal2/set_teacher_identity",{
                    "teacherid"    : opt_data.teacherid,
                    "identity"     : id_identity_new.val(),
                    "realname"     : id_realname.val(),
                    "wx_openid"     : id_wx_openid.val()
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

    $("#id_set_jw_subject").on("click",function(){
        $.do_ajax( "/ajax_deal2/get_jw_subject_permission_list",{

        },function(resp){
            var data = resp.data;
            var title = "调整工作状态";
            var html_node= $("<div  id=\"div_table\"><div style=\"float:right\"><button class=\"btn btn-warning\" id=\"add_subject\"></button></div><table   class=\"table table-bordered \"><tr><td>用户</td><td>年级</td><td>科目</td><td>操作</td></tr></table></div>");


            $.each(data,function(i,item){
                html_node.find("table").append("<tr><td>"+item.account+"</td><td class=\"status_str\">"+item.admin_work_status_str+"</td><td class=\"edit_work_status\" data-uid=\""+item.uid+"\" data-status=\""+item.admin_work_status+"\"><a href=\"javascript:;\">调整</a></td></tr>");
            });
            html_node.find(".edit_work_status").on("click",function(){
                var m = $(this);
                var uid = $(this).data("uid");
                var status = $(this).data("status");
                var id_status = $("<select><option value=\"0\">休息</option><option value=\"1\">工作</option></select>");
                id_status.val(status);
                var arr =[
                    ["状态",id_status]
                ];
                $.show_key_value_table("修改状态", arr ,{
                    label    : '确认',
                    cssClass : 'btn-warning',
                    action   : function(dialog) {
                        $.do_ajax( '/ajax_deal2/set_admin_work_status',{
                            "adminid":uid,
                            "status":id_status.val()
                        },function(){
                            var status_str="工作";
                            if(id_status.val() ==0){
                                status_str="休息";
                            }
                            m.parent().find(".status_str").text(status_str);
                            dialog.close();
                        });
                    }
                });


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

    $(".opt-upload-teacher-call-crad").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_callcard_url = $("<div><input class=\"callcard_url\" id=\"callcard_url\" type=\"text\"readonly ><div><span ><a class=\"upload_callcard_pic\" id=\"id_upload_callcard_url\" href=\"javascript:;\">上传</a></span><span><a style=\"margin-left:20px\" href=\"javascript:;\" id=\"id_del_callcard_url\">删除</a></span></div></div>");
      
        var arr=[          
            ["老师名片",  id_callcard_url ]
        ];
       // id_callcard_url.find("#callcard_url").val(opt_data.rurl);      

        id_callcard_url.find("#id_del_callcard_url").on("click",function(){
            id_callcard_url.find("#callcard_url").val("");
        });

        $.show_key_value_table("上传", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/ajax_deal3/upload_teacher_callcard_info', {
                    'teacherid': opt_data.teacherid,                  
                    "callcard_url": id_callcard_url.find("#callcard_url").val()
                });
            }
        },function(){
            $.custom_upload_file('id_upload_callcard_url',true,function (up, info, file) {
                var res = $.parseJSON(info);

                id_callcard_url.find("#callcard_url").val(res.key);
            }, null,["doc", "docx","xls",'pdf','jpg','png','rar','zip','peng']);

        });
        
    });

    $("#id_add_teacher_callcard").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_callcard_url = $("<div><input class=\"callcard_url\" id=\"callcard_url\" type=\"text\"readonly ><div><span ><a class=\"upload_callcard_pic\" id=\"id_upload_callcard_url\" href=\"javascript:;\">上传</a></span><span><a style=\"margin-left:20px\" href=\"javascript:;\" id=\"id_del_callcard_url\">删除</a></span></div></div>");
        var id_teacherid = $("<input />");
        
        var arr=[          
            ["老师",id_teacherid],
            ["老师名片",  id_callcard_url ]
        ];
        // id_callcard_url.find("#callcard_url").val(opt_data.rurl);      

        id_callcard_url.find("#id_del_callcard_url").on("click",function(){
            id_callcard_url.find("#callcard_url").val("");
        });

        $.show_key_value_table("上传", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/ajax_deal3/upload_teacher_callcard_info', {
                    'teacherid': id_teacherid.val(),                  
                    "callcard_url": id_callcard_url.find("#callcard_url").val()
                });
            }
        },function(){
            $.custom_upload_file('id_upload_callcard_url',true,function (up, info, file) {
                var res = $.parseJSON(info);

                id_callcard_url.find("#callcard_url").val(res.key);
            }, null,["doc", "docx","xls",'pdf','jpg','png','rar','zip','peng']);

            $.admin_select_user(id_teacherid, "teacher");
 

        });

    });

    $(".opt-show-teacher-call-crad").on("click",function(){
        var data = $(this).get_opt_data();
        $.wopen(data.callcard_url);
    });





});
