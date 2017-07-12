/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-test_lesson_assign_teacher.d.ts" />


$(function(){
    function load_data(){
        $.reload_self_page ( {
			seller_student_id: g_args.seller_student_id	
        });
    }



	$('.opt-change').set_input_change_event(load_data);

    $(".opt-send-webcat ").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var args=$.fiter_obj_field(opt_data, ["id", "openid", "seller_student_id" ]);
        //var args=$.  opt_data
        $.do_ajax_t("/seller_student/wx_assign_teacher", args );
    });

});


