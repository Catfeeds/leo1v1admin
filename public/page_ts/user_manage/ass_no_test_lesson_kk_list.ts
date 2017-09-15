/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-ass_no_test_lesson_kk_list.d.ts" />

function load_data(){
    $.reload_self_page ( {
    order_by_str: g_args.order_by_str,
        "assistantid"  : $("#id_assistantid").val(),
        "grade"        : $("#id_grade").val(),
        "student_type" : $("#id_student_type").val(),
        "user_name"    : $("#id_user_name").val(),
    "revisit_flag" : $('#id_revisit_flag').val(),
    warning_stu:	$('#id_warning_stu').val()
    });
}

$(function(){
    Enum_map.td_show_desc("grade", $(".td-grade"));
    Enum_map.td_show_desc("relation_ship", $(".td-parent-type"));
    Enum_map.append_option_list("student_type", $("#id_student_type"));

    //$(td-grade)

    //init  input data
    $("#id_grade").val(g_args.grade);
    $.enum_multi_select( $("#id_grade") ,"grade", function( ){ load_data()}  );

    $("#id_user_name").val(g_args.user_name);
    $("#id_phone").val(g_args.phone);
    $("#id_assistantid").val(g_args.assistantid);

    $("#id_student_type").val(g_args.student_type);
    $("#id_assistantid").val(g_args.assistantid);
    $("#id_revisit_flag").val(g_args.revisit_flag);
    $('#id_warning_stu').val(g_args.warning_stu);

    $.admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });
    //btn_s('.done_b','.mesg_alert03');//录入回访  dfa
    $(".stu_sel" ).on( "change",function(){
        load_data();
    });
    $(".for_input").on ("keypress",function(e){
        if (e.keyCode==13){
            var field_name=$(this).data("field");
            var value=$(this).val();
            load_data();
        }
    });

    $("#id_search_user").on("click",function(){
        var value=$("#id_user_name").val();
        load_data();
    });

    $("#id_search_tel").on("click",function(){
        var value=$("#id_phone").val();
        load_data();
    });

    //点击进入个人主页
    $('.opt-user').on('click',function(){
        var userid=$(this).parent().data("userid");
        var nick=$(this).parent().data("stu_nick");
        $.wopen('/stu_manage?sid='+userid );
    });

    //点击进入排课页面
    $('.opt-lesson').on('click',function(){
        var userid=$(this).parent().data("userid");
        $.wopen('/stu_manage/course_list?sid='+userid );
    });



    // 设置学生临时密码
    $(".opt-modify").on("click", function(){
        var html_node =$("<div></div>").html($.dlg_get_html_by_class('dlg_set_dynamic_passwd'));

        html_node.find(".stu_phone").text($(this).parents("td").siblings(".user_phone").text());
        html_node.find(".stu_nick").text($(this).parents("td").siblings(".user_nick").text());
        html_node.find(".dynamic_passwd").val("123456");

        BootstrapDialog.show({
            title: '设置学生动态登陆密码',
            message : html_node,
            closable: true,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        var phone = html_node.find(".stu_phone").text();
                        var passwd = html_node.find(".dynamic_passwd").val();

                        $.ajax({
                            type     :"post",
                            url      :"/user_manage/set_dynamic_passwd",
                            dataType :"json",
                            data     :{"phone":phone, "passwd": passwd, "role": 1 },
                            success  : function(result){
                                BootstrapDialog.alert(result['info']);
                            }
                        });
                        dialog.close();
                    }
                },
                {
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });
    });

    $('.opt-test-room').on('click', function(){

        var phone = $(this).parents("td").siblings(".user_phone").text();

        $.ajax({
            type     :"post",
            url      :"/user_manage/get_test_room",
            dataType :"json",
            data     :{"phone":phone },
            success  : function(result){
                if (result['ret'] != 0) {
                    BootstrapDialog.alert(result['info']);
                } else {
                    var msg = '';
                    if (result['test_room'] == '') {
                        msg = '是否设置试音室';
                    } else {
                        msg = '是否取消试音室'+result['test_room'];
                    }
                    BootstrapDialog.show({
                        title: '设置试音室',
                        message : msg,
                        closable: true,
                        buttons: [
                            {
                                label: '确认',
                                cssClass: 'btn-primary',
                                action: function(dialog) {

                                    $.ajax({
                                        type     :"post",
                                        url      :"/user_manage/set_test_room",
                                        dataType :"json",
                                        data     :{"phone":phone },
                                        success  : function(result){
                                            BootstrapDialog.alert(result['info']);
                                        }
                                    });
                                    dialog.close();
                                }
                            },
                            {
                                label: '取消',
                                cssClass: 'btn',
                                action: function(dialog) {
                                    dialog.close();
                                }
                            }]
                    });

                }
            }
        });

    });


    Enum_map.append_option_list("student_type",$("#id_set_channel"),true);
    Enum_map.append_option_list("is_auto_set_type_flag",$("#id_auto_set_flag"),true);
    $(".opt-change-type-new").on("click", function(){
        var userid = $(this).parent().data("userid");
        var nick   = $(this).parent().data("nick");
        var is_auto_set_type_flag   = $(this).parent().data("autoflag");
        var lesson_stop_reason   = $(this).parent().data("reason");
        var student_type   = $(this).parent().data("type");
        var recover_time="";
        var wx_remind_time="";
        var stop_duration="";

        
        $.do_ajax("/user_manage_new/get_last_change_type_info",{
            "userid" : userid
        },function(result){
            var data = result.data;
            if(data==false){
            }else{
                lesson_stop_reason = data.reason;
                student_type = data.type_cur;
                recover_time = data.recover_time;
                wx_remind_time = data.wx_remind_time;
                stop_duration = data.stop_duration;
            }
            
            var id_auto_set_flag = $("<select ><option value=\"0\">系统自动更新</option><option value=\"1\">手动修改</option></select>");
            var id_student_type = $("<select />");
            var id_lesson_stop_reason = $("<textarea />");
            var id_recover_time = $("<input />");
            var id_wx_remind_time = $("<input />");
            var id_stop_duration = $("<input />");
            Enum_map.append_option_list( "student_type",  id_student_type,true);
            id_recover_time.datetimepicker({
                datepicker:true,
                timepicker:false,
                format:'Y-m-d ',
                step:30,
                onChangeDateTime :function(){

                }
            });
            
            id_wx_remind_time.datetimepicker({
                datepicker:true,
                timepicker:false,
                format:'Y-m-d',
                step:30,
                onChangeDateTime :function(){

                }
            });


            var arr = [
               // [ "是否系统自动更新：",  id_auto_set_flag] ,
                [ "学员类型",  id_student_type] ,
                [ "原因",  id_lesson_stop_reason] ,
                ["时长",  id_stop_duration ],
                ["预计复课时间",  id_recover_time ],
                ["微信提醒时间",  id_wx_remind_time ],
            ];
            id_auto_set_flag.val(is_auto_set_type_flag);
            id_student_type.val(student_type);
            id_lesson_stop_reason.val(lesson_stop_reason);
            id_stop_duration.val(stop_duration);
            id_recover_time.val(recover_time);
            id_wx_remind_time.val(wx_remind_time);

            var show_field=function (jobj,show_flag) {
                if ( show_flag ) {
                    jobj.parent().parent().show();
                }else{
                    jobj.parent().parent().hide();
                }
            };

            var reset_ui=function() {
                var val=id_student_type.val();
                if (val>1) {
                    show_field( id_recover_time ,true);
                    show_field( id_stop_duration,true);
                    show_field( id_wx_remind_time,true);
                }else{
                    show_field( id_recover_time ,false );
                    show_field( id_stop_duration,false );
                    show_field( id_wx_remind_time,false);
                }
               


            };

            id_student_type.on("change",function(){
                reset_ui();
            });


            $.show_key_value_table("修改类型", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    $.ajax({
                        type     :"post",
                        url      :"/user_manage/set_stu_type",
                        dataType :"json",
                        data     :{
                            "userid":userid,
                            "type":id_student_type.val(),
                            "is_auto_set_type_flag":1,
                            "lesson_stop_reason":id_lesson_stop_reason.val(),
                            "recover_time"  :id_recover_time.val(),
                            "wx_remind_time"  :id_wx_remind_time.val(),
                            "stop_duration" :id_stop_duration.val()
                        },
                        success  : function(result){
                            if(result['ret'] != 0){
                                alert(result['info']);
                            }else{
                                window.location.reload();
                            }
                        }
                    });
                  
                }
            },function(){
                reset_ui();
            });
            
        });            

    });
    
    $(".opt-change-type").on("click", function(){
        var userid = $(this).parent().data("userid");
        var nick   = $(this).parent().data("nick");
        var is_auto_set_type_flag   = $(this).parent().data("autoflag");
        var lesson_stop_reason   = $(this).parent().data("reason");
        var student_type   = $(this).parent().data("type");

        console.log(student_type);
        var html_node=$('<div></div>').html($.dlg_get_html_by_class('cl_dlg_change_type'));
        html_node.find("#id_set_channel").val(student_type);
        html_node.find("#id_auto_set_flag").val(is_auto_set_type_flag);
        html_node.find("#id_lesson_stop_reason").val(lesson_stop_reason);

        BootstrapDialog.show({
            title: '设置学员类型',
            message : html_node,
            closable: false,
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var stu_type     = html_node.find("#id_set_channel").val();
                    var is_auto_set_type_flag    = html_node.find("#id_auto_set_flag").val();
                    var lesson_stop_reason     = html_node.find("#id_lesson_stop_reason").val();
                    $.ajax({
                        type     :"post",
                        url      :"/user_manage/set_stu_type",
                        dataType :"json",
                        data     :{
                            "userid":userid,
                            "type":stu_type,
                            "is_auto_set_type_flag":is_auto_set_type_flag,
                            "lesson_stop_reason":lesson_stop_reason
                        },
                        success  : function(result){
                            if(result['ret'] != 0){
                                alert(result['info']);
                            }else{
                                window.location.reload();
                            }
                        }
                    });
                }
            }]
        });
    });
    $(".opt-left-time").on("click", function(){
        var studentid = $(this).parent().data("userid");
        $.ajax({
            url: '/user_deal/reset_lesson_count',
            type: 'POST',
            dataType: 'json',
            data:{
                'studentid' : studentid
            },
            success: function(data) {
                if(data.ret != -1){
                    window.location.reload();
                }
            }
        });

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

    init_noit_btn("id_sumweek",    "未做学情回访人数" );
    init_noit_btn("id_summonth",    "未做月度回访人数" );
    $("#id_sumweek").on("click",function(){

        $('#id_is_grade').val("");

        $('#id_assistantid').val("");
        $('#id_student_type').val(0);
        $("#id_user_name").val("");
        $("#id_revisit_flag").val(1);
        load_data();
    });
    $("#id_summonth").on("click",function(){

        $('#id_is_grade').val("");

        $('#id_assistantid').val("");
        $('#id_student_type').val(0);
        $("#id_user_name").val("");
        $("#id_revisit_flag").val(2);
        load_data();
    });

    $(".opt-telphone").on("click",function(){
        //
        var opt_data= $(this).get_opt_data();

        $.do_ajax_t("/ss_deal/call_ytx_phone", {
            "phone": opt_data.phone
        } );
    });


    $(".opt-return-back-new").on("click", function(){
        var userid = $(this).parent().data("userid");
        var id_return_record_time = $("<input placeholder=\" 用于录入遗漏的回访信息,可不填写\"/>");
        var id_return_record_type = $("<select />");
        var id_return_record_person = $("<select />");
        var id_return_record_record = $("<textarea />");

        var $operation_satisfy_flag =$("<select/>");
        var $operation_satisfy_type =$("<select/>");
        var $operation_satisfy_info  = $("<textarea/>");

        var $child_class_performance_flag =$("<select/>");
        var $child_class_performance_type =$("<select/>");
        var $child_class_performance_info  = $("<textarea/>");

        var $school_score_change_flag  =$("<select/>");
        var $school_score_change_info   = $("<textarea/>");
        var $school_work_change_flag =$("<select/>");
        var $school_work_change_type =$("<select/>");
        var $school_work_change_info  = $("<textarea/>");

        var $tea_content_satisfy_flag  =$("<select/>");
        var $tea_content_satisfy_type =$("<select/>");
        var $tea_content_satisfy_info  = $("<textarea/>");

        var $other_parent_info  = $("<textarea/>");
        var $other_warning_info   = $("<textarea/>");
        var $is_warning_flag  =$("<select/>");



        Enum_map.append_option_list( "set_boolean",  $operation_satisfy_flag,true);
        Enum_map.append_option_list( "set_boolean",  $school_work_change_flag,true);
        Enum_map.append_option_list( "child_class_performance_type",  $child_class_performance_type,true);
        Enum_map.append_option_list( "operation_satisfy_type", $operation_satisfy_type,true);
        Enum_map.append_option_list( "child_class_performance_flag", $child_class_performance_flag,true);
        Enum_map.append_option_list( "school_score_change_flag", $school_score_change_flag,true);
        Enum_map.append_option_list( "school_work_change_type", $school_work_change_type,true);
        Enum_map.append_option_list( "tea_content_satisfy_type", $tea_content_satisfy_type,true);
        Enum_map.append_option_list( "tea_content_satisfy_flag", $tea_content_satisfy_flag,true);
        Enum_map.append_option_list("revisit_type",id_return_record_type,true,[0,1,2,3]);
        Enum_map.append_option_list("revisit_person",id_return_record_person,true,[0,1,2,3]);

        id_return_record_time.datetimepicker({
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i',
            step:30,
            onChangeDateTime :function(){

            }

        });


        var arr = [
          //  [ "回访时间",  id_return_record_time] ,
            [ "回访类型",  id_return_record_type] ,
            [ "回访对象",  id_return_record_person] ,
            [ "回访记录",  id_return_record_record] ,

            ["软件操作是否满意",  $operation_satisfy_flag ],
            ["软件操作不满意的类型",  $operation_satisfy_type ],
            ["软件操作不满意的具体描述",  $operation_satisfy_info ],

            ["孩子课堂表现",  $child_class_performance_flag ],
            ["孩子课堂表现不好的类型",  $child_class_performance_type ],
            ["孩子课堂表现不好的具体描述",  $child_class_performance_info ],

            ["学校成绩变化",  $school_score_change_flag ],
            ["学校成绩变差的具体描述",  $school_score_change_info ],

            ["学业变化",  $school_work_change_flag ],
            ["学业变化的类型",  $school_work_change_type ],
            ["学业变化的具体描述",  $school_work_change_info ],


            ["对于老师or教学是否满意",  $tea_content_satisfy_flag ],
            ["对于老师or教学不满意的类型",  $tea_content_satisfy_type ],
            ["对于老师or教学不满意的具体描述",  $tea_content_satisfy_info ],

            ["家长意见或建议",  $other_parent_info ],
            ["其他预警问题",  $other_warning_info ],
        ];

        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        var reset_ui=function() {
            var val1=$operation_satisfy_flag.val();
            var val2=$tea_content_satisfy_flag.val();
            var val3=$child_class_performance_flag.val();
            var val4=$school_score_change_flag.val();
            var val5=$school_work_change_flag.val();
            if (val1==1 || val1==0) {
                show_field( $operation_satisfy_type ,false );
                show_field( $operation_satisfy_info,false );
            }else{
                show_field( $operation_satisfy_type ,true);
                show_field( $operation_satisfy_info,true);
            }
            if (val2==1 || val2==0 || val2==2) {
                show_field( $tea_content_satisfy_type ,false );
                show_field( $tea_content_satisfy_info,false );
            }else{
                show_field( $tea_content_satisfy_type ,true);
                show_field( $tea_content_satisfy_info,true);
            }
            if (val3==1 || val3==0 || val3==2) {
                show_field( $child_class_performance_type ,false );
                show_field( $child_class_performance_info,false );
            }else{
                show_field( $child_class_performance_type ,true);
                show_field( $child_class_performance_info,true);
            }
            if (val4==1 || val4==0) {
                show_field( $school_score_change_info,false );
            }else{
                show_field( $school_score_change_info,true);
            }

            if (val5==2 || val5==0) {
                show_field( $school_work_change_type ,false );
                show_field( $school_work_change_info,false );
            }else{
                show_field( $school_work_change_type ,true);
                show_field( $school_work_change_info,true);
            }


        };

        $operation_satisfy_flag.on("change",function(){
            reset_ui();
        });
        $tea_content_satisfy_flag.on("change",function(){
            reset_ui();
        });
        $child_class_performance_flag.on("change",function(){
            reset_ui();
        });
        $school_score_change_flag.on("change",function(){
            reset_ui();
        });
        $school_work_change_flag.on("change",function(){
            reset_ui();
        });


        $.show_key_value_table("修改", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/revisit/add_revisit_record", {
                    "userid":userid,
                    "operator_note":id_return_record_record.val(),

                    "revisit_person":id_return_record_person.find("option:selected").text(),
                    "revisit_type":id_return_record_type.val(),
                    "revisit_time":id_return_record_time.val(),

                    "operation_satisfy_flag": $operation_satisfy_flag.val(),
                    "operation_satisfy_type": $operation_satisfy_type.val(),
                    "operation_satisfy_info": $operation_satisfy_info.val(),

                    "child_class_performance_flag": $child_class_performance_flag.val(),
                    "child_class_performance_type": $child_class_performance_type.val(),
                    "child_class_performance_info": $child_class_performance_info.val(),

                    "tea_content_satisfy_flag": $tea_content_satisfy_flag.val(),
                    "tea_content_satisfy_type": $tea_content_satisfy_type.val(),
                    "tea_content_satisfy_info": $tea_content_satisfy_info.val(),

                    "other_parent_info": $other_parent_info.val(),
                    "other_warning_info": $other_warning_info.val(),

                    "school_score_change_flag": $school_score_change_flag.val(),
                    "school_score_change_info": $school_score_change_info.val(),
                    "school_work_change_flag": $school_work_change_flag.val(),
                    "school_work_change_type": $school_work_change_type.val(),
                    "school_work_change_info": $school_work_change_info.val()
                });
            }
        },function(){
             reset_ui();
        });

    });

    $(".opt-return-back-lesson").on("click", function(){
        var userid = $(this).parent().data("userid");

        var id_recover_time = $("<input />");                 //复课时间
        var id_parent_guidance_except  = $("<textarea />");   //家长辅导预期
        var id_tutorial_subject_info = $("<input />");        //其他科目情况
        var id_other_subject_info = $("<textarea />");        //最近学习情况

        var id_return_record_type = $("<select />");          //回访类型
        var id_revisit_path  = $("<select />");
        var id_return_record_person = $("<select />");        //回访对象
        var id_return_record_record = $("<textarea />");      //回访记录

        var $operation_satisfy_flag =$("<select/>");          //操作是否满意
        var $operation_satisfy_type =$("<select/>");          //操作不满意类型
        var $operation_satisfy_info  = $("<textarea/>");      //操作不满意描述

        var $child_class_performance_flag =$("<select/>");    //孩子课堂表现
        var $child_class_performance_type =$("<select/>");    //孩子课堂表现不好的类型
        var $child_class_performance_info  = $("<textarea/>"); //孩子课堂表现不好的具体描述

        var $school_score_change_flag  =$("<select/>");        //学校成绩变化
        var $school_score_change_info   = $("<textarea/>");    //学校成绩变差的具体描述
        var $school_work_change_flag =$("<select/>");          //学业变化
        var $school_work_change_type =$("<select/>");          //学业变化的类型
        var $school_work_change_info  = $("<textarea/>");      //学业变化的具体描述

        var $tea_content_satisfy_flag  =$("<select/>");        //教学满意
        var $tea_content_satisfy_type =$("<select/>");         //教学满意类型
        var $tea_content_satisfy_info  = $("<textarea/>");     //教学满意的具体描述
        var id_recent_learn_info  = $("<textarea/>");          //最近学校情况

        var $other_parent_info  = $("<textarea/>");            //家长意见或建议
        var $other_warning_info   = $("<textarea/>");          //其他预警问题

        var $is_warning_flag  =$("<select/>");
        var id_self_introduction    = $("<div class=\"check_flag\"><input type=\"checkbox\" id=\"self_intro\"></div>");
        var id_check_lesson_time    = $("<div class=\"check_flag\"><input type=\"checkbox\" id=\"check_lesson\"></div>");
        var id_bulid_wx_qun         = $("<div class=\"check_flag\"><input type=\"checkbox\" id=\"bulid_wx\"></div>");
        var id_parent_introduction  = $("<div class=\"check_flag\"><input type=\"checkbox\" id=\"parent_intro\"></div>");
        var id_parent_wx_introduction = $("<div class=\"check_flag\"><input type=\"checkbox\" id=\"parent_wx_intro\"></div>");
        var id_homework_method      = $("<div class=\"check_flag\"><input type=\"checkbox\" id=\"homework_method\"></div>");
        var id_leave_send           = $("<div class=\"check_flag\"><input type=\"checkbox\" id=\"leave_send\"></div>");
        var id_educational_system   = $("<div class=\"check_flag\"><input type=\"checkbox\" id=\"educate_system\"></div>");
        var id_subject_confirm      = $("<div class=\"check_flag\"><input type=\"checkbox\" id=\"subject\"></div>");
        var id_grade_confirm        = $("<div class=\"check_flag\"><input type=\"checkbox\" id=\"grade\"></div>");
        var id_textbook_confirm     = $("<div class=\"check_flag\"><input type=\"checkbox\" id=\"textbook\"></div>");

        Enum_map.append_option_list( "set_boolean",  $operation_satisfy_flag,true);
        Enum_map.append_option_list( "set_boolean",  $school_work_change_flag,true);
        Enum_map.append_option_list( "child_class_performance_type",  $child_class_performance_type,true);
        Enum_map.append_option_list( "operation_satisfy_type", $operation_satisfy_type,true);
        Enum_map.append_option_list( "child_class_performance_flag", $child_class_performance_flag,true);
        Enum_map.append_option_list( "school_score_change_flag", $school_score_change_flag,true);
        Enum_map.append_option_list( "school_work_change_type", $school_work_change_type,true);
        Enum_map.append_option_list( "tea_content_satisfy_type", $tea_content_satisfy_type,true);
        Enum_map.append_option_list( "tea_content_satisfy_flag", $tea_content_satisfy_flag,true);
        Enum_map.append_option_list("revisit_type",id_return_record_type,true,[0,3,4,5,6]);
        Enum_map.append_option_list("revisit_person",id_return_record_person,true,[0,1,2,3]);
        Enum_map.append_option_list("revisit_path",id_revisit_path,true);

        id_recover_time.datetimepicker({
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i',
            step:30,
            onChangeDateTime :function(){

            }

        });

        var arr = [
            [ "回访类型",  id_return_record_type] ,
            [ "回访路径",  id_revisit_path] ,
            [ "回访对象",  id_return_record_person] ,
            [ "回访记录",  id_return_record_record] ,
            [ "其他情况说明", id_recent_learn_info] , 
            [ "复课时间", id_recover_time],
            ["软件操作是否满意",  $operation_satisfy_flag ],
            ["软件操作不满意的类型",  $operation_satisfy_type ],
            ["软件操作不满意的具体描述",  $operation_satisfy_info ],
            ["孩子课堂表现",  $child_class_performance_flag ],
            ["孩子课堂表现不好的类型",  $child_class_performance_type ],
            ["孩子课堂表现不好的具体描述",  $child_class_performance_info ],
            ["学校成绩变化",  $school_score_change_flag ],
            ["学校成绩变差的具体描述",  $school_score_change_info ],
            ["学业变化",  $school_work_change_flag ],
            ["学业变化的类型",  $school_work_change_type ],
            ["学业变化的具体描述",  $school_work_change_info ],
            ["对于老师or教学是否满意",  $tea_content_satisfy_flag ],
            ["对于老师or教学不满意的类型",  $tea_content_satisfy_type ],
            ["对于老师or教学不满意的具体描述",  $tea_content_satisfy_info ],
            ["家长意见或建议",  $other_parent_info ],
            ["其他预警问题",  $other_warning_info ],

            ["自我介绍", id_self_introduction ],
            ["上课时间核对", id_check_lesson_time ],
            ["微信群建立",id_bulid_wx_qun],
            ["家长端介绍",id_parent_introduction],
            ["家长微信公众号介绍",id_parent_wx_introduction],
            ["做作业方式",id_homework_method],
            ["请假制度发送",id_leave_send],
            ["学制确认",id_educational_system],
            ["年级确认",id_grade_confirm],
            ["科目确认",id_subject_confirm],
            ["教材版本确认",id_textbook_confirm],
            ["家长辅导预期",id_parent_guidance_except],
            ["辅导科目情况",id_tutorial_subject_info],
            ["其他科目情况",id_other_subject_info],
        ];

        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };
        var hidden_field=function(){       
            show_field( $operation_satisfy_flag ,false );
            show_field( $child_class_performance_flag ,false );
            show_field( $school_score_change_flag ,false );
            show_field( $school_work_change_flag ,false );
            show_field( $tea_content_satisfy_flag ,false );
            show_field( $school_work_change_flag ,false );
            show_field( $other_parent_info ,false );
            show_field( $other_warning_info ,false );
            show_field( id_self_introduction ,false );
            show_field( id_check_lesson_time ,false );
            show_field( id_other_subject_info ,false );
            show_field( id_recover_time,false );
            show_field( id_self_introduction ,false );
            show_field( id_check_lesson_time ,false );
            show_field( id_bulid_wx_qun ,false );
            show_field( id_recover_time, false);
            show_field( id_parent_introduction ,false );
            show_field( id_parent_wx_introduction ,false );
            show_field( id_homework_method ,false );
            show_field( id_leave_send ,false );
            show_field( id_educational_system ,false );
            show_field( id_grade_confirm ,false );
            show_field( id_subject_confirm ,false );
            show_field( id_textbook_confirm ,false );
            show_field( id_parent_guidance_except ,false );
            show_field( id_tutorial_subject_info ,false );
            show_field( id_recent_learn_info, false);

            show_field( $tea_content_satisfy_type, false);
            show_field( $tea_content_satisfy_info, false);
            show_field( $operation_satisfy_type, false);
            show_field( $operation_satisfy_info, false);
            show_field( $child_class_performance_type, false);
            show_field( $child_class_performance_info, false);
            show_field( $school_score_change_info, false);
            show_field( $school_work_change_type, false);
            show_field( $school_work_change_info, false);

            show_field( id_revisit_path ,false);
            show_field( id_return_record_person ,false );
            show_field( id_return_record_record ,false);

        }
        var reset_ui=function() {
            var var0=id_return_record_type.val();
            var val1=$operation_satisfy_flag.val();
            var val2=$tea_content_satisfy_flag.val();
            var val3=$child_class_performance_flag.val();
            var val4=$school_score_change_flag.val();
            var val5=$school_work_change_flag.val();
            if(var0 == 0){ //学情回访
                hidden_field();
                show_field( id_return_record_type ,true );
                show_field( id_return_record_person ,true );
                show_field( id_return_record_record ,true );
                show_field( $operation_satisfy_flag ,true );
                show_field( $child_class_performance_flag ,true );
                show_field( $school_score_change_flag ,true );
                show_field( $school_work_change_flag ,true );
                show_field( $tea_content_satisfy_flag ,true );
                show_field( $other_parent_info ,true );
                show_field( $other_warning_info ,true );
            }else if(var0 == 3){ //其他回访
                hidden_field();
                show_field( id_revisit_path ,true);
                show_field( id_return_record_person ,true);
                show_field( id_recover_time ,true);
                show_field( $other_parent_info ,true);
                show_field( $other_warning_info ,true);
                show_field( id_recent_learn_info,true);  
            }else if(var0 == 4){//首次课前回访
                hidden_field();
                show_field( id_revisit_path ,true);
                show_field( id_return_record_person ,true);
                show_field( id_self_introduction ,true);
                show_field( id_check_lesson_time, true);
                show_field( id_bulid_wx_qun, true);
                show_field( id_parent_introduction, true);
                show_field( id_parent_wx_introduction, true);
                show_field( id_homework_method, true);
                show_field( id_leave_send, true);
                show_field( id_educational_system, true);
                show_field( id_grade_confirm, true);
                show_field( id_subject_confirm, true);
                show_field( id_textbook_confirm, true);
                show_field( id_parent_guidance_except, true);
                show_field( id_other_subject_info, true);
                show_field( id_tutorial_subject_info, true);
            }else if(var0 == 5){//首次课后回访
                hidden_field();
                show_field( id_revisit_path ,true);
                show_field( id_return_record_person ,true);
                show_field( $operation_satisfy_flag ,true );
                show_field( $child_class_performance_flag ,true );
                show_field( $tea_content_satisfy_flag ,true );
                show_field( $other_parent_info ,true );
                show_field( $other_warning_info ,true );
            }else if(var0 == 6){//停课月度回访
                hidden_field();
                show_field( id_revisit_path ,true );
                show_field( id_return_record_person ,true );
                show_field( id_return_record_record ,true );
            }
            if (val1==1 || val1==0) {
                show_field( $operation_satisfy_type ,false );
                show_field( $operation_satisfy_info,false );
            }else{
                show_field( $operation_satisfy_type ,true);
                show_field( $operation_satisfy_info,true);
            }
            if (val2==1 || val2==0 || val2==2) {
                show_field( $tea_content_satisfy_type ,false );
                show_field( $tea_content_satisfy_info,false );
            }else{
                show_field( $tea_content_satisfy_type ,true);
                show_field( $tea_content_satisfy_info,true);
            }
            if (val3==1 || val3==0 || val3==2) {
                show_field( $child_class_performance_type ,false );
                show_field( $child_class_performance_info,false );
            }else{
                show_field( $child_class_performance_type ,true);
                show_field( $child_class_performance_info,true);
            }
            if (val4==1 || val4==0) {
                show_field( $school_score_change_info,false );
            }else{
                show_field( $school_score_change_info,true);
            }

            if (val5==2 || val5==0) {
                show_field( $school_work_change_type ,false );
                show_field( $school_work_change_info,false );
            }else{
                show_field( $school_work_change_type ,true);
                show_field( $school_work_change_info,true);
            }


        };
        id_return_record_type.on("change", function(){
            reset_ui();
        });
        $operation_satisfy_flag.on("change",function(){
            reset_ui();
        });
        $tea_content_satisfy_flag.on("change",function(){
            reset_ui();
        });
        $child_class_performance_flag.on("change",function(){
            reset_ui();
        });
        $school_score_change_flag.on("change",function(){
            reset_ui();
        });
        $school_work_change_flag.on("change",function(){
            reset_ui();
        });


        $.show_key_value_table("回访录入-new", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                //console.log(id_self_introduction.parent().parent().parent());
                var information_confirm=[];
                id_self_introduction.parent().parent().parent().find(".check_flag").each(function(){
                    var ss= $(this).find("input:checked").length;
                    var name = $(this).find("input").attr("id");
                    information_confirm.push("{"+name+":"+ss+"}");
                });

                if(id_return_record_type.val()=== '0'){//学情回访
                    if(id_return_record_record.val() === ''){
                        alert("请输入回访记录!");
                        return;
                    }
                    if($operation_satisfy_flag.val() <= 0){
                        alert("请选择软件操作是否满意!");
                        return;
                    }
                    if($child_class_performance_flag.val() <=0){
                        alert("请选择孩子课堂表现!");
                        return;
                    }
                    if($school_score_change_flag.val() <=0){
                        alert("请选择学校成绩变化!");
                        return;
                    }
                    if($school_work_change_flag.val() <=0){
                        alert("请选择学业变化!");
                        return;
                    }
                    if($tea_content_satisfy_flag.val() <=0){
                        alert("请选择对于老师or教学是否满意");
                        return;
                    }

                }else if(id_return_record_type.val()==='3'){ //其他回访
                    if(id_recent_learn_info.val()=== ''){
                        alert("请输入其他情况说明!");
                        return;
                    }
                    if(id_recover_time.val() <= 0){
                        alert("请选择复课时间!");
                        return;
                    }
             
                }else if(id_return_record_type.val()==='4'){//首次课前回访
                    if(id_parent_guidance_except.val() === ''){
                        alert("请输入家长辅导预期!");
                        return;
                    }
                    if(id_tutorial_subject_info.val() === ''){
                        alert("请输入辅导科目情况!");
                        return;
                    }
                    if(id_other_subject_info.val() === ''){
                        alert("请输入其他科目情况!");
                        return;
                    }

                }else if(id_return_record_type.val()==='5'){//首次课后回访
                    if($operation_satisfy_flag.val() <=0){
                        alert("请选择软件操作是否满意");
                        return;
                    }
                    if($child_class_performance_flag.val() <= 0){
                        alert("请选择孩子课堂表现!");
                        return;
                    }
                    if($tea_content_satisfy_flag.val() <=0){
                        alert("请选择对于老师or教学是否满意!");
                        return;
                    }

                }else if(id_return_record_type.val()==='6'){//停课月度回访
                    if(id_return_record_record.val() === ''){
                        alert("请输入回访记录!");
                        return;
                    }
                }

                //alert(information_confirm);
                $.do_ajax("/revisit/add_revisit_record_b2", {
                    "userid":userid,
                    "operator_note":id_return_record_record.val(),
                    "revisit_person":id_return_record_person.find("option:selected").text(),
                    "revisit_type":id_return_record_type.val(),
                    "operation_satisfy_flag": $operation_satisfy_flag.val(),
                    "operation_satisfy_type": $operation_satisfy_type.val(),
                    "operation_satisfy_info": $operation_satisfy_info.val(),
                    "child_class_performance_flag": $child_class_performance_flag.val(),
                    "child_class_performance_type": $child_class_performance_type.val(),
                    "child_class_performance_info": $child_class_performance_info.val(),
                    "tea_content_satisfy_flag": $tea_content_satisfy_flag.val(),
                    "tea_content_satisfy_type": $tea_content_satisfy_type.val(),
                    "tea_content_satisfy_info": $tea_content_satisfy_info.val(),
                    "other_parent_info": $other_parent_info.val(),
                    "other_warning_info": $other_warning_info.val(),
                    "school_score_change_flag": $school_score_change_flag.val(),
                    "school_score_change_info": $school_score_change_info.val(),
                    "school_work_change_flag": $school_work_change_flag.val(),
                    "school_work_change_type": $school_work_change_type.val(),
                    "school_work_change_info": $school_work_change_info.val(),
                    "information_confirm"    : information_confirm,
                    "id_parent_guidance_except": id_parent_guidance_except.val(),
                    "id_other_subject_info"  : id_other_subject_info.val(),
                    "id_tutorial_subject_info" : id_tutorial_subject_info.val(),
                    "id_recover_time"        : id_recover_time.val(),
                    "id_revisit_path"        : id_revisit_path.val(),
                    "id_recent_learn_info"   : id_recent_learn_info.val(),
                });
            }
        },function(){
            reset_ui();
        });

    });

    $(".opt-return-back-list-new").on("click",function(){
        var userid=$(this).parent().data("userid");
        var phone=$(this).parent().data("phone");

    $.ajax({
      type     :"post",
      url      :"/revisit/get_revisit_info_new",
      dataType :"json",
      data     :{"userid":userid,phone:phone},
      success  : function(result){
        var html_str="<table class=\"table table-bordered table-striped\"  > ";
                html_str+=" <tr><th> 时间  <th> 回访类型 <th> 负责人 <th>对象 <th>内容<th>软件操作是否满意<th>软件操作不满意的类型<th>软件操作不满意的描述<th>孩子课堂表现<th>孩子课堂表现不好的类型<th>孩子课堂表现不好的描述<th>学校成绩变化<th>学校成绩变化变差的描述 <th>学业是否变化 <th>学业变化的类型 <th>学业变化的描述 <th>对老师or教学是否满意<th>对老师or教学不满意的类型<th>对老师or教学不满意的描述<th>家长意见或建议<th>其他预警问题<th>预警情况</tr>   ";
        $.each( result.revisit_list ,function(i,item){
                    //console.log(item);
                    //return;
                    var revisit_person  ="";
                    if(item.revisit_person  ) {
                        revisit_person  = item.revisit_person;
                    }
          html_str=html_str+"<tr><td>"+item.revisit_time +"</td><td>"+item.revisit_type+"</td><td>"+ item.sys_operator +"</td><td>"+revisit_person+"</td><td>"+item.operator_note+" </td><td>"+item.operation_satisfy_flag_str+" </td><td>"+item.operation_satisfy_type_str+" </td><td>"+item.operation_satisfy_info+" </td><td>"+item.child_class_performance_flag_str+" </td><td>"+item.child_class_performance_type_str+" </td><td>"+item.child_class_performance_info+" </td><td>"+item.school_score_change_flag_str+" </td><td>"+item.school_score_change_info+" </td><td>"+item.school_work_change_flag_str+" </td><td>"+item.school_work_change_type_str+" </td><td>"+item.school_work_change_info+" </td><td>"+item.tea_content_satisfy_flag_str+" </td><td>"+item.tea_content_satisfy_type_str+" </td><td>"+item.tea_content_satisfy_info+" </td><td>"+item.other_parent_info+" </td><td>"+item.other_warning_info+" </td><td>"+item.is_warning_flag_str+" </td></tr>";
        } );



                var dlg=BootstrapDialog.show({
                    title: '回访记录',
                    message :  html_str ,
                    closable: true,
                    buttons: [{
                        label: '查看全部',
                        cssClass: 'btn-warning',
                        action: function(dialog) {
                            $.wopen("/stu_manage/return_record?sid="+userid);
                        }
                    },{

                        label: '返回',
                        action: function(dialog) {
                            //dlg.setSize(BootstrapDialog.SIZE_WIDE);
                            dialog.close();
                        }
                    }]
                });

                if (!$.check_in_phone()) {
                    dlg.getModalDialog().css("width", "1200px");
                }

      }
    });

  });

    if (window.location.pathname=="/user_manage/ass_archive_ass") {
        $("#id_assistantid").parent().parent().hide();
    }

    $("a[data-field-name='ass_assign_time']").parent().hide();

    $("#id_summonth").hide();

    $(".opt-change-teacher").on("click",function(){
        var userid = $(this).parent().data("userid");
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/tea_manage/get_course_list_new",
            //其他参数
            "args_ex" : {
                "userid"  :  userid
            },

            select_primary_field   : "courseid",
            select_display         : "package_name",
            select_no_select_value : 0,
            select_no_select_title : "[未设置]",

            //字段列表
            'field_list' :[
                {
                    title:"courseid",
                    width :50,
                    field_name:"courseid"
                },{
                    title:"类型",
                    field_name:"course_type"
                },{
                    title:"老师",
                    field_name:"teacher_nick"
                },{
                    title:"科目",
                    field_name:"subject"
                },{
                    title:"年级",
                    field_name:"grade"
                },{
                    title:"状态",
                    field_name:"course_status"
                },{
                    title:"课次总数",
                    field_name:"lesson_total"

                },{
                    title:"剩余课时数",
                    field_name:"lesson_left"

                },{
                    title:"默认课时数",
                    field_name:"default_lesson_count"
                }
            ] ,
            //查询列表
            filter_list:[
            ],
            "auto_close" : true,
            "onChange"   : function( val) {
                var courseid = val ;
                if (courseid>0) {
                    $.do_ajax("/ss_deal/get_course_order_info",{
                        "courseid":courseid
                    },function(resp){
                        var list = resp.data;
                        var id_realname = $("<input readonly/>");
                        var id_subject = $("<input readonly/>");
                        var id_grade = $("<input readonly/>");
                        var id_stu_score_info = $("<input />");
                        var id_stu_character_info = $("<input />");
                        var id_phone_location = $("<input />");
                        var id_textbook = $("<input />");
                        var id_change_teacher_reason_type = $("<select />");
                        var id_change_reason = $("<textarea />");
                        var id_except_teacher  = $("<textarea />");
                        var id_change_reason_url = $("<div><input class=\"change_reason_url\" id=\"change_reason_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_change_reason\" href=\"javascript:;\">上传</a></span></div>");
                        Enum_map.append_option_list( "change_teacher_reason_type", id_change_teacher_reason_type,true);
                        id_realname.val(list.realname);
                        id_subject.val(list.subject_str);
                        id_grade.val(list.grade_str);
                        id_stu_score_info.val(list.stu_score_info);
                        id_stu_character_info.val(list.stu_character_info);
                        id_phone_location.val(list.phone_location);
                        id_textbook.val(list.textbook);
                        var arr = [
                            [ "老师",  id_realname] ,
                            [ "科目",  id_subject] ,
                            [ "年级",  id_grade] ,
                            ["教材版本",  id_textbook ],
                            ["学生成绩",  id_stu_score_info ],
                            ["学生性格",  id_stu_character_info ],
                            ["地区",  id_phone_location ],
                            ["申请原因类型",  id_change_teacher_reason_type],
                            ["申请原因",  id_change_reason ],
                            ["申请原因(图片)",  id_change_reason_url ],
                            ["期望老师",  id_except_teacher ]
                        ];

                        $.show_key_value_table("更换老师申请", arr ,{
                            label    : '确认',
                            cssClass : 'btn-warning',
                            action   : function(dialog) {
                                var url = id_change_reason_url.find("#change_reason_url").val();

                                $.do_ajax("/user_deal/change_teacher_require_deal", {
                                    "userid":userid,
                                    "teacherid":list.teacherid,
                                    "subject":list.subject,
                                    "grade":list.grade,
                                    "textbook":id_textbook.val(),
                                    "stu_score_info": id_stu_score_info.val(),
                                    "stu_character_info": id_stu_character_info.val(),
                                    "phone_location": id_phone_location.val(),
                                    "change_reason": id_change_reason.val(),
                                    "change_teacher_reason_type": id_change_teacher_reason_type.val(),
                                    "change_reason_url": id_change_reason_url.find("#change_reason_url").val(),
                                    "except_teacher": id_except_teacher.val(),
                                    "commend_type" :1
                                });
                            }
                        },function(){
                            $.custom_upload_file('id_upload_change_reason',true,function (up, info, file) {
                                console.log(info);
                                var res = $.parseJSON(info);

                                $("#change_reason_url").val(res.key);
                            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

                        });


                    });
                }else{
                    alert( "请选择课程包" );
                }
            },
            "onLoadData" : null
        });


    });


    $(".opt-type-change-list").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var userid = opt_data.userid;
        var title = "学生类型修改记录";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>操作时间</td><td>修改前类型</td><td>修改后类型</td><td>理由</td><td>时长</td><td>预计复课时间</td><td>微信提醒时间</td><td>操作人</td><td>是否手动修改</td></tr></table></div>");                     

        $.do_ajax("/user_deal/get_student_type_change_list",{
            "userid" : userid
        },function(result){
            if(result.ret!=0){
                BootstrapDialog.alert(result.info);
                return ;
            }

            $.each(result.data,function(i,item){
                html_node.find("table").append("<tr><td>"+item['add_time_str']+"</td><td>"+item['type_before_str']+"</td><td>"+item['type_cur_str']+"</td><td>"+item['reason']+"</td><td>"+item['stop_duration']+"</td><td>"+item['recover_time_str']+"</td><td>"+item['wx_remind_time_str']+"</td><td>"+item['account']+"</td><td>"+item['change_type_str']+"</td></tr>");
                

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

    $(".opt-require-commend-teacher").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var id_except_teacher = $("<textarea />") ;
        var id_textbook = $("<input />") ;
        var id_stu_request_test_lesson_demand = $("<textarea />") ;
        var id_stu_score_info = $("<input />") ;
        var id_stu_character_info = $("<input />") ;
        var id_stu_request_test_lesson_time = $("<input />") ;
        var id_subject = $("<select />");
        id_stu_request_test_lesson_time.datetimepicker({
            datepicker:true,
            timepicker:false,
            format:'Y-m-d ',
            step:30,
            onChangeDateTime :function(){

            }
        });

        Enum_map.append_option_list("subject", id_subject,true);
        var arr=[
            ["科目",id_subject],
            ["学生成绩", id_stu_score_info],
            ["学生性格",id_stu_character_info],
            ["教材版本",id_textbook],
            ["试听需求",id_stu_request_test_lesson_demand],
            ["试听时间",id_stu_request_test_lesson_time],
            ["备注(特殊要求)",id_except_teacher],
        ];
        

        $.show_key_value_table("申请推荐老师", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                var timestamp2 =  $.strtotime(id_stu_request_test_lesson_time.val());
                console.log(id_subject.val());
                console.log(opt_data.location);
                $.do_ajax("/user_deal/add_seller_require_commend_teacher", {
                    "except_teacher"             : id_except_teacher.val(),
                    "subject"                    : id_subject.val(),
                    "grade"                      : opt_data.grade,
                    "textbook"                   : id_textbook.val(),
                    "stu_request_test_lesson_demand" :  id_stu_request_test_lesson_demand.val(),
                    "stu_request_test_lesson_time"   : timestamp2,
                    "stu_request_lesson_time_info"   : "无" ,
                    "phone_location"                 : opt_data.location ,
                    "stu_score_info"                 : id_stu_score_info.val(),
                    "stu_character_info"             : id_stu_character_info.val(),
                    "userid"                         : opt_data.userid,
                    "commend_type"                   : 2
                },function(res){
                    if(res.ret==-1){
                        BootstrapDialog.alert(res.info);
                    }else if(res.ret==1){
                        BootstrapDialog.alert(res.info,function(){
                            window.location.reload();
                        });

                    }
                });
            }
        });

    });


});
