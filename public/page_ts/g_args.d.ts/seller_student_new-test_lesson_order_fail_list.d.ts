interface GargsStatic {
	cur_require_adminid:	number;
	hide_cur_require_adminid:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	origin_userid_flag:	number;//App\Enums\Eboolean
	order_flag:	number;//App\Enums\Eboolean
	test_lesson_order_fail_flag:	number;//App\Enums\Etest_lesson_order_fail_flag
	userid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	key1	:any;
	require_id	:any;
	lesson_start	:any;
	lesson_end	:any;
	userid	:any;
	teacherid	:any;
	grade	:any;
	subject	:any;
	cur_require_adminid	:any;
	test_lesson_fail_flag	:any;
	test_lesson_order_fail_set_time	:any;
	test_lesson_order_fail_flag	:any;
	test_lesson_order_fail_desc	:any;
	contract_status	:any;
	student_nick	:any;
	teacher_nick	:any;
	cur_require_admin_nick	:any;
	test_lesson_fail_flag_str	:any;
	test_lesson_order_fail_flag_str	:any;
	contract_status_str	:any;
	subject_str	:any;
	grade_str	:any;
	test_lesson_order_fail_flag_one	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/test_lesson_order_fail_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-test_lesson_order_fail_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			cur_require_adminid:	$('#id_cur_require_adminid').val(),
			hide_cur_require_adminid:	$('#id_hide_cur_require_adminid').val(),
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			origin_userid_flag:	$('#id_origin_userid_flag').val(),
			order_flag:	$('#id_order_flag').val(),
			test_lesson_order_fail_flag:	$('#id_test_lesson_order_fail_flag').val(),
			userid:	$('#id_userid').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_origin_userid_flag"));
	Enum_map.append_option_list("boolean",$("#id_order_flag"));
	Enum_map.append_option_list("test_lesson_order_fail_flag",$("#id_test_lesson_order_fail_flag"));

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
	$('#id_cur_require_adminid').val(g_args.cur_require_adminid);
	$('#id_hide_cur_require_adminid').val(g_args.hide_cur_require_adminid);
	$('#id_origin_userid_flag').val(g_args.origin_userid_flag);
	$('#id_order_flag').val(g_args.order_flag);
	$('#id_test_lesson_order_fail_flag').val(g_args.test_lesson_order_fail_flag);
	$('#id_userid').val(g_args.userid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cur_require_adminid</span>
                <input class="opt-change form-control" id="id_cur_require_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">hide_cur_require_adminid</span>
                <input class="opt-change form-control" id="id_hide_cur_require_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_origin_userid_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_order_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_lesson_order_fail_flag</span>
                <select class="opt-change form-control" id="id_test_lesson_order_fail_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
*/
