/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-ass_archive.d.ts" />

function load_data(){
    $.reload_self_page ( {
    order_by_str: g_args.order_by_str,
        "assistantid"  : $("#id_assistantid").val(),
        "grade"        : $("#id_grade").val(),
        "student_type" : $("#id_student_type").val(),
        "user_name"    : $("#id_user_name").val(),
        "revisit_flag" : $('#id_revisit_flag').val(),
        "refund_warn"  : $("#id_refund_warn").val(),
    warning_stu:	$('#id_warning_stu').val()
    });
}
function add_subject_score(obj){
    $(obj).parent().parent().parent().append("<div class='subject_score'><div class='col-xs-12 col-md-1' ><div class='input-group'><span class='input-group-addon' style='height:34px;'>科目：</span><select name='subject_score_new_two' class='form-control' style='width:70px'></select> </div></div><div class='col-xs-3 col-md-1' style='margin:0 0 0 2.0%'><div class='input-group' style='width:90px;'><input type='text' class='form-control' name='subject_score_one_new_two' placeholder='' /></div></div><div class='col-xs-3 col-md-1' style='width:8px;margin:0.5% 3% 0 -0.5%;cursor: pointer;' ><i class='fa fa-plus' onclick='add_subject_score(this)' title='添加科目'></i></div><div class='col-xs-3 col-md-1' style='width:8px;margin:1% 2% 0 -1.5%;cursor: pointer;padding:0 0 0 0;' ><i class='fa fa-minus' onclick='del_subject_score(this)' title='删除科目'></i></div></div>");
    var id_subject_score = $(obj).parent().parent().parent().find("select[name='subject_score_new_two']").last();
    var id_grade = $(obj).parent().parent().parent().parent().parent().parent().parent().find('#id_stu_grade_new_two').val();
    if(id_grade==101 || id_grade==102 || id_grade==103 || id_grade==104 || id_grade==105 || id_grade==106){
        Enum_map.append_option_list("subject", id_subject_score, true,[0,1,2,3]);
    }else if(id_grade==201 || id_grade==202 || id_grade==203){
        Enum_map.append_option_list("subject", id_subject_score, true,[0,1,2,3,4,5,6,7,8,9,10]);
    }else if(id_grade==301 || id_grade==302 || id_grade==303){
        Enum_map.append_option_list("subject", id_subject_score, true,[0,1,2,3,4,5,6,7,8,9]);
    }
}
function del_subject_score(obj){
    $(obj).parent().parent().remove();
}
function add0(m){return m<10?'0'+m:m }

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
    $("#id_refund_warn").val(g_args.refund_warn);

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

        if(student_type>4){
            alert("逾期学员不可更改类型!");
            return;
        }

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
            Enum_map.append_option_list( "student_type",  id_student_type,true,[0,1,2,3,4]);
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

    // if(window.location.pathname=="/user_manage/ass_archive" || window.location.pathname=="/user_manage/ass_archive/"){
    //     $("#id_assistantid").parent().parent().parent().hide();

    // }else{
    // }




    $(".opt-edit-new_new_two").on("click",function(){
        var opt_data   = $(this).get_opt_data();
        var opt_obj    = this;
        var click_type = 2;
        edit_user_info_new_two(opt_data,opt_obj,click_type);
    });

    var edit_user_info_new_two = function(opt_data,opt_obj,click_type){
        $.do_ajax("/ss_deal/ass_get_user_info",{
            "userid" : opt_data.userid ,
        },function(ret){
            var data                = ret.data;
            var html_node           = $.dlg_need_html_by_id( "id_dlg_post_user_info_new_two");
            var show_noti_info_flag = false;
            var $note_info          = html_node.find(".note-info");
            var note_msg            = "";
            if (data.test_lesson_count >0 ) {
                show_noti_info_flag=true;
                note_msg="已有试听课:"+data.test_lesson_count +"次" ;
            }

            if (!show_noti_info_flag) {
                $note_info.hide();
            }else{
                $note_info.find("span").html( note_msg);
            }

            if( data.status !=0 ) {
                html_node.find("#id_stu_rev_info_new_two").removeClass("btn-primary");
                html_node.find("#id_stu_rev_info_new_two").addClass("btn-warning");
            }else{
                html_node.find("#id_stu_rev_info_new_two").addClass("btn-primary");
                html_node.find("#id_stu_rev_info_new_two").removeClass("btn-warning");
            }
            html_node.find("#id_stu_rev_info_new_two") .on("click",function(){
                $(opt_obj).parent().find(".opt-return-back-list").click();
            });
            var id_stu_nick          = html_node.find("#id_stu_nick_new_two");
            var id_gender            = html_node.find("#id_stu_gender_new_two");
            var id_par_nick          = html_node.find("#id_par_nick_new_two");
            var id_par_type          = html_node.find("#id_par_type_new_two");
            var id_grade             = html_node.find("#id_stu_grade_new_two");
            var id_subject           = html_node.find("#id_stu_subject_new_two");
            var id_editionid         = html_node.find("#id_stu_editionid_new_two");
            var id_has_pad           = html_node.find("#id_stu_has_pad_new_two");
            var id_school            = html_node.find("#id_stu_school_new_two");
            var id_interests_hobbies = html_node.find("#id_interests_hobbies_new_two");
            var id_character_type    = html_node.find("#id_character_type_new_two");
            var id_address           = html_node.find("#id_stu_addr_new_two");

            var id_main_subject = html_node.find("#id_main_subject_new_two");
            var id_main_subject_score_one = html_node.find("#id_main_subject_score_one_new_two");
            var id_subject_score     = html_node.find("select[name='subject_score_new_two']");

            var id_test_stress = html_node.find("#id_test_stress_new_two");
            var id_academic_goal = html_node.find("#id_academic_goal_new_two");
            var id_entrance_school_type = html_node.find("#id_entrance_school_type_new_two");
            var id_advice_flag = html_node.find("#id_advice_flag_new_two");
            var id_interest_cultivation = html_node.find("#id_interest_cultivation_new_two");
            var id_extra_improvement = html_node.find("#id_extra_improvement_new_two");
            var id_habit_remodel = html_node.find("#id_habit_remodel_new_two");
            var id_study_habit = html_node.find("#id_study_habit_new_two");
            var id_need_teacher_style = html_node.find("#id_need_teacher_style_new_two");
            var id_stu_request_test_lesson_demand= html_node.find("#id_stu_request_test_lesson_demand_new_two");
            var id_intention_level = html_node.find("#id_intention_level_new_two");
            var id_stu_request_test_lesson_time = html_node.find("#id_stu_request_test_lesson_time_new_two");
            var id_stu_request_test_lesson_time_end = html_node.find("#id_stu_request_test_lesson_time_end_new_two");
            var id_test_paper = html_node.find("#id_test_paper_new_two");

            var id_status            = html_node.find("#id_stu_status_new_two");
            var id_seller_student_sub_status = html_node.find("#id_seller_student_sub_status_new_two");
            var id_next_revisit_time = html_node.find("#id_next_revisit_time_new_two");
            var id_stu_test_ipad_flag = html_node.find("#id_stu_test_ipad_flag_new_two");
            var id_user_desc         = html_node.find("#id_stu_user_desc_new_two");
            var id_demand_urgency = html_node.find("#id_demand_urgency_new_two");
            var id_quotation_reaction = html_node.find("#id_quotation_reaction_new_two");
            var id_revisit_info_new = html_node.find("#id_revisit_info_new_two");

            var id_cultivation = html_node.find("#id_cultivation_new_two");
            var id_teacher_nature = html_node.find("#id_teacher_nature_new_two");
            var id_pro_ability = html_node.find("#id_pro_ability_new_two");
            var id_tea_status = html_node.find("#id_tea_status_new_two");
            var id_tea_age = html_node.find("#id_tea_age_new_two");
            var id_tea_gender = html_node.find("#id_tea_gender_new_two");
            var id_class_env = html_node.find("#id_class_env_new_two");
            var id_courseware = html_node.find("#id_courseware_new_two");
            var id_teacher_type = html_node.find("#id_teacher_type_new_two");
            var id_add_tag = html_node.find("#id_add_tag_new_two");
            var id_ass_test_lesson_type = html_node.find("#id_ass_test_lesson_type_new_two");//分类
            var id_change_teacher_reason_type = html_node.find("#id_change_teacher_reason_type_new_two");//换老师类型
            var id_change_reason_url = html_node.find("#id_change_reason_url_new_two");//换老师原因图片
            var id_change_reason = html_node.find("#id_change_reason_new_two");//换老师原因
            var id_green_channel_teacherid = html_node.find("#id_green_channel_teacherid_new_two");//绿色通道
            var id_learning_situation = html_node.find("#id_learning_situation_new_two");//学情反馈

            var wuyaoqiu_html = "<option value='0'>无要求</option>";
            html_node.find(".upload_test_paper").attr("id","id_upload_test_paper");
            html_node.find("#id_stu_reset_next_revisit_time_new_two").on("click",function(){
                id_next_revisit_time.val("");
            });
            Enum_map.append_option_list("grade", id_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
            Enum_map.append_option_list("pad_type", id_has_pad, true);
            Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
            Enum_map.append_option_list("boolean", id_advice_flag, true);
            Enum_map.append_option_list("academic_goal", id_academic_goal, true);
            Enum_map.append_option_list("test_stress", id_test_stress, true,[1,2,3]);
            id_test_stress.append(wuyaoqiu_html);
            Enum_map.append_option_list("habit_remodel", id_habit_remodel, true);
            Enum_map.append_option_list("extra_improvement", id_extra_improvement, true);
            Enum_map.append_option_list("entrance_school_type", id_entrance_school_type, true,[1,2,3,4,5,6,7]);
            id_entrance_school_type.append(wuyaoqiu_html);
            Enum_map.append_option_list("interest_cultivation", id_interest_cultivation, true);
            Enum_map.append_option_list("intention_level", id_intention_level, true);
            Enum_map.append_option_list("demand_urgency", id_demand_urgency, true);
            Enum_map.append_option_list("quotation_reaction", id_quotation_reaction, true);
            Enum_map.append_option_list("identity", id_tea_status, true,[5,6,7,8]);
            id_tea_status.append(wuyaoqiu_html);
            Enum_map.append_option_list("gender", id_tea_gender, true,[1,2]);
            id_tea_gender.append(wuyaoqiu_html);
            Enum_map.append_option_list("tea_age", id_tea_age, true,[1,2,3,4]);
            id_tea_age.append(wuyaoqiu_html);
            Enum_map.append_option_list("teacher_type", id_teacher_type, true,[1,3]);
            id_teacher_type.append(wuyaoqiu_html);
            id_stu_request_test_lesson_time.datetimepicker({
                lang             : 'ch',
                timepicker       : true,
                format:'Y-m-d H:i',
                step             : 30,
                onGenerate       : function(){
                    check_disable_time();
                }
            });
            id_stu_request_test_lesson_time_end.datetimepicker({
                lang             : 'ch',
                timepicker       : true,
                format:'Y-m-d H:i',
                step             : 30,
                onGenerate       : function(){
                    check_disable_time();
                }
            });
            //检测该时间该人是否排课
            var check_disable_time = function() {
                var cur_time = id_stu_request_test_lesson_time.val();
                var cur_time_end = id_stu_request_test_lesson_time_end.val();
                var cur_day = new Date(cur_time).getTime() / 1000;
                $.do_ajax("/seller_student_new/get_stu_request_test_lesson_time_by_adminid",{
                    "cur_day" : cur_day
                },function(res){
                    var ret = res.list;
                    $(ret).each(function(i){
                        var dis_time = ret[i];
                        console.log(dis_time)
                        $('.xdsoft_time').each(function(){
                            var add_attr = function(obj){
                                $(obj).css('border','1px solid red');
                                $(obj).css('background-color','#ccc');
                                $(obj).on('click',function(){
                                    BootstrapDialog.alert('你已经在该时间段内排过一节课!');
                                    return false;
                                });
                            };

                            if ( $(this).text() == dis_time ) {
                                var that = $(this);
                                var prev_that = $(this).prev();
                                var next_that = $(this).next();
                                add_attr(prev_that);
                                add_attr(that);
                                add_attr(next_that);
                            }
                        });
                    });
                });
            };
            html_node.find("#id_stu_reset_stu_request_test_lesson_time_new_two").on("click",function(){
                id_stu_request_test_lesson_time.val("");
                id_stu_request_test_lesson_time_end.val("");
            });
            id_study_habit.data("v",data.study_habit);
            id_study_habit.on("click",function(){
                var study_habit  = id_study_habit.data("v");
                $.do_ajax("/ss_deal2/get_stu_study_habit_list",{
                    "study_habit" : study_habit
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["study_habit"]  ]);

                        if (this["has_study_habit"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","学习习惯" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_study_habit_name",{
                                "study_habit" : JSON.stringify(select_list)
                            },function(res){
                                id_study_habit.val(res.data);
                                id_study_habit.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_interests_hobbies.data("v",data.interests_and_hobbies);
            id_interests_hobbies.on("click",function(){
                var interests_hobbies  = id_interests_hobbies.data("v");
                $.do_ajax("/ss_deal2/get_stu_interests_hobbies_list",{
                    "interests_hobbies" : interests_hobbies
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["interests_hobbies"]  ]);

                        if (this["has_interests_hobbies"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","兴趣爱好" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_interests_hobbies_name",{
                                "interests_hobbies" : JSON.stringify(select_list)
                            },function(res){
                                id_interests_hobbies.val(res.data);
                                id_interests_hobbies.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_cultivation.on("click",function(){
                var interests_hobbies  = id_interests_hobbies.data("v");
                $.do_ajax("/ss_deal2/get_stu_cultivation_list",{
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["tag_id"], this["tag_name"]]);
                        if (this["has_tag_name"]) {
                            select_list.push (this["tag_id"]) ;
                        }
                    });
                    $(this).admin_select_dlg({
                        header_list     : [ "id","素质培养" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {
                            $.do_ajax("/ss_deal2/get_stu_cultivation_name",{
                                "cultivation" : JSON.stringify(select_list)
                            },function(res){
                                id_cultivation.val(res.data);
                                id_cultivation.data("v",res.data);
                            });
                            dlg.close();
                        }
                    });
                });
            });

            id_teacher_nature.on("click",function(){
                var teacher_nature  = id_teacher_nature.data("v");
                $.do_ajax("/ss_deal2/get_teacher_nature_list",{
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["tag_id"], this["tag_name"]]);
                        if (this["has_tag_name"]) {
                            select_list.push (this["tag_id"]) ;
                        }
                    });
                    $(this).admin_select_dlg({
                        header_list     : [ "id","风格性格" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {
                            $.do_ajax("/ss_deal2/get_teacher_nature_name",{
                                "teacher_nature" : JSON.stringify(select_list)
                            },function(res){
                                id_teacher_nature.val(res.data);
                                id_teacher_nature.data("v",res.data);
                            });
                            dlg.close();
                        }
                    });
                });
            });

            id_pro_ability.on("click",function(){
                var pro_ability  = id_pro_ability.data("v");
                $.do_ajax("/ss_deal2/get_pro_ability_list",{
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["tag_id"], this["tag_name"]]);
                        if (this["has_tag_name"]) {
                            select_list.push (this["tag_id"]) ;
                        }
                    });
                    $(this).admin_select_dlg({
                        header_list     : [ "id","专业能力" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {
                            $.do_ajax("/ss_deal2/get_pro_ability_name",{
                                "pro_ability" : JSON.stringify(select_list)
                            },function(res){
                                id_pro_ability.val(res.data);
                                id_pro_ability.data("v",res.data);
                            });
                            dlg.close();
                        }
                    });
                });
            });

            id_class_env.on("click",function(){
                var class_env  = id_class_env.data("v");
                $.do_ajax("/ss_deal2/get_class_env_list",{
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["tag_id"], this["tag_name"]]);
                        if (this["has_tag_name"]) {
                            select_list.push (this["tag_id"]) ;
                        }
                    });
                    $(this).admin_select_dlg({
                        header_list     : [ "id","课堂气氛" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {
                            $.do_ajax("/ss_deal2/get_class_env_name",{
                                "class_env" : JSON.stringify(select_list)
                            },function(res){
                                id_class_env.val(res.data);
                                id_class_env.data("v",res.data);
                            });
                            dlg.close();
                        }
                    });
                });
            });
            id_courseware.on("click",function(){
                var courseware  = id_courseware.data("v");
                $.do_ajax("/ss_deal2/get_courseware_list",{
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["tag_id"], this["tag_name"]]);
                        if (this["has_tag_name"]) {
                            select_list.push (this["tag_id"]) ;
                        }
                    });
                    $(this).admin_select_dlg({
                        header_list     : [ "id","课件要求" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {
                            $.do_ajax("/ss_deal2/get_courseware_name",{
                                "courseware" : JSON.stringify(select_list)
                            },function(res){
                                id_courseware.val(res.data);
                                id_courseware.data("v",res.data);
                            });
                            dlg.close();
                        }
                    });
                });
            });

            id_character_type.data("v",data.character_type);
            id_character_type.on("click",function(){
                var character_type  = id_character_type.data("v");
                $.do_ajax("/ss_deal2/get_stu_character_type_list",{
                    "character_type" : character_type
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["character_type"]  ]);

                        if (this["has_character_type"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","性格特点" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_character_type_name",{
                                "character_type" : JSON.stringify(select_list)
                            },function(res){
                                id_character_type.val(res.data);
                                id_character_type.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_need_teacher_style.data("v",data.need_teacher_style);
            id_need_teacher_style.on("click",function(){
                var need_teacher_style  = id_need_teacher_style.data("v");
                $.do_ajax("/ss_deal2/get_stu_need_teacher_style_list",{
                    "need_teacher_style" : need_teacher_style
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["need_teacher_style"]  ]);

                        if (this["has_need_teacher_style"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","老师要求" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_need_teacher_style_name",{
                                "need_teacher_style" : JSON.stringify(select_list)
                            },function(res){
                                id_need_teacher_style.val(res.data);
                                id_need_teacher_style.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });
                });
            });


            var old_province = data.region;
            if(old_province == ''){
                old_province="选择省（市）";
            }
            var old_city = data.city;
            if(old_city == ''){
                old_city="选择市（区）";
            }
            var old_area = data.area;
            if(old_area == ''){
                old_area="选择区（县）";
            }
            var province = html_node.find("#province_new_two");
            var city = html_node.find("#city_new_two");
            var area = html_node.find("#area_new_two");
            var preProvince = "<option value=\"\">"+old_province+"</option>";
            var preCity = "<option value=\"\">"+old_city+"</option>";
            var preArea = "<option value=\"\">"+old_area+"</option>";
            //初始化
            province.html(preProvince);
            city.html(preCity);
            area.html(preArea);

            //文档加载完毕:即从province_city_select_Info.xml获取数据,成功之后采用
            //func_suc_getXmlProvice进行 省的 解析
            $.ajax({
                type : "GET",
                url : "/province_city_select_Info.xml",
                success : func_suc_getXmlProvice
            });
            //省 下拉选择发生变化触发的事件
            province.change(function() {
                //province.val()  : 返回是每个省对应的下标,序号从0开始
                if (province.val() != "") {
                    if(data.region != html_node.find("#province_new_two").find("option:selected").text()){
                        var preCity = "<option value=\"\">选择市（区）</option>";
                        var preArea = "<option value=\"\">选择区（县）</option>";
                    }
                    city.html(preCity);
                    area.html(preArea);

                    //根据下拉得到的省对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                    //func_suc_getXmlProvice进行省对应的市的解析
                    $.ajax({
                        type : "GET",
                        url : "/province_city_select_Info.xml",
                        success : func_suc_getXmlCity
                    });

                }
            });

            //市 下拉选择发生变化触发的事件
            city.change(function() {
                if(data.city != html_node.find("#city_new_two").find("option:selected").text()){
                    var preArea = "<option value=\"\">选择区（县）</option>";
                }
                area.html(preArea);
                $.ajax({
                    type : "GET",
                    url : "/province_city_select_Info.xml",

                    //根据下拉得到的省、市对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                    //func_suc_getXmlArea进行省对应的市对于的区的解析
                    success : func_suc_getXmlArea
                });
            });

            //区 下拉选择发生变化触发的事件
            area.change(function() {
                var value = province.find("option:selected").text()
                    + city.find("option:selected").text()
                    + area.find("option:selected").text();
                id_address.val(value);
                $("#txtProCity").val(value);
            });

            //解析获取xml格式文件中的prov标签,得到所有的省,并逐个进行遍历 放进下拉框中
            function func_suc_getXmlProvice(xml) {
                //jquery的查找功能
                var sheng = $(xml).find("prov");

                //jquery的遍历与查询匹配 eq功能,并将其放到下拉框中
                sheng.each(function(i) {
                    province.append("<option value=" + i + ">"
                                    + sheng.eq(i).attr("text") + "</option>");
                });
            }

            function func_suc_getXmlCity(xml) {
                var xml_sheng = $(xml).find("prov");
                var pro_num = parseInt(province.val());
                var xml_shi = xml_sheng.eq(pro_num).find("city");
                xml_shi.each(function(j) {
                    city.append("<option  value=" + j + ">"
                                + xml_shi.eq(j).attr("text") + "</option>");
                });
            }

            function func_suc_getXmlArea(xml) {
                var xml_sheng = $(xml).find("prov");
                var pro_num = parseInt(province.val());
                var xml_shi = xml_sheng.eq(pro_num).find("city");
                var city_num = parseInt(city.val());
                var xml_xianqu = xml_shi.eq(city_num).find("county");
                xml_xianqu.each(function(k) {
                    area.append("<option  value=" + k + ">"
                                + xml_xianqu.eq(k).attr("text") + "</option>");
                });
            }

            var now=(new Date()).getTime()/1000;
            var status=data.status*1;
            var show_status_list=[];
            var cur_page= g_args.cur_page;
            show_status_list=[];
            if(opt_data.stu_type==1){
                switch ( opt_data.seller_student_status) {
                case 0:
                case  2:
                    show_status_list=[ 1,2, 100,101,102,103 ];
                    break;
                case 1:
                    show_status_list=[  100, 101,102,103 ];
                    break;
                case  60:
                    show_status_list=[ 1,2,61, 100,101,102,103 ];
                    break;
                case 61:
                    show_status_list=[1,2,60,  100, 101,102,103 ];
                    break;

                case 101:
                case  102:
                    show_status_list=[ 100, 101,102,103, 1  ];
                    break;

                case 100:case 103:
                    show_status_list=[ 1, 100, 101,102,103 ];
                    break;

                case 110:case 120:
                    show_status_list=[ 1,100, 101,102,103 ];
                    break;

                case 200:
                    show_status_list=[ ];
                    break;

                case 210:
                    show_status_list=[ 220, 290  ];
                    break;
                case 220: //待开课
                    show_status_list=[ 290,300,301,302 ];
                    break;

                case 290: //待跟进
                    show_status_list=[ 300,301,302 ];
                    break;

                case 300:case 301:case 302:   //未签
                    show_status_list=[ 300,301,302,420  ];
                    break;
                case 400:case 410:case 420: //
                    show_status_list=[  60,61 ];
                    break;
                }
            }else{
                switch ( opt_data.seller_student_status) {
                case 0:
                case  2:
                    show_status_list=[ 1,2, 100,101,102,103 ];
                    break;
                case 1:
                    show_status_list=[  100, 101,102,103 ];
                    break;
                case 101:
                case  102:
                    show_status_list=[ 100, 101,102,103, 1  ];
                    break;

                case 100:case 103:
                    show_status_list=[ 1, 100, 101,102,103 ];
                    break;

                case 110:case 120:
                    show_status_list=[ 1,100, 101,102,103 ];
                    break;

                case 200:
                    show_status_list=[ ];
                    break;

                case 210:
                    show_status_list=[ 220, 290  ];
                    break;
                case 220: //待开课
                    show_status_list=[ 290,300,301,302 ];
                    break;

                case 290: //待跟进
                    show_status_list=[ 300,301,302 ];
                    break;

                case 300:case 301:case 302:case 420 :   //未签
                    show_status_list=[ 300,301,302,420  ];
                    break;
                case 400:case 410:case 420: //
                    show_status_list=[   ];
                    break;
                }
            }

            show_status_list.push(status);

            Enum_map.append_option_list("seller_student_status", id_status ,true , show_status_list );
            Enum_map.append_option_list("gender", id_gender, true,[0,1,2]);
            Enum_map.append_option_list("parent_type", id_par_type, true);
            Enum_map.append_option_list("region_version", id_editionid, true);

            id_stu_nick.val(data.stu_nick);
            id_par_nick.val(data.par_nick);
            if(data.par_type>0){
                id_par_type.val(data.par_type);
            }else{
                id_par_type.val(1);
            }
            id_grade.val(data.grade);
            if(id_grade.val()==101 || id_grade.val()==102 || id_grade.val()==103 || id_grade.val()==104 || id_grade.val()==105 || id_grade.val()==106){
                Enum_map.append_option_list("subject", id_subject, true,[0,1,2,3]);
                Enum_map.append_option_list("subject", id_subject_score, true,[0,1,2,3]);
            }else if(id_grade.val()==201 || id_grade.val()==202 || id_grade.val()==203){
                Enum_map.append_option_list("subject", id_subject, true,[0,1,2,3,4,5,6,7,8,9,10]);
                Enum_map.append_option_list("subject", id_subject_score, true,[0,1,2,3,4,5,6,7,8,9,10]);
            }else if(id_grade.val()==301 || id_grade.val()==302 || id_grade.val()==303){
                // Enum_map.append_option_list("subject", id_subject, true,[0,1,2,3,4,5,6,7,8,9]);
                Enum_map.append_option_list("subject", id_subject_score, true,[0,1,2,3,4,5,6,7,8,9]);
            }
            id_gender.val(data.gender);
            id_address.val(data.address);
            id_subject.val(data.subject);
            id_main_subject.val(data.subject);
            $.each(data.subject_score.split(','),function(index,value){
                if(value !== ''){
                    var arr = value.split(':');
                    if(arr[0] == id_subject.find("option:selected").text()){
                        html_node.find("#id_main_subject_score_one_new_two").val(arr[1]);
                    }else{
                        html_node.find("#id_main_subject_score_one_new_two").parent().parent().parent().append("<div class='subject_score'><div class='col-xs-12 col-md-1' ><div class='input-group'><span class='input-group-addon' style='height:34px;'>科目：</span><select name='subject_score_new_two' id='subject_score_"+index+"' class='form-control' style='width:70px'><option>"+arr[0]+"</option></select> </div></div><div class='col-xs-3 col-md-1' style='margin:0 0 0 2.0%'><div class='input-group' style='width:90px;'><input type='text' class='form-control' value='"+arr[1]+"' name='subject_score_one_new_two' placeholder='' /></div></div><div class='col-xs-3 col-md-1' style='width:8px;margin:0.5% 3% 0 -0.5%;cursor: pointer;' ><i class='fa fa-plus' onclick='add_subject_score(this)' title='添加科目'></i></div><div class='col-xs-3 col-md-1' style='width:8px;margin:1% 2% 0 -1.5%;cursor: pointer;padding:0 0 0 0;' ><i class='fa fa-minus' onclick='del_subject_score(this)' title='删除科目'></i></div></div>");
                    }
                }
            });
            id_status.val(data.status);
            id_user_desc.val(data.user_desc);
            id_has_pad.val(data.has_pad);
            id_school.val(data.school);
            id_editionid.val(data.editionid);
            id_next_revisit_time.val(data.next_revisit_time);
            html_node.find("#id_class_rank_new_two").val(data.class_rank);
            html_node.find("#id_grade_rank_new_two").val(data.grade_rank);
            html_node.find("#id_recent_results_new_two").val(data.recent_results);
            html_node.find("#id_advice_flag_new_two").val(data.advice_flag);
            html_node.find("#id_interest_cultivation_new_two").val(data.interest_cultivation);
            html_node.find("#id_extra_improvement_new_two").val(data.extra_improvement);
            html_node.find("#id_habit_remodel_new_two").val(data.habit_remodel);
            html_node.find("#id_study_habit_new_two").val(data.study_habit);
            html_node.find("#id_need_teacher_style_new_two").val(data.need_teacher_style);
            html_node.find("#id_test_paper_new_two").val(data.stu_test_paper);
            html_node.find("#id_test_stress_new_two").val(data.test_stress);
            html_node.find("#id_academic_goal_new_two").val(data.academic_goal);
            html_node.find("#id_entrance_school_type_new_two").val(data.entrance_school_type);
            html_node.find("#id_interests_hobbies_new_two").val(data.interests_and_hobbies);
            html_node.find("#id_character_type_new_two").val(data.character_type);
            html_node.find("#id_intention_level_new_two").val(data.intention_level);
            html_node.find("#id_demand_urgency_new_two").val(data.demand_urgency);
            html_node.find("#id_quotation_reaction_new_two").val(data.quotation_reaction);
            id_tea_status.val(data.tea_identity);
            id_tea_age.val(data.tea_age);
            id_tea_gender.val(data.tea_gender);
            id_teacher_type.val(data.teacher_type);
            if(!data.knowledge_point_location ){
                html_node.find("#id_knowledge_point_location").val(data.stu_request_test_lesson_demand);
            }else{
                html_node.find("#id_knowledge_point_location").val(data.knowledge_point_location);
            }
            var subject_tag_arr = [];
            $.each(data.subject_tag,function(index,value){
                if(index == '学科化标签'){
                    $.each(value.split(','),function(index_v,value_v){
                        if(value_v !== ''){
                            subject_tag_arr.push(value_v);
                        }
                    });
                }
            });
            if(id_grade.val()>0 && id_subject.val()>0){
                $.do_ajax("/product_tag/get_all_tag", {
                },function(resp){
                    var data=resp.data;
                    $.each(data,function(i,item){
                        if(item['tag_l1_sort'] == '学科化内容标签' && item['tag_l2_sort'] == id_grade.find("option:selected").text() && item['tag_l3_sort'] == id_subject.find("option:selected").text()){
                            var checked = '';
                            $.each(subject_tag_arr,function(index,value){
                                if(value == item['tag_name']){
                                    checked = "checked='checked'";
                                }
                            });
                            id_add_tag.parent().append("<span class='sub_tag_name'>"+item['tag_name']+"</span><input name='subject_tag' type='checkbox' value='"+item['tag_name']+"' "+checked+" style='margin:0 10px 0 0' />");
                        }
                    });
                });
            }

            id_grade.change(function(){
                $.do_ajax("/product_tag/get_all_tag", {
                },function(resp){
                    $("select[name='subject_score_new_two']").each(function(){
                        $(this).find("option").remove();
                        if(id_grade.val()==101 || id_grade.val()==102 || id_grade.val()==103 || id_grade.val()==104 || id_grade.val()==105 || id_grade.val()==106){
                            Enum_map.append_option_list("subject", $(this), true,[0,1,2,3]);
                        }else if(id_grade.val()==201 || id_grade.val()==202 || id_grade.val()==203){
                            Enum_map.append_option_list("subject", $(this), true,[0,1,2,3,4,5,6,7,8,9,10]);
                        }else if(id_grade.val()==301 || id_grade.val()==302 || id_grade.val()==303){
                            Enum_map.append_option_list("subject", $(this), true,[0,1,2,3,4,5,6,7,8,9]);
                        }
                    });
                    id_add_tag.parent().children("span[class='sub_tag_name']").remove();
                    id_add_tag.parent().children('input[type=checkbox]').remove();
                    var data=resp.data;
                    $.each(data,function(i,item){
                        if(item['tag_l1_sort'] == '学科化内容标签' && item['tag_l2_sort'] == id_grade.find("option:selected").text() && item['tag_l3_sort'] == id_subject.find("option:selected").text()){
                            var checked = '';
                            $.each(subject_tag_arr,function(index,value){
                                if(value == item['tag_name']){
                                    checked = "checked='checked'";
                                }
                            });
                            id_add_tag.parent().append("<span class='sub_tag_name'>"+item['tag_name']+"</span><input name='subject_tag' type='checkbox' value='"+item['tag_name']+"' "+checked+" />");
                        }
                    })
                        })
            });
            id_subject.change(function(){
                $.do_ajax("/product_tag/get_all_tag", {
                },function(resp){
                    id_add_tag.parent().children("span[class='sub_tag_name']").remove();
                    id_add_tag.parent().children('input[type=checkbox]').remove();
                    var data=resp.data;
                    $.each(data,function(i,item){
                        if(item['tag_l1_sort'] == '学科化内容标签' && item['tag_l2_sort'] == id_grade.find("option:selected").text() && item['tag_l3_sort'] == id_subject.find("option:selected").text()){
                            var checked = '';
                            $.each(subject_tag_arr,function(index,value){
                                if(value == item['tag_name']){
                                    checked = "checked='checked'";
                                }
                            });
                            id_add_tag.parent().append("<span class='sub_tag_name'>"+item['tag_name']+"</span></button><input name='subject_tag' type='checkbox' value='"+item['tag_name']+"' "+checked+" />");
                        }
                    })
                        });
                id_main_subject.val(id_subject.val());
                id_main_subject_score_one.val('');
            });
            $.each(data.subject_tag,function(index,value){
                if(value == ''){
                    value = '无要求';
                }
                if(index == '素质培养'){
                    id_cultivation.val(value);
                }else if(index == '风格性格'){
                    id_teacher_nature.val(value);
                }else if(index == '专业能力'){
                    id_pro_ability.val(value);
                }else if(index == '课堂气氛'){
                    id_class_env.val(value);
                }else if(index == '课件要求'){
                    id_courseware.val(value);
                }
            });
            var reset_seller_student_status_options=function()  {
                var opt_list=[0];
                var desc_map=g_enum_map["seller_student_sub_status"]["desc_map"];
                var seller_student_status=  parseInt( id_status.val());
                $.each(desc_map, function(k,v){
                    if(k>0 ) {
                        if (  Math.floor(k/1000) == seller_student_status ){
                            opt_list.push(parseInt(k));
                        }
                    }
                });
                id_seller_student_sub_status.html("");
                Enum_map.append_option_list("seller_student_sub_status", id_seller_student_sub_status,true, opt_list );
            };

            reset_seller_student_status_options();
            id_seller_student_sub_status.val(data.seller_student_sub_status);
            id_status.on("change",function(){
                reset_seller_student_status_options();
            });

            if(data.stu_request_test_lesson_time == '无' || data.stu_request_test_lesson_time == '' || data.stu_request_test_lesson_time == undefined){
                var myDate = Date.parse((new Date()).toString() )+3600*24*1000;
                var time = new Date(myDate);
                var year = time.getFullYear();
                var month = time.getMonth()+1;
                var date = time.getDate();
                var hours = 10;
                var minutes = 0;
                var start_date = year+'-'+add0(month)+'-'+add0(date)+' '+add0(hours)+':'+add0(minutes);
                id_stu_request_test_lesson_time.val(start_date);
            }else{
                id_stu_request_test_lesson_time.val(data.stu_request_test_lesson_time);
            }
            if(data.stu_request_test_lesson_time_end == '无' || data.stu_request_test_lesson_time == undefined){
                var start_time = Date.parse((new Date(id_stu_request_test_lesson_time.val())).toString() )+3600*2*1000;
                var time = new Date(start_time);
                var year = time.getFullYear();
                var month = time.getMonth()+1;
                var date = time.getDate();
                var hours = time.getHours();
                var minutes = time.getMinutes();
                var start_date = year+'-'+add0(month)+'-'+add0(date)+' '+add0(hours)+':'+add0(minutes);
                id_stu_request_test_lesson_time_end.val(start_date);
            }else{
                id_stu_request_test_lesson_time_end.val(data.stu_request_test_lesson_time_end);
            }
            id_stu_request_test_lesson_demand.val(data.stu_request_test_lesson_demand );
            id_stu_test_ipad_flag.val(data.stu_test_ipad_flag);

            id_next_revisit_time.datetimepicker( {
                lang:'ch',
                timepicker:true,
                format: "Y-m-d H:i",
                onChangeDateTime :function(){
                }
            });
            id_stu_request_test_lesson_time.change(function(){
                var start_time = Date.parse(
                    ( new Date(id_stu_request_test_lesson_time.val()).toString()
                    ))+3600*2*1000;
                var time = new Date(start_time);
                var year = time.getFullYear();
                var month = time.getMonth()+1;
                var date = time.getDate();
                var hours = time.getHours();
                var minutes = time.getMinutes();
                var start_date = year+'-'+add0(month)+'-'+add0(date)+' '+add0(hours)+':'+add0(minutes);
                id_stu_request_test_lesson_time_end.val(start_date);
            });
            var origin=data.origin;
            if (  /bm_/.test(origin) ||
                  /bw_/.test(origin) ||
                  /baidu/.test(origin)
               ) {
                origin="百度";
            }

            var title= '用户信息['+opt_data.phone+':'+opt_data.phone_location+']';
            if(click_type == 1){
                if(html_node.find("#id_stu_editionid").val() == 0){
                    html_node.find("#id_stu_editionid").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_request_test_lesson_time").val() == 0){
                    html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_subject").val() <= 0){
                    html_node.find("#id_stu_subject").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_request_test_lesson_time").val() == '无'){
                    html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }else{
                    var require_time= $.strtotime(html_node.find("#id_stu_request_test_lesson_time").val());
                    var require_time_end= $.strtotime(html_node.find("#id_stu_request_test_lesson_time_end").val());
                    var need_start_time=0;
                    var now=(new Date()).getTime()/1000;
                    var min_date_time="";
                    var nowDayOfWeek = (new Date()).getDay();
                    if ( (new Date()).getHours() <18 ) {
                        min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 08:00:00"  );
                    }else{
                        if( nowDayOfWeek==5 ||  nowDayOfWeek==6){
                            min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 16:00:00"  );
                        }else{
                            min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 14:00:00"  );
                        }
                    }
                    need_start_time=$.strtotime(min_date_time );
                    if (require_time < need_start_time ) {
                        html_node.find("#id_stu_request_test_lesson_time_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                    }
                }
            }
            Enum_map.append_option_list("ass_test_lesson_type",id_ass_test_lesson_type,true);
            id_ass_test_lesson_type.on("change",function(){
                if(id_ass_test_lesson_type.val() == 2){
                    id_change_teacher_reason_type.parent().parent().css('display','table-row');
                    id_change_reason.parent().parent().css('display','table-row');
                    id_change_reason_url.parent().parent().css('display','table-row');
                }else{
                    id_change_teacher_reason_type.parent().parent().css('display','none');
                    id_change_reason.parent().parent().css('display','none');
                    id_change_reason_url.parent().parent().css('display','none');

                    id_change_teacher_reason_type.val(0);
                    id_change_reason.val('');
                    id_change_reason_url.val('');
                }
            });
            html_node.find(".upload_change_reason_url").attr("id","id_upload_change_reason_url");
            Enum_map.append_option_list("change_teacher_reason_type",id_change_teacher_reason_type,true);
            $.admin_select_user(id_green_channel_teacherid,"teacher");

            var dlg=BootstrapDialog.show({
                title:  title,
                size: "size-wide",
                message : html_node,
                closable: false,
                buttons: [{
                    label: '返回',
                    action: function(dialog) {
                        dialog.close();
                    }
                },{
                    label: '提交',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        if (  id_seller_student_sub_status.find("option").length>1  && id_seller_student_sub_status.val()=="0" ) {
                            alert("请选择回访状态的子分类");
                            return;
                        }

                        var region = html_node.find("#province_new_two").find("option:selected").text();
                        var province = html_node.find("#province_new_two").val();
                        var city = html_node.find("#city_new_two").find("option:selected").text();
                        var area = html_node.find("#area_new_two").find("option:selected").text();
                        if(province==""){
                            region="";
                            city="";
                            area="";
                        }
                        if(html_node.find("#city_new_two").val()==""){
                             city="";
                        }
                        if(html_node.find("#area_new_two").val()==""){
                            area="";
                        }
                        var subject_str = '';
                        $(".subject_score ").each(function(){
                            var subject_score = $(this).children("div").children("div").children("select[name='subject_score_new_two']").find("option:selected").text();
                            var subject_score_one = $(this).children("div").children("div").children("input[name='subject_score_one_new_two']").val();
                            if(subject_score == ''){
                            }else{
                                subject_str += subject_score+':'+subject_score_one+',';
                            }
                        });
                        var add_tag = '';
                        $("[name = subject_tag]:checkbox").each(function(){
                            if($(this).is(":checked")){
                                add_tag += $(this).attr('value')+',';
                            }
                        });
                        if(html_node.find("#id_stu_nick_new_two").val() == ''){
                            html_node.find("#id_stu_nick_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_nick_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_gender_new_two").val() == 0){
                            html_node.find("#id_stu_gender_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_gender_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_par_nick_new_two").val() == 0){
                            html_node.find("#id_par_nick_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_par_nick_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_par_type_new_two").val() == 0){
                            html_node.find("#id_par_type_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_par_type_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_grade_new_two").val() <= 0){
                            html_node.find("#id_stu_grade_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_grade_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_subject_new_two").val() <= 0){
                            html_node.find("#id_stu_subject_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_subject_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_editionid_new_two").val() <= 0){
                            html_node.find("#id_stu_editionid_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_editionid_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_has_pad_new_two").val() < 0){
                            html_node.find("#id_stu_has_pad_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_has_pad_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#province_new_two").text() == ''){
                            html_node.find("#province_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#province_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#city_new_two").text() == ''){
                            html_node.find("#city_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#city_new_two").parent().attr('style','');
                        }
                        var r = /^\+?[1-9][0-9]*$/;　　//判断是否为正整数
                        if(html_node.find("#id_main_subject_score_one_new_two").val() == ''){
                            html_node.find("#id_main_subject_score_one_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                        }else{
                            html_node.find("#id_main_subject_score_one_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_cultivation_new_two").val() == ''){
                            html_node.find("#id_cultivation_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_cultivation_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_request_test_lesson_demand_new_two").val() == ''){
                            html_node.find("#id_stu_request_test_lesson_demand_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_request_test_lesson_demand_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_stu_request_test_lesson_time_new_two").val() == ''){
                            html_node.find("#id_stu_request_test_lesson_time_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_stu_request_test_lesson_time_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_teacher_nature_new_two").val() == ''){
                            html_node.find("#id_teacher_nature_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_teacher_nature_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_pro_ability_new_two").val() == ''){
                            html_node.find("#id_pro_ability_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_pro_ability_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_class_env_new_two").val() == ''){
                            html_node.find("#id_class_env_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_class_env_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_courseware_new_two").val() == ''){
                            html_node.find("#id_courseware_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_courseware_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_quotation_reaction_new_two").val() <= 0){
                            html_node.find("#id_quotation_reaction_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_quotation_reaction_new_two").parent().attr('style','');
                        }
                        if(html_node.find("#id_intention_level_new_two").val() <= 0){
                            html_node.find("#id_intention_level_new_two").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            return false;
                        }else{
                            html_node.find("#id_intention_level_new_two").parent().attr('style','');
                        }
                        if((id_stu_request_test_lesson_time.val() != '' && id_stu_request_test_lesson_time.val() != '无') && (id_stu_request_test_lesson_time_end.val() != '' && id_stu_request_test_lesson_time_end.val() != '无')){
                            var min_time = Date.parse(
                                (new Date(id_stu_request_test_lesson_time.val())).toString()
                            );
                            var start_time = Date.parse(
                                (new Date(id_stu_request_test_lesson_time.val()).toString()))+3600*2*1000;
                            var time = new Date(start_time);
                            var year = time.getFullYear();
                            var month = time.getMonth()+1;
                            var date = time.getDate();
                            var hours = time.getHours();
                            var minutes = time.getMinutes();
                            var seconds = 0;
                            var end_date = year+'-'+add0(month)+'-'+add0(date)+' '+add0(hours)+':'+add0(minutes)+':'+add0(seconds);
                            var max_time = Date.parse((new Date(end_date)).toString());
                            var end_time = Date.parse((new Date(id_stu_request_test_lesson_time_end.val()+':00')).toString());
                            if(end_time<min_time){
                                alert('试听最晚时间不能小于'+id_stu_request_test_lesson_time.val());
                                return false;
                            }else if(end_time>max_time){
                                alert('试听最晚时间不能大于'+end_date);
                                return false;
                            }
                            var require_time= $.strtotime(id_stu_request_test_lesson_time.val());
                            var need_start_time=0;
                            var now=(new Date()).getTime()/1000;
                            var min_date_time="";
                            var nowDayOfWeek = (new Date()).getDay();
                            if ( (new Date()).getHours() <18 ) {
                                min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 08:00:00"  );
                            }else{
                                if( nowDayOfWeek==5 ||  nowDayOfWeek==6){
                                    min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 16:00:00"  );
                                }else{
                                    min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 14:00:00"  );
                                }
                            }
                            need_start_time=$.strtotime(min_date_time );
                            if (require_time < need_start_time ) {
                                alert("申请时间不能早于 "+ min_date_time );
                                html_node.find("#id_stu_request_test_lesson_time").attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                return false;
                            }else{
                                html_node.find("#id_stu_request_test_lesson_time").attr('style','');
                            }
                        }

                        $.do_ajax("/ss_deal2/ass_add_require_test_lesson_new",{
                            new_demand_flag                  : 1,
                            click_type                       : click_type,
                            userid                           : opt_data.userid,
                            test_lesson_subject_id           : opt_data.test_lesson_subject_id,
                            phone                            : opt_data.phone,
                            stu_nick                         : id_stu_nick.val(),
                            gender                           : id_gender.val(),
                            par_nick                         : id_par_nick.val(),
                            par_type                         : id_par_type.val(),
                            grade                            : id_grade.val(),
                            subject                          : id_subject.val(),
                            editionid                        : id_editionid.val(),
                            has_pad                          : id_has_pad.val(),
                            school                           : id_school.val(),
                            character_type                   : id_character_type.val(),
                            interests_and_hobbies            : id_interests_hobbies.val(),
                            province                         : province,
                            city                             : city,
                            area                             : area,
                            region                           : region,
                            address                          : id_address.val(),
                            class_rank                       : html_node.find("#id_class_rank_new_two").val(),
                            grade_rank                       : html_node.find("#id_grade_rank_new_two").val(),
                            subject_score                    : subject_str,
                            test_stress                      : html_node.find("#id_test_stress_new_two").val(),
                            academic_goal                    : id_academic_goal.val(),
                            entrance_school_type             : id_entrance_school_type.val(),
                            cultivation                      : id_cultivation.val(),
                            add_tag                          : add_tag,
                            teacher_nature                   : id_teacher_nature.val(),
                            pro_ability                      : id_pro_ability.val(),
                            class_env                        : id_class_env.val(),
                            courseware                       : id_courseware.val(),
                            recent_results                   : html_node.find("#id_recent_results_new_two").val(),
                            advice_flag                      : id_advice_flag.val(),
                            interest_cultivation             : id_interest_cultivation.val(),
                            extra_improvement                : id_extra_improvement.val(),
                            habit_remodel                    : id_habit_remodel.val(),
                            study_habit                      : id_study_habit.val(),
                            stu_request_test_lesson_demand   : id_stu_request_test_lesson_demand.val(),
                            stu_request_test_lesson_time     : id_stu_request_test_lesson_time.val(),
                            stu_request_test_lesson_time_end : id_stu_request_test_lesson_time_end.val(),
                            test_paper                       : id_test_paper.val(),
                            tea_identity                     : id_tea_status.val(),
                            tea_age                          : id_tea_age.val(),
                            tea_gender                       : id_tea_gender.val(),
                            teacher_type                     : id_teacher_type.val(),
                            need_teacher_style               : id_need_teacher_style.val(),
                            quotation_reaction               : id_quotation_reaction.val(),
                            intention_level                  : id_intention_level.val(),
                            demand_urgency                   : id_demand_urgency.val(),
                            seller_student_status            : id_status.val(),
                            seller_student_sub_status        : id_seller_student_sub_status.val(),
                            next_revisit_time                : id_next_revisit_time.val(),
                            stu_test_ipad_flag               : id_stu_test_ipad_flag.val(),
                            user_desc                        : id_user_desc.val(),
                            ass_test_lesson_type             : id_ass_test_lesson_type.val(),
                            change_teacher_reason_type       : id_change_teacher_reason_type.val(),
                            change_reason_url                : id_change_reason_url.val(),
                            change_reason                    : id_change_reason.val(),
                            green_channel_teacherid          : id_green_channel_teacherid.val(),
                            learning_situation               : id_learning_situation.val(),
                        });
                    }
                }]
            });

            dlg.getModalDialog().css("width","78%");
            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );
            var th = setTimeout(function(){
                $.custom_upload_file('id_upload_change_reason_url',true,function (up, info, file) {
                    var res = $.parseJSON(info);
                    console.log(res);
                    id_change_reason_url.val(res.key);
                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
                $.custom_upload_file('id_upload_test_paper', false,function (up, info, file) {
                    var res = $.parseJSON(info);
                    console.log(res);
                    id_test_paper.val(res.key);

                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
                clearTimeout(th);
            }, 1000);

        });
    };

    $(".refund_warn_reason").on("click", function() {
        var userid = $(this).parent().parent().parent().find(".userid").text();

        $.do_ajax("/user_manage_new/get_refund_warn_info", {"userid":userid},
            function(res) {
            if (res.data) {
                var data = res.data;
                var arr = [];
                
                for (var key in data) {
                    var val = data[key];
                    if ((key == "单科上课次数" && parseInt(val) < 3) || (key == "退费预警级别" && val == "一级")) {val = "<span style='color:#0099FF'>" + val + "</span>";}
                    if ((key == "上课次数(2周)" && parseInt(val) == 0) || (key == "换老师次数" && parseInt(val) == 1) ||
                        (key == "退费预警级别" && val == "二级")) {val = "<span style='color:#FFCC33'>" + val + "</span>";}
                    if ((key == "上课次数(30天)" && parseInt(val) == 0) || (key == "换老师次数" && parseInt(val) == 1) ||
                        (key == "退费预警级别" && val == "三级")) {val = "<span style='color:#FF0000'>" + val + "</span>";}
                    if (key == "学员类型" && (val == "休学学员" || val == "停课学员" || val == "寒暑假停课")) {val = "<span style='color:#FF0000'>" + val + "</span>";}
                    if (key == "退费预警级别" && val == "无") {val = "<span style='color:#0000FF'>" + val + "</span>";}
                    arr.push([key, val]);
                }
                $.show_key_value_table("退费预警级别详情", arr);

            } else {
                alert('没有数据');
            }
        });

    })

    $(".refund_return_back_new").on("click", function() { // 回访
        var userid = $(this).parent().parent().parent().find(".userid").text();
        var id_return_record_type = $("<select />");
        var id_return_record_person = $("<select />");
        var id_revisit_path = $("<select />");
        var id_return_record_record = $("<textarea />");
        var id_is_over = $("<select name='is_over'><option value=0>是</option><option value=1>否</option></select>")

        Enum_map.append_option_list("revisit_type",id_return_record_type,true,[11]);
        Enum_map.append_option_list("revisit_person",id_return_record_person,true,[0,1,2,3]);
        Enum_map.append_option_list("revisit_path",id_revisit_path,true);

        var arr = [
            ["回访类型", id_return_record_type],
            ["回访对象", id_return_record_person] ,
            ["回访路径", id_revisit_path] ,
            ["回访记录", id_return_record_record],
            ["退费预警是否解除", id_is_over]
        ];

        $.show_key_value_table("退费预警级别详情", arr, {
            label : "确定",
            cssClass : "btn-danger",
            action : function(dialog) {
                $.do_ajax("/company_wx/add_warn_revisit_record", {
                    "userid" : userid,
                    "revisit_type" : id_return_record_type.val(),
                    "revisit_person" : id_return_record_person.val(),
                    "revisit_path" : id_revisit_path.val(),
                    "operator_note" : id_return_record_record.val(),
                    "is_over" : id_is_over.val()
                });
            }

        });
    });

});
