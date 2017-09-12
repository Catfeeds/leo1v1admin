/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_lesson_review-test_lesson_review_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $group_suc_flag = $("<select/>");
        var $master_suc_flag = $("<select/>");
        Enum_map.append_option_list("group_suc_flag",$group_suc_flag ,true );
        Enum_map.append_option_list("master_suc_flag",$master_suc_flag ,true );
        $group_suc_flag.val(opt_data.group_suc_flag);
        $master_suc_flag.val(opt_data.master_suc_flag);
        var arr=[
            ["组长审核",  $group_suc_flag],
            ["主管审核",  $master_suc_flag],
        ];

        $.show_key_value_table("排课审核", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/test_lesson_review/test_lesson_review_edit",{
                    "id":opt_data.id,
                    "group_suc_flag" : $group_suc_flag.val(),
                    "master_suc_flag" : $master_suc_flag.val()
                })
            }
        })
    });

    $('.opt-change').set_input_change_event(load_data);
});
