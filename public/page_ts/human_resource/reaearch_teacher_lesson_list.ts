/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-reaearch_teacher_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ({
			      teacherid:	$('#id_teacherid').val()
        });
    }


	  $('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user( $("#id_teacherid"), "research_teacher", load_data);
    $.each( $(".opt-show-lessons-new"), function(i,item ){
        $(item).admin_select_teacher_free_time_new({
            "teacherid" : $(item).get_opt_data("teacherid")
        });
        $(item).hide();
    });
    $(".show_lesson_info").each(function(){
        $(this).admin_select_teacher_free_time_new({
            "teacherid" : $(this).data("teacherid")
        });

    });

	  $('.opt-change').set_input_change_event(load_data);

    $("#id_add_closest").on("click", function(){

        var id_realname           = $("<input>");
        var id_phone              = $("<input>");

        var id_teacher_money_type = $("<input readonly>");
        var id_teacher_type       = $("<input readonly>");
        var id_grade_start        = $("<select/>");
        var id_grade_end          = $("<select/>");
        var id_subject            = $("<select/>");
        
        id_teacher_money_type.val("在职老师");
        id_teacher_type.val("公司教研老师");

        Enum_map.append_option_list("grade", id_grade_start,true);
        Enum_map.append_option_list("grade", id_grade_end,true);
        Enum_map.append_option_list("subject", id_subject,true);

        var arr = [
            [ "老师姓名",  id_realname] ,
            [ "手机号",  id_phone] ,
            [ "科目",  id_subject] ,
            [ "年级开始",  id_grade_start] ,
            [ "年级结束",  id_grade_end] ,
            [ "工资类型",  id_teacher_money_type] ,
            [ "老师类型",  id_teacher_type] ,
        ];

        $.show_key_value_table("添加教研老师", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var realname            = id_realname.val();
                var phone               = id_phone.val();
                var teacher_money_type  = id_teacher_money_type.val();
                var teacher_type        = id_teacher_type.val();
                var grade_start         = id_grade_start.val();
                var grade_end           = id_grade_end.val();
                var subject             = id_subject.val();
                if (!realname) {
                    BootstrapDialog.alert("教研老师名字不能为空");
                    return;
                }
                if (!phone) {
                    BootstrapDialog.alert('教研老师手机号码不能为空');
                    return;
                }
                if (grade_start == 0) {
                    BootstrapDialog.alert('年级开始不能为空');
                    return;
                }
                if (grade_end == 0) {
                    BootstrapDialog.alert('年级结束不能为空');
                    return;
                }

                if (subject == 0) {
                    BootstrapDialog.alert('科目不能为空');
                    return;
                }

                if(grade_end<grade_start){
                    BootstrapDialog.alert("年级结束不能小于年级开始");
                    return;
                }
                

                $.do_ajax('/human_resource/add_research_teacher',{
                    'realname'           : realname,
                    'phone'              : phone,
                    'subject'            : subject,
                    'grade_start'        : grade_start,
                    'grade_end'          : grade_end,
                    'teacher_money_type' : 0,
                    'teacher_type'       : 4
                });

            }
        });
   });

});



