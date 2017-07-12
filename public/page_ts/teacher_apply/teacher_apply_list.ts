/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_apply-teacher_apply_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            teacher_id : $('#id_teacher_id').val(),
            cc_id      : $('#id_cc_id').val(),
            lesson_id  : $('#id_lesson_id').val(),
            p_phone    : $('#p_phone').val(),
        });
    }

    $('#id_teacher_id').val(g_args.teacher_id);
    $('#id_cc_id').val(g_args.cc_id);
    $('#id_lesson_id').val(g_args.lesson_id);
    $('#id_p_phone').val(g_args.p_phone);

    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $cc_flag = $("<select><option selected='selected' value ='0'>待处理</option> <option value ='1'>已处理</option></select>");
        $cc_flag.val(opt_data.cc_flag);
        var arr=[
            ["销售处理反馈状态",  $cc_flag],
        ];
        $.show_key_value_table("修改讲师反馈信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/teacher_apply_edit",{
                    "id"      : opt_data.id,
                    "cc_flag" : $cc_flag.val(),
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

    $(".opt-telphone").on("click",function(){
        var opt_data= $(this).get_opt_data();
        var phone    = ""+ opt_data.p_phone;
        phone=phone.split("-")[0];

        try{
            window.navigate(
                "app:1234567@"+phone+"");
        } catch(e){

        };
        $.do_ajax_t("/ss_deal/call_ytx_phone", {
            "phone": opt_data.p_phone,
        } );
    });

    $('.opt-change').set_input_change_event(load_data);
});
