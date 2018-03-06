interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	phone_province:	string;
	origin_level:	number;
	key0:	string;
	key1:	string;
	key2:	string;
	key3:	string;
	value:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	seller_resource_type	:any;
	first_call_time	:any;
	first_contact_time	:any;
	test_lesson_count	:any;
	first_revisit_time	:any;
	last_revisit_time	:any;
	tmk_assign_time	:any;
	last_contact_time	:any;
	last_contact_cc	:any;
	competition_call_adminid	:any;
	competition_call_time	:any;
	sys_invaild_flag	:any;
	wx_invaild_flag	:any;
	return_publish_count	:any;
	tmk_adminid	:any;
	test_lesson_subject_id	:any;
	seller_student_sub_status	:any;
	add_time	:any;
	global_tq_called_flag	:any;
	seller_student_status	:any;
	userid	:any;
	nick	:any;
	origin	:any;
	origin_level	:any;
	phone_location	:any;
	phone	:any;
	sub_assign_adminid_2	:any;
	admin_revisiterid	:any;
	admin_assign_time	:any;
	sub_assign_time_2	:any;
	origin_assistantid	:any;
	phone_province	:any;
	key0	:any;
	key1	:any;
	key2	:any;
	key3	:any;
	key4	:any;
	value	:any;
	origin_userid	:any;
	subject	:any;
	grade	:any;
	user_desc	:any;
	has_pad	:any;
	require_adminid	:any;
	tmk_student_status	:any;
	first_tmk_set_valid_admind	:any;
	first_tmk_set_valid_time	:any;
	tmk_set_seller_adminid	:any;
	first_tmk_set_seller_time	:any;
	first_admin_master_adminid	:any;
	first_admin_master_time	:any;
	first_admin_revisiterid	:any;
	first_admin_revisiterid_time	:any;
	first_seller_status	:any;
	call_count	:any;
	auto_allot_adminid	:any;
	first_called_cc	:any;
	first_get_cc	:any;
	test_lesson_flag	:any;
	orderid	:any;
	price	:any;
	origin_level_str	:any;
	seller_student_status_str	:any;
	global_tq_called_flag_str	:any;
	cc_nick	:any;
	suc_test_flag	:any;
	order_flag	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ex; vi  ../tongji_ex/seller_student_detail.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ex-seller_student_detail.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		phone_province:	$('#id_phone_province').val(),
		origin_level:	$('#id_origin_level').val(),
		key0:	$('#id_key0').val(),
		key1:	$('#id_key1').val(),
		key2:	$('#id_key2').val(),
		key3:	$('#id_key3').val(),
		value:	$('#id_value').val()
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
	$('#id_phone_province').val(g_args.phone_province);
	$('#id_origin_level').val(g_args.origin_level);
	$('#id_key0').val(g_args.key0);
	$('#id_key1').val(g_args.key1);
	$('#id_key2').val(g_args.key2);
	$('#id_key3').val(g_args.key3);
	$('#id_value').val(g_args.value);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone_province</span>
                <input class="opt-change form-control" id="id_phone_province" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["phone_province title", "phone_province", "th_phone_province" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_level</span>
                <input class="opt-change form-control" id="id_origin_level" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["origin_level title", "origin_level", "th_origin_level" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key0</span>
                <input class="opt-change form-control" id="id_key0" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["key0 title", "key0", "th_key0" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key1</span>
                <input class="opt-change form-control" id="id_key1" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["key1 title", "key1", "th_key1" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key2</span>
                <input class="opt-change form-control" id="id_key2" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["key2 title", "key2", "th_key2" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key3</span>
                <input class="opt-change form-control" id="id_key3" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["key3 title", "key3", "th_key3" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">value</span>
                <input class="opt-change form-control" id="id_value" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["value title", "value", "th_value" ]])!!}
*/
