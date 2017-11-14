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
	l1_child_count:	string;
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
	userid	:any;
	test_lessonid	:any;
	sys_operator	:any;
	account	:any;
	name	:any;
	account_role	:any;
	self_order_count	:any;
	self_order_price	:any;
	is_test_lesson_str	:any;
	agent_info	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/student_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-student_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
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
		order_flag:	$('#id_order_flag').val(),
		l1_child_count:	$('#id_l1_child_count').val()
    });
}
$(function(){

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
	$('#id_l1_child_count').val(g_args.l1_child_count);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">l1_child_count</span>
                <input class="opt-change form-control" id="id_l1_child_count" />
            </div>
        </div>
*/
