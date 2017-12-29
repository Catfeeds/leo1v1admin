interface GargsStatic {
	order_by_str:	string;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	test_seller_id:	number;
	groupid:	number;
	self_groupid:	number;
	is_group_leader_flag:	number;
	tongji_type:	number;//枚举: App\Enums\Etongji_type
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../main_page; vi  ../main_page/seller.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-seller.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		order_by_str:	$('#id_order_by_str').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		test_seller_id:	$('#id_test_seller_id').val(),
		groupid:	$('#id_groupid').val(),
		self_groupid:	$('#id_self_groupid').val(),
		is_group_leader_flag:	$('#id_is_group_leader_flag').val(),
		tongji_type:	$('#id_tongji_type').val()
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
	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_test_seller_id').val(g_args.test_seller_id);
	$('#id_groupid').val(g_args.groupid);
	$('#id_self_groupid').val(g_args.self_groupid);
	$('#id_is_group_leader_flag').val(g_args.is_group_leader_flag);
	$('#id_tongji_type').admin_set_select_field({
		"enum_type"    : "tongji_type",
		"field_name" : "tongji_type",
		"select_value" : g_args.tongji_type,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_tongji_type",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_by_str title", "order_by_str", "th_order_by_str" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_seller_id</span>
                <input class="opt-change form-control" id="id_test_seller_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_seller_id title", "test_seller_id", "th_test_seller_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["groupid title", "groupid", "th_groupid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">self_groupid</span>
                <input class="opt-change form-control" id="id_self_groupid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["self_groupid title", "self_groupid", "th_self_groupid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_group_leader_flag</span>
                <input class="opt-change form-control" id="id_is_group_leader_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_group_leader_flag title", "is_group_leader_flag", "th_is_group_leader_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">分类</span>
                <select class="opt-change form-control" id="id_tongji_type" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tongji_type title", "tongji_type", "th_tongji_type" ]])!!}
*/
