interface GargsStatic {
	origin:	string;
	origin_ex:	string;
	seller_groupid_ex:	string;
	admin_revisiterid:	number;
	groupid:	number;
	tmk_adminid:	number;
	check_field_id:	number;
	is_history:	number;
	sta_data_type:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	is_show_pie_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	key0	:any;
	key1	:any;
	key2	:any;
	key3	:any;
	key4	:any;
	key0_class	:any;
	key1_class	:any;
	key2_class	:any;
	key3_class	:any;
	key4_class	:any;
	level	:any;
	create_time	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/channel_statistics.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-channel_statistics.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		origin:	$('#id_origin').val(),
		origin_ex:	$('#id_origin_ex').val(),
		seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
		admin_revisiterid:	$('#id_admin_revisiterid').val(),
		groupid:	$('#id_groupid').val(),
		tmk_adminid:	$('#id_tmk_adminid').val(),
		check_field_id:	$('#id_check_field_id').val(),
		is_history:	$('#id_is_history').val(),
		sta_data_type:	$('#id_sta_data_type').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		is_show_pie_flag:	$('#id_is_show_pie_flag').val()
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
	$('#id_origin').val(g_args.origin);
	$('#id_origin_ex').val(g_args.origin_ex);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);
	$('#id_groupid').val(g_args.groupid);
	$('#id_tmk_adminid').val(g_args.tmk_adminid);
	$('#id_check_field_id').val(g_args.check_field_id);
	$('#id_is_history').val(g_args.is_history);
	$('#id_sta_data_type').val(g_args.sta_data_type);
	$('#id_is_show_pie_flag').val(g_args.is_show_pie_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["origin title", "origin", "th_origin" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_ex</span>
                <input class="opt-change form-control" id="id_origin_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["origin_ex title", "origin_ex", "th_origin_ex" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_groupid_ex title", "seller_groupid_ex", "th_seller_groupid_ex" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">admin_revisiterid</span>
                <input class="opt-change form-control" id="id_admin_revisiterid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["admin_revisiterid title", "admin_revisiterid", "th_admin_revisiterid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["groupid title", "groupid", "th_groupid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tmk_adminid</span>
                <input class="opt-change form-control" id="id_tmk_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tmk_adminid title", "tmk_adminid", "th_tmk_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_field_id</span>
                <input class="opt-change form-control" id="id_check_field_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["check_field_id title", "check_field_id", "th_check_field_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_history</span>
                <input class="opt-change form-control" id="id_is_history" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_history title", "is_history", "th_is_history" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sta_data_type</span>
                <input class="opt-change form-control" id="id_sta_data_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["sta_data_type title", "sta_data_type", "th_sta_data_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_show_pie_flag</span>
                <input class="opt-change form-control" id="id_is_show_pie_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_show_pie_flag title", "is_show_pie_flag", "th_is_show_pie_flag" ]])!!}
*/
