interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	seller_groupid_ex:	string;
	master_flag:	string;
	lesson_user_online_status:	number;//\App\Enums\Eset_boolean
	test_assess_flag:	number;//\App\Enums\Eset_boolean
	order_by_str:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	assess	:any;
	cur_require_adminid	:any;
	userid	:any;
	nick	:any;
	phone	:any;
	lessonid	:any;
	teacherid	:any;
	lesson_start	:any;
	lesson_end	:any;
	tq_call_time	:any;
	duration	:any;
	price	:any;
	last_tq_call_time	:any;
	order_time	:any;
	tq_call_count	:any;
	tq_call_all_time	:any;
	lesson_user_online_status	:any;
	duration_str	:any;
	lesson_user_online_status_str	:any;
	cur_require_admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../tongji2; vi  ../tongji2/test_lesson_frist_call_time.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-test_lesson_frist_call_time.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
			master_flag:	$('#id_master_flag').val(),
			lesson_user_online_status:	$('#id_lesson_user_online_status').val(),
			test_assess_flag:	$('#id_test_assess_flag').val(),
			order_by_str:	$('#id_order_by_str').val()
        });
    }

	Enum_map.append_option_list("set_boolean",$("#id_lesson_user_online_status"));
	Enum_map.append_option_list("set_boolean",$("#id_test_assess_flag"));

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
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
	$('#id_master_flag').val(g_args.master_flag);
	$('#id_lesson_user_online_status').val(g_args.lesson_user_online_status);
	$('#id_test_assess_flag').val(g_args.test_assess_flag);
	$('#id_order_by_str').val(g_args.order_by_str);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">master_flag</span>
                <input class="opt-change form-control" id="id_master_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">set_boolean</span>
                <select class="opt-change form-control" id="id_lesson_user_online_status" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">set_boolean</span>
                <select class="opt-change form-control" id="id_test_assess_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
*/
