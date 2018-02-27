interface GargsStatic {
	adminid:	number;
	main_flag:	number;
	become_full_member_flag:	number;
	fulltime_teacher_type:	number;
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
	create_time	:any;
	uid	:any;
	account	:any;
	become_full_member_flag	:any;
	become_full_member_time	:any;
	id	:any;
	assess_time	:any;
	positive_id	:any;
	master_deal_flag	:any;
	main_master_deal_flag	:any;
	name	:any;
	assess_adminid	:any;
	mater_adminid	:any;
	master_assess_time	:any;
	main_mater_adminid	:any;
	main_master_assess_time	:any;
	positive_type	:any;
	add_time	:any;
	create_time_str	:any;
	become_full_member_time_str	:any;
	assess_time_str	:any;
	master_assess_time_str	:any;
	add_time_str	:any;
	main_master_assess_time_str	:any;
	assess_admin_nick	:any;
	mater_admin_nick	:any;
	main_mater_admin_nick	:any;
	master_deal_flag_str	:any;
	main_master_deal_flag_str	:any;
	become_full_member_flag_str	:any;
	positive_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../fulltime_teacher; vi  ../fulltime_teacher/fulltime_teacher_assessment_positive_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/fulltime_teacher-fulltime_teacher_assessment_positive_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		adminid:	$('#id_adminid').val(),
		main_flag:	$('#id_main_flag').val(),
		become_full_member_flag:	$('#id_become_full_member_flag').val(),
		fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
		});
}
$(function(){


	$('#id_adminid').admin_select_user_new({
		"user_type"    : "account",
		"select_value" : g_args.adminid,
		"onChange"     : load_data,
		"th_input_id"  : "th_adminid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_main_flag').val(g_args.main_flag);
	$('#id_become_full_member_flag').val(g_args.become_full_member_flag);
	$('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["adminid title", "adminid", "th_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">main_flag</span>
                <input class="opt-change form-control" id="id_main_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["main_flag title", "main_flag", "th_main_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">become_full_member_flag</span>
                <input class="opt-change form-control" id="id_become_full_member_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["become_full_member_flag title", "become_full_member_flag", "th_become_full_member_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_teacher_type</span>
                <input class="opt-change form-control" id="id_fulltime_teacher_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["fulltime_teacher_type title", "fulltime_teacher_type", "th_fulltime_teacher_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
