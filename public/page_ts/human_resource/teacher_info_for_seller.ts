/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_info_for_seller.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			address:	$('#id_address').val()
        });
    }


	$('#id_address').val(g_args.address);

    $.each( $(".opt-show-lessons-new"), function(i,item ){
        $(item).admin_select_teacher_free_time_new({
            "teacherid" : $(item).get_opt_data("teacherid")
        });
    });

	$('.opt-change').set_input_change_event(load_data);
});



