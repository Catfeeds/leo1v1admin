/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-reaearch_teacher_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val()
        });
    }


	$('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user( $("#id_teacherid"), "research_teacher", load_data);
    $.each( $(".opt-show-lessons-new"), function(i,item ){
        $(item).admin_select_teacher_free_time_new({
            "teacherid" : $(item).get_opt_data("teacherid")
        });
    });



	$('.opt-change').set_input_change_event(load_data);
});



