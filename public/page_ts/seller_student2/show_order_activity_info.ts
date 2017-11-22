/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-show_order_activity_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		order_activity_type:	$('#id_order_activity_type').val()
    });
}
$(function(){

	Enum_map.append_option_list("order_activity_type",$("#id_order_activity_type"));

	$('#id_order_activity_type').val(g_args.order_activity_type);


	$('.opt-change').set_input_change_event(load_data);
});

