interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	is_warning_flag:	number;
	ass_adminid:	number;
	seller_groupid_ex:	string;
	warning_type_flag:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	revisit_time	:any;
	revisit_person	:any;
	operator_note	:any;
	operator_audio	:any;
	sys_operator	:any;
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
	nick	:any;
	revisit_time_str	:any;
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
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/ass_revisit_warning_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-ass_revisit_warning_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			is_warning_flag:	$('#id_is_warning_flag').val(),
			ass_adminid:	$('#id_ass_adminid').val(),
			seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
			warning_type_flag:	$('#id_warning_type_flag').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
	$('#id_is_warning_flag').val(g_args.is_warning_flag);
	$('#id_ass_adminid').val(g_args.ass_adminid);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
	$('#id_warning_type_flag').val(g_args.warning_type_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_warning_flag</span>
                <input class="opt-change form-control" id="id_is_warning_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">ass_adminid</span>
                <input class="opt-change form-control" id="id_ass_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">warning_type_flag</span>
                <input class="opt-change form-control" id="id_warning_type_flag" />
            </div>
        </div>
*/
