/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-return_record.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            sid: g_args.sid,
            is_warning_flag:	$('#id_is_warning_flag').val()
        });
    }

    Enum_map.append_option_list( "is_warning_flag",$('#id_is_warning_flag') );
	$('#id_is_warning_flag').val(g_args.is_warning_flag);

    $(".opt-audio").each(function(){
        var opt_data=$(this).get_opt_data();
        if (!opt_data.record_url ) {
            $(this).hide();
            // $(this).parent().find(".opt-edit").hide();
        }
    });

    $(".opt-edit-new").on("click", function(){
        var opt_data=$(this).get_opt_data();
        console.log(opt_data);
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

        if(opt_data.revisit_person === "爸爸"){
            id_return_record_person.val(0);
        }else if(opt_data.revisit_person === "妈妈"){
            id_return_record_person.val(1);
        }else if(opt_data.revisit_person === "孩子"){
            id_return_record_person.val(2);
        }else if(opt_data.revisit_person === "其他"){
            id_return_record_person.val(3);
        }
        if(opt_data.self_intro){
            id_self_introduction = $("<div class=\"check_flag\"><input type=\"checkbox\" checked id=\"self_intro\"></div>");
        }
        if(opt_data.check_lesson){
            id_check_lesson_time    = $("<div class=\"check_flag\"><input type=\"checkbox\" checked id=\"check_lesson\"></div>");
        }
        if(opt_data.bulid_wx){
            id_bulid_wx_qun         = $("<div class=\"check_flag\"><input type=\"checkbox\" checked id=\"bulid_wx\"></div>");
        }
        if(opt_data.parent_intro){
            id_parent_introduction  = $("<div class=\"check_flag\"><input type=\"checkbox\" checked id=\"parent_intro\"></div>");
        }
        if(opt_data.parent_wx_intro){
            id_parent_wx_introduction = $("<div class=\"check_flag\"><input type=\"checkbox\" checked id=\"parent_wx_intro\"></div>");
        }
        if(opt_data.homework_method){
            id_homework_method      = $("<div class=\"check_flag\"><input type=\"checkbox\" checked id=\"homework_method\"></div>");
        }
        if(opt_data.leave_send){
            id_leave_send           = $("<div class=\"check_flag\"><input type=\"checkbox\" checked id=\"leave_send\"></div>");
        }
        if(opt_data.educate_system){
            id_educational_system   = $("<div class=\"check_flag\"><input type=\"checkbox\" checked id=\"educate_system\"></div>");
        }
        if(opt_data.grade){
            id_grade_confirm        = $("<div class=\"check_flag\"><input type=\"checkbox\"  checked id=\"grade\"></div>");
        }
        if(opt_data.subject){
            id_subject_confirm      = $("<div class=\"check_flag\"><input type=\"checkbox\" checked id=\"subject\"></div>");
        }
        if(opt_data.textbook){
            id_textbook_confirm     = $("<div class=\"check_flag\"><input type=\"checkbox\"  checked id=\"textbook\"></div>");
        }


        
        id_return_record_type.val(opt_data.revisit_type);
        
        id_return_record_record.val(opt_data.operator_note);
        $operation_satisfy_flag.val(opt_data.operation_satisfy_flag );
        $operation_satisfy_type.val(opt_data.operation_satisfy_type );
        $operation_satisfy_info.val(opt_data.operation_satisfy_info );
        $child_class_performance_flag.val(opt_data.child_class_performance_flag );
        $child_class_performance_type.val(opt_data.child_class_performance_type);
        $child_class_performance_info.val(opt_data.child_class_performance_info);
        $tea_content_satisfy_flag.val(opt_data.tea_content_satisfy_flag );
        $tea_content_satisfy_type.val(opt_data.tea_content_satisfy_type);
        $tea_content_satisfy_info.val(opt_data.tea_content_satisfy_info );
        $other_parent_info.val(opt_data.other_parent_info );
        $other_warning_info.val(opt_data.other_warning_info );
        $school_score_change_flag.val(opt_data.school_score_change_flag );
        $school_score_change_info.val(opt_data.school_score_change_info);
        $school_work_change_flag.val(opt_data.school_work_change_flag );
        $school_work_change_type.val(opt_data.school_work_change_type );
        $school_work_change_info.val(opt_data.school_work_change_info );
        id_parent_guidance_except.val(opt_data.parent_guidance_except);
        id_other_subject_info.val(opt_data.other_subject_info);
        id_tutorial_subject_info.val(opt_data.tutorial_subject_info);
        if(opt_data.recover_time_str === '无'){
            id_recover_time.val("未设置");
        }else{
            id_recover_time.val(opt_data.recover_time_str);
        }
        id_revisit_path.val(opt_data.revisit_path);
        id_recent_learn_info.val(opt_data.recent_learn_info);
        

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


        $.show_key_value_table("回访录入-编辑", arr ,{
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
                $.do_ajax("/revisit/update_revisit", {
                    "userid":userid,
                    "revisit_time":opt_data.revisit_time,
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


    $(".opt-edit1").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var $revisit_type =$("<select/>");
        var $lesson_total = $("<input/>");
        var $revisit_person= $("<select> <option value=\"爸爸\">爸爸</option> <option value=\"妈妈\">妈妈</option> <option value=\"孩子\">孩子</option>  <option value=\"其他\">其他</option> </select>");
        var $operator_note= $("<textarea/>");
        var $operation_satisfy_flag =$("<select/>");
        var $operation_satisfy_type =$("<select/>");
        var $operation_satisfy_info  = $("<textarea/>");
        var $record_tea_class_flag =$("<select/>");
        var $child_performance   = $("<textarea/>");
        var $tea_content_satisfy_flag  =$("<select/>");
        var $tea_content_satisfy_type =$("<select/>");
        var $tea_content_satisfy_info  = $("<textarea/>");
        var $other_parent_info  = $("<textarea/>");



        Enum_map.append_option_list( "revisit_type", $revisit_type);
        Enum_map.append_option_list( "set_boolean",  $operation_satisfy_flag,true);
        Enum_map.append_option_list( "set_boolean",  $record_tea_class_flag,true);
        Enum_map.append_option_list( "set_boolean",  $tea_content_satisfy_flag,true);
        Enum_map.append_option_list( "operation_satisfy_type", $operation_satisfy_type,true);
        Enum_map.append_option_list( "tea_content_satisfy_type", $tea_content_satisfy_type,true);

        
        $revisit_type.val(opt_data.revisit_type );
        $revisit_person.val(opt_data.revisit_person );
        $operator_note.val(opt_data.operator_note );
        $operation_satisfy_flag.val(opt_data.operation_satisfy_flag );
        $operation_satisfy_type.val(opt_data.operation_satisfy_type );
        $operation_satisfy_info.val(opt_data.operation_satisfy_info );
        $record_tea_class_flag.val(opt_data.record_tea_class_flag );
        $child_performance.val(opt_data.child_performance );
        $tea_content_satisfy_flag.val(opt_data.tea_content_satisfy_flag );
        $tea_content_satisfy_type.val(opt_data.tea_content_satisfy_type);
        $tea_content_satisfy_info.val(opt_data.tea_content_satisfy_info );
        $other_parent_info.val(opt_data.other_parent_info );



        var arr=[
            ["回访时间", opt_data.revisit_time ],
            ["通话时长", opt_data.duration],
            ["类型",  $revisit_type ],
            ["回访对象",  $revisit_person ],
            ["说明",  $operator_note ],
            ["家长对于我们的软件操作和体验是否满意",  $operation_satisfy_flag ],
            ["家长对于我们的软件操作和体验不满意的类型",  $operation_satisfy_type ],
            ["家长对于我们的软件操作和体验不满意的具体描述",  $operation_satisfy_info ],
            ["是否完成反馈老师对于近期课程的评价和不足",  $record_tea_class_flag ],
            ["学生在校近期表现",  $child_performance ],
            ["家长对于老师教学内容和水平是否满意",  $tea_content_satisfy_flag ],
            ["家长对于老师教学内容和水平不满意的类型",  $tea_content_satisfy_type ],
            ["家长对于老师教学内容和水平不满意的具体描述",  $tea_content_satisfy_info ],
            ["退费预警其他情况说明",  $other_parent_info ],
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
            if (val1==1 || val1==0) {
                show_field( $operation_satisfy_type ,false );
                show_field( $operation_satisfy_info,false );
            }else{
                show_field( $operation_satisfy_type ,true);
                show_field( $operation_satisfy_info,true);
            }
            if (val2==1 || val2==0) {
                show_field( $tea_content_satisfy_type ,false );
                show_field( $tea_content_satisfy_info,false );
            }else{
                show_field( $tea_content_satisfy_type ,true);
                show_field( $tea_content_satisfy_info,true);
            }

        };

        $operation_satisfy_flag.on("change",function(){
            reset_ui();
        });
        $tea_content_satisfy_flag.on("change",function(){
            reset_ui();
        });



        $.show_key_value_table("编辑",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog){
                $.do_ajax("/user_deal/set_revisit",{
                    userid : g_args.sid,
                    revisit_time: opt_data.revisit_time,
                    revisit_type: $revisit_type.val() ,
                    revisit_person: $revisit_person.val() ,
                    operator_note: $operator_note.val(),
                    operation_satisfy_flag: $operation_satisfy_flag.val(),
                    operation_satisfy_type: $operation_satisfy_type.val(),
                    operation_satisfy_info: $operation_satisfy_info.val(),
                    record_tea_class_flag: $record_tea_class_flag.val(),
                    child_performance: $child_performance.val(),
                    tea_content_satisfy_flag: $tea_content_satisfy_flag.val(),
                    tea_content_satisfy_type: $tea_content_satisfy_info.val(),
                    tea_content_satisfy_info: $tea_content_satisfy_info.val(),
                    other_parent_info: $other_parent_info.val()
                });
            }
        },function(){
            reset_ui();
        });

    });

    $(".opt-edit2").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var $revisit_type =$("<select/>");
        var $lesson_total = $("<input/>");
        var $revisit_person= $("<select> <option value=\"爸爸\">爸爸</option> <option value=\"妈妈\">妈妈</option> <option value=\"孩子\">孩子</option>  <option value=\"其他\">其他</option> </select>");
        var $operator_note= $("<textarea/>");
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



        Enum_map.append_option_list( "revisit_type", $revisit_type);
        Enum_map.append_option_list( "set_boolean",  $operation_satisfy_flag,true);
        Enum_map.append_option_list( "set_boolean",  $school_work_change_flag,true);
        Enum_map.append_option_list( "child_class_performance_type",  $child_class_performance_type,true);
        Enum_map.append_option_list( "operation_satisfy_type", $operation_satisfy_type,true);
        Enum_map.append_option_list( "child_class_performance_flag", $child_class_performance_flag,true);
        Enum_map.append_option_list( "school_score_change_flag", $school_score_change_flag,true);
        Enum_map.append_option_list( "school_work_change_type", $school_work_change_type,true);
        Enum_map.append_option_list( "tea_content_satisfy_type", $tea_content_satisfy_type,true);
        Enum_map.append_option_list( "tea_content_satisfy_flag", $tea_content_satisfy_flag,true);

        $revisit_type.val(opt_data.revisit_type );
        $revisit_person.val(opt_data.revisit_person );
        $operator_note.val(opt_data.operator_note );
        $operation_satisfy_flag.val(opt_data.operation_satisfy_flag );
        $operation_satisfy_type.val(opt_data.operation_satisfy_type );
        $operation_satisfy_info.val(opt_data.operation_satisfy_info );
        $child_class_performance_flag.val(opt_data.child_class_performance_flag );
        $child_class_performance_type.val(opt_data.child_class_performance_type);
        $child_class_performance_info.val(opt_data.child_class_performance_info);
        $tea_content_satisfy_flag.val(opt_data.tea_content_satisfy_flag );
        $tea_content_satisfy_type.val(opt_data.tea_content_satisfy_type);
        $tea_content_satisfy_info.val(opt_data.tea_content_satisfy_info );
        $other_parent_info.val(opt_data.other_parent_info );
        $other_warning_info.val(opt_data.other_warning_info );
        $school_score_change_flag.val(opt_data.school_score_change_flag );
        $school_score_change_info.val(opt_data.school_score_change_info);
        $school_work_change_flag.val(opt_data.school_work_change_flag );
        $school_work_change_type.val(opt_data.school_work_change_type );
        $school_work_change_info.val(opt_data.school_work_change_info );




        var arr=[
            ["回访时间", opt_data.revisit_time ],
            ["通话时长", opt_data.duration],
            ["类型",  $revisit_type ],
            ["回访对象",  $revisit_person ],
            ["说明",  $operator_note ],
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

        $.show_key_value_table("编辑",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog){
                $.do_ajax("/user_deal/set_revisit",{
                    userid : g_args.sid,
                    revisit_time: opt_data.revisit_time,
                    revisit_type: $revisit_type.val() ,
                    revisit_person: $revisit_person.val() ,
                    operator_note: $operator_note.val(),
                    operation_satisfy_flag: $operation_satisfy_flag.val(),
                    operation_satisfy_type: $operation_satisfy_type.val(),
                    operation_satisfy_info: $operation_satisfy_info.val(),
                    child_class_performance_flag: $child_class_performance_flag.val(),
                    child_class_performance_type: $child_class_performance_type.val(),
                    child_class_performance_info: $child_class_performance_info.val(),
                    school_score_change_flag: $school_score_change_flag.val(),
                    school_score_change_info: $school_score_change_info.val(),
                    school_work_change_flag: $school_work_change_flag.val(),
                    school_work_change_type: $school_work_change_type.val(),
                    school_work_change_info: $school_work_change_info.val(),
                    tea_content_satisfy_flag: $tea_content_satisfy_flag.val(),
                    tea_content_satisfy_type: $tea_content_satisfy_type.val(),
                    tea_content_satisfy_info: $tea_content_satisfy_info.val(),
                    other_parent_info: $other_parent_info.val(),
                    other_warning_info: $other_warning_info.val(),
                    sys_operator:opt_data.sys_operator
                });
            }
        },function(){
            reset_ui();
        });

    });

    //测试-编辑
    $(".opt-edit-test").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id_time = $("<input />");
        var id_call = $("<input />");
      
      
        

        id_time.datetimepicker({
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i',
            step:30,
            onChangeDateTime :function(){

            }

        });

        var arr=[
            ["回访时间", id_time ],
            ["电话回访id", id_call],
        ];
        $.show_key_value_table("修改-test", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {

                $.do_ajax("/ajax_deal3/update_revisit_info_test", {
                    userid : g_args.sid,
                    revisit_time: opt_data.revisit_time,
                    time : id_time.val(),
                    call_flag:id_call.val()
                });
            }
        });



    });

    $(".opt-warning-record").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id_warning_deal_info  = $("<textarea />");
        var id_warning_deal_url = $("<div><input class=\"warning_deal_url\" id=\"warning_deal_url\" type=\"text\"readonly ><div ><span><a class=\"upload_gift_pic\" id=\"id_upload_warning_deal\" href=\"javascript:;\">上传</a></span><span><a style=\"margin-left:20px\" href=\"javascript:;\" id=\"id_del_warning_deal\">删除</a></span></div></div>");
        var id_is_warning_flag = $("<select><option value=\"1\">预警中</option><option value=\"2\">已解决</option></select>");
        id_warning_deal_info.val(opt_data.warning_deal_info);
        id_is_warning_flag.val(opt_data.is_warning_flag);
        id_warning_deal_url.find("#warning_deal_url").val(opt_data.url);
        var arr = [
            ["预警处理方案",  id_warning_deal_info ],
            ["相关图片上传",  id_warning_deal_url ],
            ["预警解决",  id_is_warning_flag ]
        ];

        id_warning_deal_url.find("#id_del_warning_deal").on("click",function(){
            id_warning_deal_url.find("#warning_deal_url").val("");
        });
        $.show_key_value_table("预警处置", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {

                $.do_ajax("/user_deal/set_revisit_warning_deal_info", {
                    userid : g_args.sid,
                    revisit_time: opt_data.revisit_time,
                    warning_deal_url : id_warning_deal_url.find("#warning_deal_url").val(),
                    warning_deal_info: id_warning_deal_info.val(),
                    is_warning_flag:id_is_warning_flag.val()
                });
            }
        },function(){
            $.custom_upload_file('id_upload_warning_deal',true,function (up, info, file) {
                var res = $.parseJSON(info);

                $("#warning_deal_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

        });


        

    });

    $(".show_pic").on('click',function(){
        var url = $(this).data("url");
        $.wopen(url);
        
    });


    $(".opt-audio").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var   url = opt_data.record_url;
        if (opt_data.load_wav_self_flag) {
            var file=opt_data.record_url.split("/")[4];
            file=file.split(".")[0]+".mp3";
            url= "/audio/"+file;
        }

        var html_node = $(" <div  style=\"text-align:center;\"  > <div id=\"drawing_list\" style=\"width:100%\"  > </div>  <audio preload=\"none\"  > </audio> </div> <br>  <a href=\""+url+"\" class=\"btn btn-primary \"  target=\"_blank\"> 下载 </a> ");

        var audio_node   = html_node.find("audio" );

        BootstrapDialog.show({
            title    : "录音:"+opt_data.phone ,
            message  : html_node,
            closable : true,
            onhide   : function(dialogRef){
            },
            onshown: function() {


                    //加载mp3
                    audiojs.events.ready(function(){
                        var as = audiojs.createAll({}, audio_node  );
                        as[0].load(url);
                        as[0].play();

                    });
            }
        });


    });


    $('.opt-change').set_input_change_event(load_data);

    $("#id_reload_ytx").on("click",function(){
        $.do_ajax("/ss_deal/sync_ytx",{});
    });

    $(".opt_detail").on("click",function(){
        var userid = $(this).data("userid");
        var revisit_time = $(this).data("revisit_time");
        $.do_ajax("/revisit/get_revisit_info_by_revisit_time", {
                    "userid":userid,
                    "revisit_time" : revisit_time,
        },function(result){
            if(result.info == "success" && result.ret_info != null){
                var ret_info = result.ret_info;
                var revisit_type = ret_info[0]['revisit_type'];
                if(revisit_type == '停课月度回访'){
                    var revisit_path = ret_info[0]['revisit_path'];
                    var revisit_person = ret_info[0]['revisit_person'];
                    var operator_note  = ret_info[0]['operator_note'];
                    var html_node_ha = $("<div style=\"text-align:center;\"> "
                                    +"<div id=\"drawing_list\" style=\"width:100%\">"
                                    +"</div><audio preload=\"none\"></audio></div>"
                                    +"<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > "
                                    +"<tr><td>回访路径</td><td>"+revisit_path+"</td></tr>"
                                    +"<tr><td>回访对象</td><td>"+revisit_person+"</td></tr>"
                                    +"<tr><td>回访记录</td><td>"+operator_note+"</td></tr>"
                                    +"</table></div>"
                                );
                    BootstrapDialog.show({
                      title    : '停课月度回访',
                      message  : html_node_ha,
                      closable : true,
                      onhide   : function(dialogRef){
                      }
                    });
                }
                else if(revisit_type == '首次课后回访'){
                    revisit_path = ret_info[0]['revisit_path'];
                    revisit_person = ret_info[0]['revisit_person'];
                    operator_note  = ret_info[0]['operator_note'];
                    var operation_satisfy_flag =  ret_info[0]['operation_satisfy_flag'];
                    if(operation_satisfy_flag < 2){
                        var operation_satisfy_flag_str = ret_info[0]['operation_satisfy_flag_str'];
                        var operation_satisfy = "<tr><td>软件操作是否满意</td><td>"+operation_satisfy_flag_str+"</td></tr>";
                    }else{
                        operation_satisfy_flag_str = ret_info[0]['operation_satisfy_flag_str'];
                        var operation_satisfy_type_str = ret_info[0]['operation_satisfy_type_str'];
                        var operation_satisfy_info = ret_info[0]['operation_satisfy_info'];
                        operation_satisfy = "<tr><td>软件操作是否满意</td><td>"+operation_satisfy_flag_str+"</td></tr><tr><td>软件操作不满意的类型</td><td>"+operation_satisfy_type_str+"<tr><td>软件操作不满意的具体描述</td><td>"+operation_satisfy_info+"</td></tr>";
                    }

                    var child_class_performance_flag = ret_info[0]['child_class_performance_flag'];
                    if(child_class_performance_flag < 3){
                        var child_class_performance_flag_str = ret_info[0]['child_class_performance_flag_str'];
                        var child_class_performance = "<tr><td>孩子课堂表现</td><td>"+child_class_performance_flag_str+"</td></tr>";
                    }else{
                        child_class_performance_flag_str = ret_info[0]['child_class_performance_flag_str'];
                        var child_class_performance_type_str = ret_info[0]['child_class_performance_type_str'];
                        var child_class_performance_info = ret_info[0]['child_class_performance_info'];
                        child_class_performance = "<tr><td>孩子课堂表现</td><td>"+child_class_performance_flag_str+"</td></tr><tr><td>孩子课堂表现不好的类型</td><td>"+child_class_performance_type_str+"</td></tr><tr><td>孩子课堂表现不好的具体描述</td><td>"+child_class_performance_info+"</td></tr>";
                    }

                    var tea_content_satisfy_flag = ret_info[0]['tea_content_satisfy_flag'];
                    if(tea_content_satisfy_flag < 3){
                        var tea_content_satisfy_flag_str = ret_info[0]['tea_content_satisfy_flag_str'];
                        var tea_content_satisfy = "<tr><td>对于老师or教学是否满意</td><td>"+tea_content_satisfy_flag_str+"</td></tr>";
                    }else{
                        tea_content_satisfy_flag_str = ret_info[0]['tea_content_satisfy_flag_str'];
                        var tea_content_satisfy_type_str = ret_info[0]['tea_content_satisfy_type_str'];
                        var tea_content_satisfy_info = ret_info[0]['tea_content_satisfy_info'];
                        tea_content_satisfy = "<tr><td>对于老师or教学是否满意</td><td>"+tea_content_satisfy_flag_str+"</td></tr><tr><td>对于老师or教学不满意的类型</td><td>"+tea_content_satisfy_type_str+"</td></tr><tr><td>对于老师or教学不满意的具体描述"+"</td><td>"+tea_content_satisfy_info+"</td></tr>";
                    }
                    var other_parent_info = ret_info[0]['other_parent_info'];
                    var other_warning_info = ret_info[0]['other_warning_info'];
                    


                    html_node_ha = $("<div style=\"text-align:center;\"> "
                                     +"<div id=\"drawing_list\" style=\"width:100%\">"
                                     +"</div><audio preload=\"none\"></audio></div>"
                                     +"<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > "
                                     +"<tr><td>回访路径</td><td>"+revisit_path+"</td></tr>"
                                     +"<tr><td>回访对象</td><td>"+revisit_person+"</td></tr>"
                                     +operation_satisfy
                                     +child_class_performance
                                     +tea_content_satisfy
                                     +"<tr><td>家长意见或建议</td><td>"+other_parent_info+"</td></tr>"
                                     +"<tr><td>其他预警问题</td><td>"+other_warning_info+"</td></tr>"
                                     +"</table></div>"
                                );
                    BootstrapDialog.show({
                      title    : '停课月度回访',
                      message  : html_node_ha,
                      closable : true,
                      onhide   : function(dialogRef){
                      }
                    });
                }
                else if(revisit_type == '首次课前回访'){
                    var revisit_path = ret_info[0]['revisit_path'];
                    var revisit_person = ret_info[0]['revisit_person'];
                    var self_intro   = ret_info[0]['self_intro'];
                    var check_lesson = ret_info[0]['check_lesson'];
                    var bulid_wx     = ret_info[0]['bulid_wx'];
                    var parent_intro = ret_info[0]['parent_intro'];
                    var parent_wx_intro = ret_info[0]['parent_wx_intro'];
                    var homework_method = ret_info[0]['homework_method'];
                    var leave_send   = ret_info[0]['leave_send'];
                    var educate_system = ret_info[0]['educate_system'];
                    var grade        = ret_info[0]['grade'];
                    var subject      = ret_info[0]['subject'];
                    var textbook     = ret_info[0]['textbook'];
                    var radio = '';
                    if(self_intro > 0){
                        radio += "<tr><td>自我介绍</td><td><input type=\"checkbox\" checked /></td></tr>";
                    }
                    if(check_lesson > 0){
                        radio += "<tr><td>上课时间核对</td><td><input type=\"checkbox\" checked /></td></tr>";
                    }
                    if(bulid_wx  > 0){
                        radio += "<tr><td>微信群建立</td><td><input type=\"checkbox\" checked /></td></tr>";
                    }
                    if(parent_intro  > 0){
                        radio += "<tr><td>家长端介绍</td><td><input type=\"checkbox\" checked /></td></tr>";
                    }
                    if(parent_wx_intro  > 0){
                        radio += "<tr><td>家长微信公众号介绍</td><td><input type=\"checkbox\" checked /></td></tr>";
                    }
                    if(homework_method  > 0){
                        radio += "<tr><td>做作业方式</td><td><input type=\"checkbox\" checked /></td></tr>";
                    }
                    if(leave_send  > 0){
                        radio += "<tr><td>请假制度发送</td><td><input type=\"checkbox\" checked /></td></tr>";
                    }
                    if(educate_system  > 0){
                        radio += "<tr><td>学制确认</td><td><input type=\"checkbox\" checked /></td></tr>";
                    }
                    if(grade  > 0){
                        radio += "<tr><td>年级确认</td><td><input type=\"checkbox\" checked /></td></tr>";
                    }
                    if(subject  > 0){
                        radio += "<tr><td>科目确认</td><td><input type=\"checkbox\" checked /></td></tr>";
                    }
                    if(textbook  > 0){
                        radio += "<tr><td>教材版本确认</td><td><input type=\"checkbox\" checked /></td></tr>";
                    }
                    var parent_guidance_except  = ret_info[0]['parent_guidance_except'];
                    var tutorial_subject_info   = ret_info[0]['tutorial_subject_info'];
                    var other_subject_info      = ret_info[0]['other_subject_info'];
                    var html_node_ha = $("<div style=\"text-align:center;\"> "
                                    +"<div id=\"drawing_list\" style=\"width:100%\">"
                                    +"</div><audio preload=\"none\"></audio></div>"
                                    +"<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > "
                                    +"<tr><td>回访路径</td><td>"+revisit_path+"</td></tr>"
                                    +"<tr><td>回访对象</td><td>"+revisit_person+"</td></tr>"
                                    +radio
                                    +"<tr><td>家长辅导预期</td><td>"+parent_guidance_except+"</td></tr>"
                                    +"<tr><td>辅导科目情况</td><td>"+tutorial_subject_info+"</td></tr>"
                                    +"<tr><td>其他科目情况</td><td>"+other_subject_info+"</td></tr>"
                                    +"</table></div>"
                                );
                    BootstrapDialog.show({
                      title    : '首次课前回访',
                      message  : html_node_ha,
                      closable : true,
                      onhide   : function(dialogRef){
                      }
                    });
                }
                else if(revisit_type == '其他回访'){
                    var revisit_path = ret_info[0]['revisit_path'];
                    var revisit_person = ret_info[0]['revisit_person'];
                    var recent_learn_info  = ret_info[0]['recent_learn_info'];
                    var recover_time  = ret_info[0]['recover_time'];
                    var other_parent_info = ret_info[0]['other_parent_info'];
                    var other_warning_info = ret_info[0]['other_warning_info'];
                    var html_node_ha = $("<div style=\"text-align:center;\"> "
                                    +"<div id=\"drawing_list\" style=\"width:100%\">"
                                    +"</div><audio preload=\"none\"></audio></div>"
                                    +"<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > "
                                    +"<tr><td>回访路径</td><td>"+revisit_path+"</td></tr>"
                                    +"<tr><td>回访对象</td><td>"+revisit_person+"</td></tr>"
                                    +"<tr><td>其他情况说明</td><td>"+recent_learn_info+"</td></tr>"
                                    +"<tr><td>复课时间</td><td>"+recover_time+"</td></tr>"
                                    +"<tr><td>家长意见或建议</td><td>"+other_parent_info+"</td></tr>"
                                    +"<tr><td>其他预警问题</td><td>"+other_warning_info+"</td></tr>"
                                    +"</table></div>"
                                );
                    BootstrapDialog.show({
                      title    : '其他回访',
                      message  : html_node_ha,
                      closable : true,
                      onhide   : function(dialogRef){
                      }
                    });
                }
                else if(revisit_type == '学情回访' || revisit_type == '首次回访' || revisit_type == '月度回访' || revisit_type == '系统'){

                    var revisit_person = ret_info[0]['revisit_person'];
                    var operator_note  = ret_info[0]['operator_note'];
                    var operation_satisfy_flag =  ret_info[0]['operation_satisfy_flag'];
                    if(operation_satisfy_flag < 2){
                        var operation_satisfy_flag_str = ret_info[0]['operation_satisfy_flag_str'];
                        var operation_satisfy = "<tr><td>软件操作是否满意</td><td>"+operation_satisfy_flag_str+"</td></tr>";
                    }else{
                        operation_satisfy_flag_str = ret_info[0]['operation_satisfy_flag_str'];
                        var operation_satisfy_type_str = ret_info[0]['operation_satisfy_type_str'];
                        var operation_satisfy_info = ret_info[0]['operation_satisfy_info'];
                        operation_satisfy = "<tr><td>软件操作是否满意</td><td>"+operation_satisfy_flag_str+"</td></tr><tr><td>软件操作不满意的类型</td><td>"+operation_satisfy_type_str+"<tr><td>软件操作不满意的具体描述</td><td>"+operation_satisfy_info+"</td></tr>";
                    }
                    var child_class_performance_flag = ret_info[0]['child_class_performance_flag'];
                    if(child_class_performance_flag < 3){
                        var child_class_performance_flag_str = ret_info[0]['child_class_performance_flag_str'];
                        var child_class_performance = "<tr><td>孩子课堂表现</td><td>"+child_class_performance_flag_str+"</td></tr>";
                    }else{
                        child_class_performance_flag_str = ret_info[0]['child_class_performance_flag_str'];
                        var child_class_performance_type_str = ret_info[0]['child_class_performance_type_str'];
                        var child_class_performance_info = ret_info[0]['child_class_performance_info'];
                        child_class_performance = "<tr><td>孩子课堂表现</td><td>"+child_class_performance_flag_str+"</td></tr><tr><td>孩子课堂表现不好的类型</td><td>"+child_class_performance_type_str+"</td></tr><tr><td>孩子课堂表现不好的具体描述</td><td>"+child_class_performance_info+"</td></tr>";
                    }
                    var school_score_change_flag = ret_info[0]['school_score_change_flag'];
                    if(school_score_change_flag < 2){
                        var school_score_change_flag_str = ret_info[0]['school_score_change_flag_str'];
                        var school_score_change = "<tr><td>学校成绩变化</td><td>"+school_score_change_flag_str+"</td></tr>";
                    }else{
                        school_score_change_flag_str = ret_info[0]['school_score_change_flag_str'];
                        var school_score_change_info  = ret_info[0]['school_score_change_info'];
                        school_score_change = "<tr><td>学校成绩变化</td><td>"+school_score_change_flag_str+"</td></tr><tr><td>学校成绩变差的具体描述</td><td>"+school_score_change_info+"</td></tr>";
                    }

                    var school_work_change_flag  = ret_info[0]['school_work_change_flag'];
                    if(school_work_change_flag < 1 || school_work_change_flag > 1){
                        var school_work_change_flag_str = ret_info[0]['school_work_change_flag_str'];
                        var school_work_change = "<tr><td>学业变化</td><td>"+school_work_change_flag_str+"</td></tr>";
                    }else{
                        school_work_change_flag_str = ret_info[0]['school_work_change_flag_str'];
                        var school_work_change_type_str = ret_info[0]['school_work_change_type_str'];
                        var school_work_change_info  = ret_info[0]['school_work_change_info'];
                        school_work_change = "<tr><td>学业变化</td><td>"+school_work_change_flag_str+"</td></tr><tr><td>学业变化的类型</td><td>"+school_work_change_type_str+"</td></tr><tr><td>学业变化的具体描述</td><td>"+school_work_change_info+"</td></tr>";
                        
                    }
                    var tea_content_satisfy_flag = ret_info[0]['tea_content_satisfy_flag'];
                    if(tea_content_satisfy_flag < 3){
                        var tea_content_satisfy_flag_str = ret_info[0]['tea_content_satisfy_flag_str'];
                        var tea_content_satisfy = "<tr><td>对于老师or教学是否满意</td><td>"+tea_content_satisfy_flag_str+"</td></tr>";
                    }else{
                        tea_content_satisfy_flag_str = ret_info[0]['tea_content_satisfy_flag_str'];
                        var tea_content_satisfy_type_str = ret_info[0]['tea_content_satisfy_type_str'];
                        var tea_content_satisfy_info = ret_info[0]['tea_content_satisfy_info'];
                        tea_content_satisfy = "<tr><td>对于老师or教学是否满意</td><td>"+tea_content_satisfy_flag_str+"</td></tr><tr><td>对于老师or教学不满意的类型</td><td>"+tea_content_satisfy_type_str+"</td></tr><tr><td>对于老师or教学不满意的具体描述"+"</td><td>"+tea_content_satisfy_info+"</td></tr>";
                    }
                    var other_parent_info = ret_info[0]['other_parent_info'];
                    var other_warning_info = ret_info[0]['other_warning_info'];
                    var html_node_ha = $("<div style=\"text-align:center;\"> "
                                    +"<div id=\"drawing_list\" style=\"width:100%\">"
                                    +"</div><audio preload=\"none\"></audio></div>"
                                    +"<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > "
                                    +"<tr><td>回访对象</td><td>"+revisit_person+"</td></tr>"
                                    +"<tr><td>回访记录</td><td>"+operator_note+"</td></tr>"
                                    +operation_satisfy
                                    +child_class_performance
                                    +school_score_change
                                    +school_work_change
                                    +tea_content_satisfy
                                    +"<tr><td>家长意见或建议</td><td>"+other_parent_info+"</td></tr>"
                                    +"<tr><td>其他预警问题</td><td>"+other_warning_info+"</td></tr>"
                                    +"</table></div>"
                                );
                    BootstrapDialog.show({
                      title    : '学情回访',
                      message  : html_node_ha,
                      closable : true,
                      onhide   : function(dialogRef){
                      }
                    });
                }
            }    
        });

    });
});
