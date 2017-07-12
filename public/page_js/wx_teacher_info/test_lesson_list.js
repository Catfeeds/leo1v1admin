
/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/wx_teacher_info-test_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			opt_type:	$('#id_opt_type').val()
        });
    }

	$('#id_opt_type').val(g_args.opt_type);


	$('.opt-change').set_input_change_event(load_data);
    
    $(".opt-confrim" ).on("click",function(){
        var opt_data= $(this).get_opt_data();
	    //
        $.do_ajax("/wx_teacher_info/confirm_test_lesson_js",{
            "seller_student_id" : opt_data.seller_student_id
        },function(data){
            alert(data.msg);
            load_data();
        });
    });

});


