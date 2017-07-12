
/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-test_lesson_no_order_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			grade:	$('#id_grade').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade")); 

	$('#id_grade').val(g_args.grade);


	$('.opt-change').set_input_change_event(load_data);

    
    $(".opt-set-self").on("click",function(){
        var opt_data=$(this).get_opt_data();
       
	    $.do_ajax("/seller_student/set_test_lesson_user_to_self",{
            "userid" : opt_data.userid
        });

        //opt_data.userid
	    
    });
    var init_noit_btn=function( id_name, title) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value =btn.data("value");
        btn.text(value);
        if (value >0 ) {
            btn.addClass("btn-warning");
        }
    };
    

    init_noit_btn("id_opt_count",    "本周已抢名额" );
    init_noit_btn("id_last_count",    "本周剩余名额" );
   
});


