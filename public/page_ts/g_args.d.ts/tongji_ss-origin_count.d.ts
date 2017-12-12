interface GargsStatic {
	origin:	string;
	origin_ex:	string;
	seller_groupid_ex:	string;
	admin_revisiterid:	number;
	groupid:	number;
	tmk_adminid:	number;
	check_field_id:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	check_value	:any;
	all_count	:any;
	tq_called_count	:any;
	no_call_count	:any;
	assigned_count	:any;
	invalid_count	:any;
	no_connected_count	:any;
	have_intention_a_count	:any;
	have_intention_b_count	:any;
	have_intention_c_count	:any;
	tmk_assigned_count	:any;
	tq_no_call_count	:any;
	tq_call_fail_count	:any;
	tq_call_fail_invalid_count	:any;
	tq_call_succ_invalid_count	:any;
	tq_call_succ_valid_count	:any;
	test_lesson_count	:any;
	distinct_test_count	:any;
	succ_test_lesson_count	:any;
	distinct_succ_count	:any;
	require_count	:any;
	title	:any;
	order_count	:any;
	user_count	:any;
	order_all_money	:any;
	key1	:any;
	key2	:any;
	key3	:any;
	key4	:any;
	key1_class	:any;
	key2_class	:any;
	key3_class	:any;
	key4_class	:any;
	level	:any;
	create_time	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/origin_count.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-origin_count.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		origin:	$('#id_origin').val(),
		origin_ex:	$('#id_origin_ex').val(),
		seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
		admin_revisiterid:	$('#id_admin_revisiterid').val(),
		groupid:	$('#id_groupid').val(),
		tmk_adminid:	$('#id_tmk_adminid').val(),
		check_field_id:	$('#id_check_field_id').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
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
        }
    });
	$('#id_origin').val(g_args.origin);
	$('#id_origin_ex').val(g_args.origin_ex);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);
	$('#id_groupid').val(g_args.groupid);
	$('#id_tmk_adminid').val(g_args.tmk_adminid);
	$('#id_check_field_id').val(g_args.check_field_id);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_ex</span>
                <input class="opt-change form-control" id="id_origin_ex" />
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
                <span class="input-group-addon">admin_revisiterid</span>
                <input class="opt-change form-control" id="id_admin_revisiterid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tmk_adminid</span>
                <input class="opt-change form-control" id="id_tmk_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_field_id</span>
                <input class="opt-change form-control" id="id_check_field_id" />
            </div>
        </div>
*/
