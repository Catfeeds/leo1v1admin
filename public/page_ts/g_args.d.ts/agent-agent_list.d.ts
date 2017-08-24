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
	test_lesson_flag:	number;//\App\Enums\Eboolean
	agent_level:	string;//枚举列表: \App\Enums\Eagent_level
 	order_flag:	number;//\App\Enums\Eboolean
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
	parentid	:any;
	userid	:any;
	phone	:any;
	wx_openid	:any;
	create_time	:any;
	bank_address	:any;
	bank_account	:any;
	bank_phone	:any;
	bank_province	:any;
	bank_city	:any;
	bank_type	:any;
	bankcard	:any;
	idcard	:any;
	zfb_name	:any;
	zfb_account	:any;
	headimgurl	:any;
	nickname	:any;
	type	:any;
	agent_level	:any;
	test_lessonid	:any;
	p_nickname	:any;
	p_phone	:any;
	pp_nickname	:any;
	pp_phone	:any;
	origin	:any;
	student_stu_type	:any;
	lesson_start	:any;
	lesson_user_online_status	:any;
	price	:any;
	p_level	:any;
	pp_level	:any;
	p_price	:any;
	pp_price	:any;
	agent_type	:any;
	agent_type_str	:any;
	agent_level_str	:any;
	student_stu_type_str	:any;
	lesson_user_online_status_str	:any;
	pp_off_info	:any;
	p_off_info	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/agent_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
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
			order_flag:	$('#id_order_flag').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_test_lesson_flag"));
	Enum_map.append_option_list("boolean",$("#id_order_flag"));

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
	$('#id_userid').val(g_args.userid);
	$('#id_phone').val(g_args.phone);
	$('#id_p_phone').val(g_args.p_phone);
	$('#id_agent_type').val(g_args.agent_type);
	$.enum_multi_select( $('#id_agent_type'), 'agent_type', function(){load_data();} )
	$('#id_test_lesson_flag').val(g_args.test_lesson_flag);
	$('#id_agent_level').val(g_args.agent_level);
	$.enum_multi_select( $('#id_agent_level'), 'agent_level', function(){load_data();} )
	$('#id_order_flag').val(g_args.order_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">p_phone</span>
                <input class="opt-change form-control" id="id_p_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">agent_type</span>
                <input class="opt-change form-control" id="id_agent_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_test_lesson_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">agent_level</span>
                <input class="opt-change form-control" id="id_agent_level" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_order_flag" >
                </select>
            </div>
        </div>
*/
