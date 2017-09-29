/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-ass_random_revisit.d.ts" />

$(function(){

    function load_data(){
        $.reload_self_page ( {
            "grade"        : $("#id_grade").val(),
        });
    }

    Enum_map.td_show_desc("grade", $(".td-grade"));
    Enum_map.td_show_desc("relation_ship", $(".td-parent-type"));

    //init  input data
    $("#id_grade").val(g_args.grade);
    $.enum_multi_select( $("#id_grade") ,"grade", function( ){ load_data()}  );

    //点击进入个人主页
    $('.opt-user').on('click',function(){
        var userid=$(this).parent().data("userid");
        var nick=$(this).parent().data("stu_nick");
        $.wopen('/stu_manage?sid='+userid );
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
        Enum_map.append_option_list("revisit_type",id_return_record_type,true,[7]);
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


    if (window.location.pathname=="/user_manage/ass_archive_ass") {
        $("#id_assistantid").parent().parent().hide();
    }


});


/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>
*/
