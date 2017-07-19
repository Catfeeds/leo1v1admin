interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	subject:	number;//\App\Enums\Esubject
	grade:	number;//\App\Enums\Egrade
	require_flag:	number;//\App\Enums\Eboolean
	class_hour:	number;//\App\Enums\Eboolean
	account_role:	number;//\App\Enums\Eaccount_role
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
	account_role	:any;
	sys_operator	:any;
	orderid	:any;
	userid	:any;
	discount_price	:any;
	promotion_discount_price	:any;
	price	:any;
	subject	:any;
	grade	:any;
	t_2_lesson_count	:any;
	t_1_lesson_count	:any;
	student_nick	:any;
	subject_str	:any;
	grade_str	:any;
	account_role_str	:any;
	cost_price	:any;
	discount_rate	:any;
}

/*

tofile: 
	 mkdir -p ../contract_present; vi  ../contract_present/contract_present_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/contract_present-contract_present_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			subject:	$('#id_subject').val(),
			grade:	$('#id_grade').val(),
			require_flag:	$('#id_require_flag').val(),
			class_hour:	$('#id_class_hour').val(),
			account_role:	$('#id_account_role').val()
        });
    }

	Enum_map.append_option_list("subject",$("#id_subject"));
	Enum_map.append_option_list("grade",$("#id_grade"));
	Enum_map.append_option_list("boolean",$("#id_require_flag"));
	Enum_map.append_option_list("boolean",$("#id_class_hour"));
	Enum_map.append_option_list("account_role",$("#id_account_role"));

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
	$('#id_subject').val(g_args.subject);
	$('#id_grade').val(g_args.grade);
	$('#id_require_flag').val(g_args.require_flag);
	$('#id_class_hour').val(g_args.class_hour);
	$('#id_account_role').val(g_args.account_role);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
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
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_require_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_class_hour" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <select class="opt-change form-control" id="id_account_role" >
                </select>
            </div>
        </div>
*/
