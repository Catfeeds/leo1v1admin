/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_teacher_level_quarter_info.d.ts" />
function load_data(){
    $.reload_self_page ( {
        order_by_str: g_args.order_by_str,
		teacher_money_type:	$('#id_teacher_money_type').val()
    });
}

$(function(){
    
    Enum_map.append_option_list("teacher_money_type", $("#id_teacher_money_type"),true,[1,4,5]);

	$('#id_teacher_money_type').val(g_args.teacher_money_type);

    $(".opt-advance-require").on("click",function(){        
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
            


        BootstrapDialog.confirm("确定要申请晋升吗？", function(val){
            if (val) {
                $.do_ajax( '/teacher_level/set_teacher_advance_require', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.quarter_start,
                    'level_before':opt_data.level,
                    'level_after':opt_data.level_after,
                    'lesson_count':opt_data.lesson_count*100,
                    'lesson_count_score':opt_data.lesson_count_score,
                    'cc_test_num':opt_data.cc_test_num,
                    'cc_order_num':opt_data.cc_order_num,
                    'cc_order_per':opt_data.cc_order_per,
                    'cc_order_score':opt_data.cc_order_score,
                    'other_test_num':opt_data.other_test_num,
                    'other_order_num':opt_data.other_order_num,
                    'other_order_per':opt_data.other_order_per,
                    'other_order_score':opt_data.other_order_score,
                    'record_num':opt_data.record_num,
                    'record_score_avg':opt_data.record_score_avg,
                    'record_final_score':opt_data.record_final_score,
                    'is_refund'  :opt_data.is_refund ,
                    'total_score':opt_data.total_score
                });
            } 
        });

    });


    $(".show_refund_detail").on("click",function(){
        var teacherid = $(this).data("teacherid");
        var start_time = g_args.quarter_start;
        
        $.do_ajax( "/teacher_level/get_teacher_refund_detail_info",{
            "teacherid" :teacherid,
            "start_time":start_time
        },function(resp){
            var title = "学生退费详情";
            var list = resp.data;
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>时间</td><td>学生</td><td>老师责任占比</td></tr></table></div>");
            $.each(list,function(i,item){
                html_node.find("table").append("<tr><td>"+item['apply_time_str']+"</td><td>"+item['nick']+"</td><td>"+item['per']+"%</td></tr>");

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

            dlg.getModalDialog().css("width","800px");
            
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
});







