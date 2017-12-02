/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info_admin-index.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val()
        });
    }


	$('#id_teacherid').val(g_args.teacherid);
    $("#id_set_teacher").on("click", function(){
        var id_nick = $("<input />");
        var id_realname = $("<input />");
        var id_phone = $("<input />");
        var id_email = $("<input />");
        var id_birth = $("<input />");
        var id_gender = $("<select />");
        var id_textbook_type = $("<select />");
        var id_grade_part_ex = $("<select />");
        var id_subject = $("<select />");
        var id_putonghua_is_correctly = $("<select />");
        var id_is_good_flag = $("<select />");
        var id_work_year = $("<input />");
        var id_advantage = $("<input >");
        var id_base_intro = $("<textarea />");
        var id_dialect_notes = $("<textarea />");
        var id_teaching_achievement  = $("<textarea />");
        var id_parent_student_evaluate = $("<textarea />");
        Enum_map.append_option_list("gender",id_gender,true);
        Enum_map.append_option_list("grade_part_ex",id_grade_part_ex,true);
        Enum_map.append_option_list("textbook_type",id_textbook_type,true);
        Enum_map.append_option_list("subject",id_subject,true);
        Enum_map.append_option_list("teacher_is_good",id_is_good_flag,true);
        Enum_map.append_option_list("putonghua_is_correctly",id_putonghua_is_correctly,true);
        $.do_ajax("/teacher_info_admin/get_teacher_info_for_js", {
            "teacherid":g_teacherid            
        },function(resp){
            id_nick.val(resp.data.nick);
            id_realname.val(resp.data.realname);
            id_phone.val(resp.data.phone);
            id_email.val(resp.data.email);
            id_birth.val(resp.data.birth);
            id_gender.val(resp.data.gender);
            id_work_year.val(resp.data.work_year);
            id_advantage.val(resp.data.advantage);
            id_base_intro.val(resp.data.base_intro);
            id_textbook_type.val(resp.data.textbook_type);
            id_grade_part_ex.val(resp.data.grade_part_ex);
            id_subject.val(resp.data.subject);
            id_putonghua_is_correctly.val(resp.data.putonghua_is_correctly);
            id_dialect_notes.val(resp.data.dialect_notes);
            id_is_good_flag.val(resp.data.is_good_flag);
            id_teaching_achievement.val(resp.data.teaching_achievement);
            id_parent_student_evaluate.val(resp.data.parent_student_evaluate);

        });

      
        id_birth.datetimepicker({
            datepicker:true,
            timepicker:true,
            format:'Y-m-d',
            step:30,
            onChangeDateTime :function(){
                
            }

        });
        
        
        var arr = [
            ["昵称",  id_nick] ,
            ["实名",  id_realname] ,
            ["手机号",  id_phone] ,
            ["邮箱",  id_email] ,
            ["出生日期",  id_birth] ,
            ["性别",  id_gender] ,
            ["教龄",  id_work_year] ,
            ["教学特长",  id_advantage] ,
            ["普通话是否标准",  id_putonghua_is_correctly] ,
            ["方言备注",  id_dialect_notes] ,
            ["教师介绍",  id_base_intro] ,
            ["教学成果",  id_teaching_achievement] ,
            ["家长/学生评价",  id_parent_student_evaluate] ,
        ];
        
        id_putonghua_is_correctly.on("change",function(){          
            if(id_putonghua_is_correctly.val() == 1 || id_putonghua_is_correctly.val() == 0){
                id_dialect_notes.parent().parent().hide();
            }else{
                id_dialect_notes.parent().parent().show(); 
            }
        });

        $.show_key_value_table("修改教师信息", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/teacher_info_admin/set_teacher_info", {
                    "teacherid"              : g_teacherid,
                    "nick"                   : id_nick.val(),
                    "realname"               : id_realname.val(),
                    "phone"                  : id_phone.val(),
                    "email"                  : id_email.val(),
                    "birth"                  : id_birth.val(),
                    "gender"                 : id_gender.val(),
                    "work_year"              : id_work_year.val(),
                    "advantage"              : id_advantage.val(),
                    "base_intro"             : id_base_intro.val(),
                    "putonghua_is_correctly" : id_putonghua_is_correctly.val(),
                    "dialect_notes"          : id_dialect_notes.val(),
                    "teaching_achievement"   : id_teaching_achievement.val(),
                    "parent_student_evaluate": id_parent_student_evaluate.val()
                });
            }
        },function(){
            if(id_putonghua_is_correctly.val() == 1 || id_putonghua_is_correctly.val() == 0){
                id_dialect_notes.parent().parent().hide();
            }else{
                id_dialect_notes.parent().parent().show(); 
            }
        });
	  });

    $.custom_upload_file('id_upload_face',1,function (up, info, file) {
        var res = $.parseJSON(info);
        $.ajax({
            url: '/teacher_info_admin/set_teacher_face',
            type: 'POST',
            data: {
				        'face'      : res.key,
                'teacherid' : g_teacherid
			      },
            dataType: 'json',
            success: function(data) {
                window.location.reload();
            }
        });

    }, null,["png", "jpg",'jpeg','bmp','gif']);  

    $.custom_upload_file('id_upload_quiz_analyse',1,function (up, info, file) {
        var res = $.parseJSON(info);
        $.ajax({
            url: '/teacher_info_admin/set_teacher_quiz_analyse',
            type: 'POST',
            data: {
				'quiz_analyse':  res.key,
                'teacherid':g_teacherid
			},
            dataType: 'json',
            success: function(data) {
                window.location.reload();
            }
        });

    }, null,["png", "jpg",'jpeg','bmp','gif']);  
         
    $.custom_upload_file('id_upload_jianli',1,function (up, info, file) {
        var res = $.parseJSON(info);
        $.ajax({
            url: '/teacher_info_admin/set_teacher_jianli',
            type: 'POST',
            data: {
				'jianli':  res.key,
                'teacherid':g_teacherid
			},
            dataType: 'json',
            success: function(data) {
                window.location.reload();
            }
        });

    }, null,["doc", "docx","xls",'pdf']);  

    $("#id_read_jianli").on('click',function(){
        if(g_jianli == undefined || g_jianli == ""){
            alert("简历未上传!");
        }else{
            $.wopen(g_jianli);
        }
    });
    

	$('.opt-change').set_input_change_event(load_data);
});

   


