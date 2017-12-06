interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	phone_name:	string;
	phone_location:	string;
	grade:	number;//App\Enums\Egrade
	has_pad:	number;//App\Enums\Epad_type
	subject:	number;//App\Enums\Esubject
	test_lesson_count_flag:	number;
	test_lesson_order_fail_flag:	number;//App\Enums\Etest_lesson_order_fail_flag
	origin:	string;
	return_publish_count:	number;
	cc_called_count:	number;
	cc_no_called_count_new:	number;
	call_admin_count:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	test_lesson_subject_id	:any;
	subject	:any;
	add_time	:any;
	userid	:any;
	phone	:any;
	phone_location	:any;
	has_pad	:any;
	user_desc	:any;
	last_revisit_time	:any;
	free_time	:any;
	free_adminid	:any;
	grade	:any;
	origin	:any;
	realname	:any;
	nick	:any;
	last_lesson_time	:any;
	lesson_start	:any;
	test_lesson_order_fail_flag	:any;
	has_pad_str	:any;
	subject_str	:any;
	grade_str	:any;
	test_lesson_order_fail_flag_str	:any;
	phone_hide	:any;
	free_nick	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/get_free_seller_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-get_free_seller_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		phone_name:	$('#id_phone_name').val(),
		phone_location:	$('#id_phone_location').val(),
		grade:	$('#id_grade').val(),
		has_pad:	$('#id_has_pad').val(),
		subject:	$('#id_subject').val(),
		test_lesson_count_flag:	$('#id_test_lesson_count_flag').val(),
		test_lesson_order_fail_flag:	$('#id_test_lesson_order_fail_flag').val(),
		origin:	$('#id_origin').val(),
		return_publish_count:	$('#id_return_publish_count').val(),
		cc_called_count:	$('#id_cc_called_count').val(),
		cc_no_called_count_new:	$('#id_cc_no_called_count_new').val(),
		call_admin_count:	$('#id_call_admin_count').val()
    });
}
$(function(){

	Enum_map.append_option_list("grade",$("#id_grade"));
	Enum_map.append_option_list("pad_type",$("#id_has_pad"));
	Enum_map.append_option_list("subject",$("#id_subject"));
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
	$('#id_phone_name').val(g_args.phone_name);
	$('#id_phone_location').val(g_args.phone_location);
	$('#id_grade').val(g_args.grade);
	$('#id_has_pad').val(g_args.has_pad);
	$('#id_subject').val(g_args.subject);
	$('#id_test_lesson_count_flag').val(g_args.test_lesson_count_flag);
	$('#id_test_lesson_order_fail_flag').val(g_args.test_lesson_order_fail_flag);
	$('#id_origin').val(g_args.origin);
	$('#id_return_publish_count').val(g_args.return_publish_count);
	$('#id_cc_called_count').val(g_args.cc_called_count);
	$('#id_cc_no_called_count_new').val(g_args.cc_no_called_count_new);
	$('#id_call_admin_count').val(g_args.call_admin_count);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone_name</span>
                <input class="opt-change form-control" id="id_phone_name" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone_location</span>
                <input class="opt-change form-control" id="id_phone_location" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">Pad</span>
                <select class="opt-change form-control" id="id_has_pad" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_lesson_count_flag</span>
                <input class="opt-change form-control" id="id_test_lesson_count_flag" />
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
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">return_publish_count</span>
                <input class="opt-change form-control" id="id_return_publish_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cc_called_count</span>
                <input class="opt-change form-control" id="id_cc_called_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cc_no_called_count_new</span>
                <input class="opt-change form-control" id="id_cc_no_called_count_new" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">call_admin_count</span>
                <input class="opt-change form-control" id="id_call_admin_count" />
            </div>
        </div>
*/
