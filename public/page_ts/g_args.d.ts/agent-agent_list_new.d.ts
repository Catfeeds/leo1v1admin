interface GargsStatic {
	start_time:	number;
	end_time:	number;
	type:	number;
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
	agent_student_status	:any;
	l1_child_count	:any;
	l2_child_count	:any;
	all_money	:any;
	agent_status	:any;
	agent_status_money	:any;
	agent_status_money_open_flag	:any;
	l1_agent_status_all_money	:any;
	l1_agent_status_test_lesson_succ_count	:any;
	l1_agent_status_all_open_money	:any;
	star_count	:any;
	all_yxyx_money	:any;
	all_open_cush_money	:any;
	all_have_cush_money	:any;
	order_open_all_money	:any;
	child_order_count	:any;
	pp_agent_status_money	:any;
	pp_agent_status_money_open_flag	:any;
	l2_agent_status_all_money	:any;
	l2_agent_status_test_lesson_succ_count	:any;
	l2_agent_status_all_open_money	:any;
	add_reason	:any;
	parent_adminid	:any;
	p_nickname	:any;
	p_phone	:any;
	pp_nickname	:any;
	pp_phone	:any;
	aoid	:any;
	price	:any;
	s_userid	:any;
	origin	:any;
	is_test_user	:any;
	admin_revisiterid	:any;
	tmk_student_status	:any;
	global_tq_called_flag	:any;
	sys_invaild_flag	:any;
	seller_student_status	:any;
	require_admin_type	:any;
	accept_flag	:any;
	tea_nick	:any;
	lesson_user_online_status	:any;
	lesson_start	:any;
	lesson_user_online_status_str	:any;
	agent_type	:any;
	a_create_time	:any;
	a_lesson_start	:any;
	num	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/agent_list_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_list_new.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		type:	$('#id_type').val()
    });
}
$(function(){


	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);
	$('#id_type').val(g_args.type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_time</span>
                <input class="opt-change form-control" id="id_start_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_time</span>
                <input class="opt-change form-control" id="id_end_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type</span>
                <input class="opt-change form-control" id="id_type" />
            </div>
        </div>
*/
