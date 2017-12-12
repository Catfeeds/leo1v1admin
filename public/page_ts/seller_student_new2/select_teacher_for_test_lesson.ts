/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-select_teacher_for_test_lesson.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page({
		    identity      : $('#id_identity').val(),
		    gender        : $('#id_gender').val(),
		    tea_age       : $('#id_tea_age').val(),
		    teacher_tags  : $('#id_teacher_tags').val(),
		    lesson_tags   : $('#id_lesson_tags').val(),
		    teaching_tags : $('#id_teaching_tags').val(),
		    require_id    : $('#id_require_id').val(),
		    refresh_flag  : $('#id_refresh_flag').val(),
    });
}

$(function(){
    Enum_map.append_option_list("identity",$("#id_identity"),true);
    Enum_map.append_option_list("gender",$("#id_gender"),true);
    Enum_map.append_option_list("tea_age",$("#id_tea_age"),true);
    $("#id_identity").val(g_args.identity);
    $("#id_gender").val(g_args.gender);
    $("#id_tea_age").val(g_args.tea_age);
    $("#id_teacher_tags").val(g_args.teacher_tags);
    $("#id_teaching_tags").val(g_args.teaching_tags);
    $("#id_lesson_tags").val(g_args.lesson_tags);
    $("#id_teacherid").val();
    $("#id_teacher_name").val();
    $('#id_require_id').val(g_args.require_id);
    $('#id_').val(g_args.require_id);
    $('#id_refresh_flag').val(g_args.refresh_flag);
	  $('.opt-change').set_input_change_event(load_data);
    $("#id_lesson_time").datetimepicker();

    $(".show_phone").on("click",function(){
        var phone = $(this).data("phone");
        BootstrapDialog.alert(phone);
    });

    $(".show_tea_free_time").on("click",function(){
	      var teacherid = $(this).data("teacherid");
        var jump_url = "/teacher_info_admin/free_time?teacherid="+teacherid;
        window.open(jump_url);
    });

    $(".opt-set-teacher").on("click",function(){
        var data = $(this).get_opt_data();
        console.log(data.realname);
        $("#id_teacherid").val(data.teacherid);
        $("#id_teacher_name").html(data.realname);
    });

    //排课
    $("#id_set_lesson_time").on("click",function(){
        var require_id  = $("#id_require_id").val();
        var lesson_time = $("#id_lesson_time").val();
        var teacherid   = $("#id_teacherid").val();
        var grade       = $("#id_require_info").data("grade");
        var seller_top_flag = $("#id_require_info").data("seller_top_flag");

        var do_post = function(){
            $.do_ajax("/ss_deal/course_set_new",{
                'require_id'      : require_id,
                "grade"           : grade,
                'teacherid'       : teacherid,
                'lesson_start'    : lesson_time,
                'top_seller_flag' : seller_top_flag
            });
        };

        var now        = (new Date()).getTime()/1000;
        var start_time = $.strtotime(lesson_time);
        if ( now > start_time ) {
            alert("上课时间比现在还小.");
            return ;
        } else if ( now + 5*3600  > start_time ) {
            BootstrapDialog.confirm("上课时间离现在很近了,要提交吗?!",function(val){
                if(val) {
                    do_post();
                }
            });
        }else{
            do_post();
        }
    });

    //驳回
    $("#id_refund_lesson").on("click",function(){
        var nick = $("#id_require_info").data("nick");
        var subject_str = $("#id_require_info").data("subject_str");
        var require_id  = $("#id_require_info").data("require_id");
        var $input = $("<input style=\"width:180px\"  placeholder=\"驳回理由\"/>");
        $.show_input(
            nick+" : "+ subject_str+ ",要驳回, 不计算排课数?! ",
            "",function(val){
                $.do_ajax("/ss_deal/set_no_accpect",{
                    'require_id'  : require_id,
                    'fail_reason' : val
                });
            }, $input);
        $input.val("未排课,期待时间已到");
    });




});
