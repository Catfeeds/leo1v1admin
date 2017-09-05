interface GargsStatic {
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
	num	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/agent_list_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_list_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			type:	$('#id_type').val()
        });
    }


	$('#id_type').val(g_args.type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type</span>
                <input class="opt-change form-control" id="id_type" />
            </div>
        </div>
*/
