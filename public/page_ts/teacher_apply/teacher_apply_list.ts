/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_apply-teacher_apply_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            teacher_id : $('#id_teacher_id').val(),
            cc_id      : $('#id_cc_id').val(),
            lesson_id  : $('#id_lesson_id').val(),
        });
    }

  // Enum_map.append_option_list("grade",$("#id_grade"));

  $('#id_teacher_id').val(g_args.teacher_id);
  $('#id_cc_id').val(g_args.cc_id);
  $('#id_lesson_id').val(g_args.lesson_id);

 $(".opt-edit").on("click",function(){
     var opt_data = $(this).get_opt_data();

     // var $teacher_id    = $("<input/>");
     // var $cc_id     = $("<input/>");
     // var $lesson_id = $("<input/>");
     // var $question_type = $("<input/>");
     // var $question_content = $("<input/>");
     // var $teacher_flag = $("<select><option selected='selected' value ='0'>请选择</option> <option value ='0'>待处理</option><option value ='1'>已处理</option></select>");
     // var $teacher_time = $("<input/>");
     var $cc_flag = $("<select><option selected='selected' value ='0'>待处理</option> <option value ='1'>已处理</option></select>");
     // var $cc_time = $("<input/>");

        // $teacher_id.val(opt_data.teacher_id);
        // $cc_id.val(opt_data.cc_id);
        // $lesson_id.val(opt_data.lesson_id);
        // $question_type.val(opt_data.question_type);
        // $question_content.val(opt_data.question_content);
        // $teacher_flag.val(opt_data.teacher_flag);
        // $teacher_time.val(opt_data.teacher_time);
        $cc_flag.val(opt_data.cc_flag);
        // $cc_time.val(opt_data.cc_time);
        var arr=[
            // ["讲师id",  $teacher_id],
            // ["销售id",  $cc_id],
            // ["课程id",  $lesson_id],
            // ["问题类型",  $question_type],
            // ["问题描述",  $question_content],
            // ["讲师处理反馈状态",  $teacher_flag],
            // ["讲师处理反馈时间",  $teacher_time],
            ["销售处理反馈状态",  $cc_flag],
            // ["销售处理反馈时间",  $cc_time],
        ];
     // alert(opt_data.id);
        $.show_key_value_table("修改讲师反馈信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/teacher_apply_edit",{
                    "id"  : opt_data.id,
                    // "teacher_id"  : $teacher_id.val(),
                    // "cc_id"     : $cc_id.val(),
                    // "lesson_id"    : $lesson_id.val(),
                    // "question_type"    : $question_type.val(),
                    // "question_content"    : $question_content.val(),
                    // "teacher_flag"    : $teacher_flag.val(),
                    // "teacher_time"    : $teacher_time.val(),
                    "cc_flag"    : $cc_flag.val(),
                    // "cc_time"    : $cc_time.val(),
                })
            }
        })
    });
    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();

        BootstrapDialog.confirm(
            "要删除ID为:" + opt_data.id + "的数据吗？",
            function(val) {
                if (val) {
                    $.do_ajax("/ajax_deal/teacher_apply_del", {
                        "id": opt_data.id
                    })
                }
            })
    });




  $('.opt-change').set_input_change_event(load_data);
});
