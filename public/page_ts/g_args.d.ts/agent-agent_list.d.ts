interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	userid:	number;
	phone:	string;
	p_phone:	string;
	agent_type:	string;//枚举列表: \App\Enums\Eagent_type
 	page_num:	number;
	page_count:	number;
	test_lesson_flag:	number;//枚举: \App\Enums\Eboolean
	agent_level:	string;//枚举列表: \App\Enums\Eagent_level
 	order_flag:	number;//枚举: \App\Enums\Eboolean
	l1_child_count:	string;
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
	id	:any;
	phone	:any;
	nickname	:any;
	agent_level	:any;
	all_yxyx_money	:any;
	all_open_cush_money	:any;
	all_have_cush_money	:any;
	create_time	:any;
	test_lessonid	:any;
	p_nickname	:any;
	p_phone	:any;
	pp_nickname	:any;
	pp_phone	:any;
	lesson_start	:any;
	lesson_user_online_status	:any;
	userid	:any;
	parentid	:any;
	sys_operator	:any;
	account	:any;
	name	:any;
	account_role	:any;
	self_order_count	:any;
	self_order_price	:any;
	child_student_count	:any;
	child_member_count	:any;
	child_student_member_count	:any;
	agent_level_str	:any;
	agent_student_status_str	:any;
	agent_type_str	:any;
	is_test_lesson_str	:any;
	agent_info	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/agent_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		userid:	$('#id_userid').val(),
		phone:	$('#id_phone').val(),
		p_phone:	$('#id_p_phone').val(),
		agent_type:	$('#id_agent_type').val(),
		test_lesson_flag:	$('#id_test_lesson_flag').val(),
		agent_level:	$('#id_agent_level').val(),
		order_flag:	$('#id_order_flag').val(),
		l1_child_count:	$('#id_l1_child_count').val(),
		order_by_str:	$('#id_order_by_str').val()
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
	$('#id_userid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.userid,
		"onChange"     : load_data,
		"th_input_id"  : "th_userid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_phone').val(g_args.phone);
	$('#id_p_phone').val(g_args.p_phone);
	$('#id_agent_type').admin_set_select_field({
		"enum_type"    : "agent_type",
		"field_name" : "agent_type",
		"select_value" : g_args.agent_type,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_agent_type",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_test_lesson_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "test_lesson_flag",
		"select_value" : g_args.test_lesson_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_test_lesson_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_agent_level').admin_set_select_field({
		"enum_type"    : "agent_level",
		"field_name" : "agent_level",
		"select_value" : g_args.agent_level,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_agent_level",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_order_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "order_flag",
		"select_value" : g_args.order_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_order_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_l1_child_count').val(g_args.l1_child_count);
	$('#id_order_by_str').val(g_args.order_by_str);


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
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["userid title", "userid", "th_userid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["phone title", "phone", "th_phone" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">p_phone</span>
                <input class="opt-change form-control" id="id_p_phone" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["p_phone title", "p_phone", "th_p_phone" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">agent_type</span>
                <input class="opt-change form-control" id="id_agent_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["agent_type title", "agent_type", "th_agent_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_test_lesson_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_lesson_flag title", "test_lesson_flag", "th_test_lesson_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">agent_level</span>
                <input class="opt-change form-control" id="id_agent_level" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["agent_level title", "agent_level", "th_agent_level" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_order_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_flag title", "order_flag", "th_order_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">l1_child_count</span>
                <input class="opt-change form-control" id="id_l1_child_count" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["l1_child_count title", "l1_child_count", "th_l1_child_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_by_str title", "order_by_str", "th_order_by_str" ]])!!}
*/
