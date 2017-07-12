/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-no_called_list.d.ts" />

$(function(){

    function load_data(){
        $.reload_self_page ( {
			grade:	$('#id_grade').val(),
			has_pad:	$('#id_has_pad').val(),
			subject:	$('#id_subject').val(),
			origin:	$('#id_origin').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade")); 
	Enum_map.append_option_list("pad_type",$("#id_has_pad")); 
	Enum_map.append_option_list("subject",$("#id_subject")); 

	$('#id_grade').val(g_args.grade);
	$('#id_has_pad').val(g_args.has_pad);
	$('#id_subject').val(g_args.subject);
	$('#id_origin').val(g_args.origin);



    
    $(".opt-set-self").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if (g_args.tmk_flag) {
 	    $.do_ajax("/ss_deal/tmk_set_no_called_to_self",{
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id
        });

            
        }else{
 	    $.do_ajax("/ss_deal/set_no_called_to_self",{
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id
        });
           
        }
    });



	$('.opt-change').set_input_change_event(load_data);
});

