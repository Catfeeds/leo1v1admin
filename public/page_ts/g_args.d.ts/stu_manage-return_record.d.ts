interface GargsStatic {
	sid:	number;
	page_num:	number;
	page_count:	number;
	is_warning_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	userid	:any;
	revisit_time	:any;
	revisit_person	:any;
	operator_note	:any;
	operator_audio	:any;
	sys_operator	:any;
	call_phone_id	:any;
	duration	:any;
	record_url	:any;
	phone	:any;
	revisit_type	:any;
	operation_satisfy_flag	:any;
	operation_satisfy_type	:any;
	operation_satisfy_info	:any;
	record_tea_class_flag	:any;
	child_performance	:any;
	tea_content_satisfy_flag	:any;
	tea_content_satisfy_type	:any;
	tea_content_satisfy_info	:any;
	other_parent_info	:any;
	child_class_performance_flag	:any;
	child_class_performance_type	:any;
	child_class_performance_info	:any;
	school_score_change_flag	:any;
	school_score_change_info	:any;
	school_work_change_flag	:any;
	school_work_change_type	:any;
	school_work_change_info	:any;
	other_warning_info	:any;
	is_warning_flag	:any;
	warning_deal_url	:any;
	warning_deal_info	:any;
	uid	:any;
	parent_guidance_except	:any;
	other_subject_info	:any;
	tutorial_subject_info	:any;
	recover_time	:any;
	revisit_path	:any;
	recent_learn_info	:any;
	information_confirm	:any;
	revisit_time_str	:any;
	recover_time_str	:any;
	revisit_type_str	:any;
	operation_satisfy_flag_str	:any;
	school_work_change_flag_str	:any;
	tea_content_satisfy_flag_str	:any;
	school_work_change_type_str	:any;
	school_score_change_flag_str	:any;
	operation_satisfy_type_str	:any;
	tea_content_satisfy_type_str	:any;
	child_class_performance_flag_str	:any;
	child_class_performance_type_str	:any;
	is_warning_flag_str	:any;
	load_wav_self_flag	:any;
	url	:any;
	master_adminid	:any;
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/return_record.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-return_record.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		sid:	$('#id_sid').val(),
		is_warning_flag:	$('#id_is_warning_flag').val()
		});
}
$(function(){


	$('#id_sid').val(g_args.sid);
	$('#id_is_warning_flag').val(g_args.is_warning_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sid</span>
                <input class="opt-change form-control" id="id_sid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["sid title", "sid", "th_sid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_warning_flag</span>
                <input class="opt-change form-control" id="id_is_warning_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_warning_flag title", "is_warning_flag", "th_is_warning_flag" ]])!!}
*/
