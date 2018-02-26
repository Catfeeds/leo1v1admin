interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	user_name:	string;
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
	phone	:any;
	add_time	:any;
	name	:any;
	education	:any;
	residence	:any;
	gender	:any;
	english	:any;
	polity	:any;
	carded	:any;
	marry	:any;
	child	:any;
	email	:any;
	post	:any;
	dept	:any;
	address	:any;
	strong	:any;
	interest	:any;
	non_compete	:any;
	is_labor	:any;
	work_info	:any;
	family_info	:any;
	is_fre	:any;
	fre_name	:any;
	education_info	:any;
	birth	:any;
	ccb_card	:any;
	height	:any;
	minor	:any;
	birth_type	:any;
	gra_school	:any;
	gra_major	:any;
	health_condition	:any;
	postcodes	:any;
	is_insured	:any;
	residence_type	:any;
	join_time	:any;
	emergency_contact_nick	:any;
	emergency_contact_address	:any;
	trial_dept	:any;
	trial_post	:any;
	native_place	:any;
	trial_start_time	:any;
	trial_end_time	:any;
	photo	:any;
	emergency_contact_phone	:any;
	gender_str	:any;
	trial_start_time_str	:any;
	trial_end_time_str	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/get_apply_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-get_apply_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		user_name:	$('#id_user_name').val()
		});
}
$(function(){


	$('#id_date_range').select_date_range({
		'date_type' : g_args.date_type,
		'opt_date_type' : g_args.opt_date_type,
		'start_time'    : g_args.start_time,
		'end_time'      : g_args.end_time,
		date_type_config : JSON.parse( g_args.date_type_config),
		onQuery :function() {
			load_data();
		});
	$('#id_user_name').val(g_args.user_name);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["user_name title", "user_name", "th_user_name" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
