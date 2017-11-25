interface GargsStatic {
	id_open_flag:	number;
	id_can_disable_flag:	number;
	page_num:	number;
	page_count:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	id	:any;
	title	:any;
	date_range_start	:any;
	date_range_end	:any;
	user_join_time_start	:any;
	user_join_time_end	:any;
	lesson_times_min	:any;
	lesson_times_max	:any;
	last_test_lesson_start	:any;
	last_test_lesson_end	:any;
	grade_list	:any;
	open_flag	:any;
	can_disable_flag	:any;
	period_flag_list	:any;
	contract_type_list	:any;
	power_value	:any;
	max_count	:any;
	max_change_value	:any;
	max_count_activity_type_list	:any;
	order_activity_discount_type	:any;
	discount_json	:any;
	period_flag_list_str	:any;
	contract_type_list_str	:any;
	can_disable_flag_str	:any;
	open_flag_str	:any;
	order_activity_discount_type_str	:any;
	grade_list_str	:any;
	date_range_time	:any;
	lesson_times_range	:any;
	user_join_time_range	:any;
	last_test_lesson_range	:any;
	discount_list	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student2; vi  ../seller_student2/show_order_activity_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student2-show_order_activity_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		id_open_flag:	$('#id_id_open_flag').val(),
		id_can_disable_flag:	$('#id_id_can_disable_flag').val()
    });
}
$(function(){


	$('#id_id_open_flag').val(g_args.id_open_flag);
	$('#id_id_can_disable_flag').val(g_args.id_can_disable_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_open_flag</span>
                <input class="opt-change form-control" id="id_id_open_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_can_disable_flag</span>
                <input class="opt-change form-control" id="id_id_can_disable_flag" />
            </div>
        </div>
*/
