/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-set_teacher_info.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val()
        });
    }

	$('#id_teacherid').val(g_args.teacherid);
    $("#id_set_teacher").on("click", function(){
        var id_nick     = $("<input >");
        var id_realname = $("<input >");
        var id_phone    = $("<input >");
        var id_email    = $("<input >");
        var id_birth    = $("<input >");
        var id_gender   = $("<select />");
        var id_grade_part_ex = $("<select />");
        var id_putonghua_is_correctly = $("<select />");
        var id_subject    = $("<select />");
        var id_work_year  = $("<input >");
        var id_base_intro = $("<textarea />");
        var id_dialect_notes = $("<textarea />");

        Enum_map.append_option_list("gender",id_gender,true);
        Enum_map.append_option_list("grade_part_ex",id_grade_part_ex,true);
        Enum_map.append_option_list("subject",id_subject,true);
        Enum_map.append_option_list("putonghua_is_correctly",id_putonghua_is_correctly,true);

        id_nick.val(g_nick);
        id_realname.val(g_realname);
        id_phone.val(g_phone);
        id_email.val(g_email);
        id_birth.val(g_birth);
        id_gender.val(g_gender);
        id_work_year.val(g_work_year);
        id_base_intro.val(g_base_intro);
        id_grade_part_ex.val(g_grade_part_ex);
        id_subject.val(g_subject);
        id_putonghua_is_correctly.val(g_putonghua_is_correctly);
        id_dialect_notes.val(g_dialect_notes);
        id_birth.datetimepicker({
            datepicker:true,
            timepicker:true,
            format:'Y-m-d',
            step:30,
            onChangeDateTime :function(){
            }
        });

        var arr = [
            [ "昵称", id_nick] ,
            [ "实名", id_realname] ,
            [ "邮箱",  id_email] ,
            [ "性别",  id_gender] ,
            [ "手机号", id_phone] ,
            [ "出生日期",  id_birth] ,
            [ "年级段",id_grade_part_ex],
            [ "科目",id_subject],
            [ "教龄",  id_work_year] ,
            [ "普通话是否标准",  id_putonghua_is_correctly] ,
            [ "方言备注",  id_dialect_notes] ,
            [ "教师介绍",  id_base_intro] ,
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
                $.do_ajax("/teacher_info/set_teacher_info", {
                    "teacherid"     : g_teacherid,
                    "nick"          : id_nick.val(),
                    "realname"      : id_realname.val(),
                    "phone"         : id_phone.val(),
                    "email"         : id_email.val(),
                    "birth"         : id_birth.val(),
                    "gender"        : id_gender.val(),
                    "work_year"     : id_work_year.val(),
                    "base_intro"    : id_base_intro.val(),
                    "grade_part_ex" : id_grade_part_ex.val(),
                    "subject"       : id_subject.val(),
                    "putonghua_is_correctly" : id_putonghua_is_correctly.val(),
                    "dialect_notes"          : id_dialect_notes.val(),
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
            url: '/teacher_info/set_teacher_face',
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

	$('.opt-change').set_input_change_event(load_data);
});

   


