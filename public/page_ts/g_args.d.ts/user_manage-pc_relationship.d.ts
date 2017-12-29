interface GargsStatic {
	page_num:	number;
	page_count:	number;
	studentid:	number;
	parentid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	parentid	:any;
	parent_type	:any;
	userid	:any;
	phone	:any;
	role	:any;
	login_phone	:any;
	parent_nick	:any;
	user_nick	:any;
	parent_type_str	:any;
	role_str	:any;
	phone_hide	:any;
	login_phone_hide	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/pc_relationship.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-pc_relationship.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		studentid:	$('#id_studentid').val(),
		parentid:	$('#id_parentid').val()
		});
}
$(function(){


	$('#id_studentid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.studentid,
		"onChange"     : load_data,
		"th_input_id"  : "th_studentid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_parentid').val(g_args.parentid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">studentid</span>
                <input class="opt-change form-control" id="id_studentid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["studentid title", "studentid", "th_studentid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">parentid</span>
                <input class="opt-change form-control" id="id_parentid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["parentid title", "parentid", "th_parentid" ]])!!}
*/
