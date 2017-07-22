/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-get_ass_test_lesson_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			require_adminid:	$('#id_require_adminid').val()
        });
    }


	$('#id_require_adminid').val(g_args.require_adminid);
    $(".opt-confirm").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if(!( opt_data.lessonid )) {
            alert("还没有排课,无需确认");
            return;
        }

        var $fail_greater_4_hour_flag = $("<select> <option value=0>否</option> <option value=1>是</option>  </select>") ;
        var $success_flag = $("<select><option value=0>未设置</option><option value=1>成功</option><option value=2>失败</option></select>") ;
        var $test_lesson_fail_flag=$("<select></select>") ;
        var $fail_reason=$("<textarea></textarea>") ;
        Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true );
        $success_flag.val(opt_data.success_flag );
        $fail_reason.val(opt_data.fail_reason);
        $test_lesson_fail_flag.val(opt_data.test_lesson_fail_flag);
        $fail_greater_4_hour_flag .val(opt_data.fail_greater_4_hour_flag);

        var arr=[
            ["学生", opt_data.nick  ],
            ["老师", opt_data.teacher_nick ],
            ["上课时间", opt_data.lesson_start   ],
            ["是否成功",  $success_flag ],
            ["是否离上课4个小时以前(不付老师工资)", $fail_greater_4_hour_flag],
            ["失败类型", $test_lesson_fail_flag],
            ["失败原因", $fail_reason],
        ];

        var update_show_status =function ()  {
            var show_flag =  $success_flag.val()==2 ;
            $fail_greater_4_hour_flag.key_value_table_show( show_flag);
            $test_lesson_fail_flag.key_value_table_show( show_flag);
            $fail_reason.key_value_table_show( show_flag);
            $test_lesson_fail_flag.html("");
            if ($fail_greater_4_hour_flag.val() ==1 ) { //不付老师工资
                Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true, [100,106,107,108,109,110,111,112,113 ] );
            }else{
                Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true ,
                                            [1,2,109,110,111,112,113] );
            }
        };

        $success_flag.on("change",update_show_status);
        $fail_greater_4_hour_flag.on("change",update_show_status);



        $.show_key_value_table("课程确认", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                $.do_ajax("/ss_deal/confirm_test_lesson", {
                    "require_id"             : opt_data.require_id ,
                    "success_flag"             : $success_flag.val(),
                    "fail_reason"              : $fail_reason.val(),
                    "test_lesson_fail_flag"    : $test_lesson_fail_flag.val(),
                    "fail_greater_4_hour_flag" : $fail_greater_4_hour_flag.val(),
                });
            }
        },function(){
            update_show_status();
        });
    });



	$('.opt-change').set_input_change_event(load_data);
});

