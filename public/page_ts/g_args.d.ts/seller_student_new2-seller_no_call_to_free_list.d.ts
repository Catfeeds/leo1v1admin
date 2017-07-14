interface GargsStatic {
	admin_revisiterid:	number;
	page_num:	number;
	page_count:	number;
	global_tq_called_flag:	number;//App\Enums\Etq_called_flag
	seller_student_status:	number;//App\Enums\Eseller_student_status
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	add_time	:any;
	admin_revisiterid	:any;
	userid	:any;
	phone	:any;
	user_desc	:any;
	account	:any;
	admin_assign_time	:any;
	global_tq_called_flag	:any;
	seller_student_status	:any;
	seller_level	:any;
	student_nick	:any;
	global_tq_called_flag_str	:any;
	seller_level_str	:any;
	seller_student_status_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/seller_no_call_to_free_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-seller_no_call_to_free_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			admin_revisiterid:	$('#id_admin_revisiterid').val(),
			global_tq_called_flag:	$('#id_global_tq_called_flag').val(),
			seller_student_status:	$('#id_seller_student_status').val()
        });
    }

	Enum_map.append_option_list("tq_called_flag",$("#id_global_tq_called_flag"));
	Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status"));

	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);
	$('#id_global_tq_called_flag').val(g_args.global_tq_called_flag);
	$('#id_seller_student_status').val(g_args.seller_student_status);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">admin_revisiterid</span>
                <input class="opt-change form-control" id="id_admin_revisiterid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">TQ</span>
                <select class="opt-change form-control" id="id_global_tq_called_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_student_status</span>
                <select class="opt-change form-control" id="id_seller_student_status" >
                </select>
            </div>
        </div>
*/
