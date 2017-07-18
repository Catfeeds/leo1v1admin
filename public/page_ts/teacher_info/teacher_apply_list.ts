/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-teacher_apply_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }

    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $teacher_flag = $("<select><option selected='selected' value ='0'>待处理</option> <option value ='1'>已处理</option></select>");
        $teacher_flag.val(opt_data.teacher_flag);
        var arr=[
            ["讲师反馈处理状态",$teacher_flag],
        ];
        $.show_key_value_table("修改信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/teacher_info/teacher_apply_edit",{
                    "id"           : opt_data.id,
                    "teacher_flag" : $teacher_flag.val(),
                })
            }
        })
    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除ID为:" + opt_data.id + "的数据吗？",
            function(val){
                if(val){
                    $.do_ajax("/teacher_info/teacher_apply_del",{
                        "id": opt_data.id,
                    })
                }
            })
    })

	  $('.opt-change').set_input_change_event(load_data);
});

