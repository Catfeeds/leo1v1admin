interface GargsStatic {
	userid:	number;
	phone:	string;
	grade:	number;//App\Enums\Egrade
	parentid:	number;
	wx_openid:	string;
	bankcard:	string;
	idcard:	string;
	bank_address:	string;
	bank_account:	string;
	bank_phone:	string;
	bank_province:	string;
	bank_city:	string;
	bank_type:	string;
	zfb_name:	string;
	zfb_account:	string;
	agent_type:	number;
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
	p_nickname	:any;
	p_phone	:any;
	pp_nickname	:any;
	pp_phone	:any;
	s_userid	:any;
	agent_type	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/agent_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			userid:	$('#id_userid').val(),
			phone:	$('#id_phone').val(),
			grade:	$('#id_grade').val(),
			parentid:	$('#id_parentid').val(),
			wx_openid:	$('#id_wx_openid').val(),
			bankcard:	$('#id_bankcard').val(),
			idcard:	$('#id_idcard').val(),
			bank_address:	$('#id_bank_address').val(),
			bank_account:	$('#id_bank_account').val(),
			bank_phone:	$('#id_bank_phone').val(),
			bank_province:	$('#id_bank_province').val(),
			bank_city:	$('#id_bank_city').val(),
			bank_type:	$('#id_bank_type').val(),
			zfb_name:	$('#id_zfb_name').val(),
			zfb_account:	$('#id_zfb_account').val(),
			agent_type:	$('#id_agent_type').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade"));

	$('#id_userid').val(g_args.userid);
	$('#id_phone').val(g_args.phone);
	$('#id_grade').val(g_args.grade);
	$('#id_parentid').val(g_args.parentid);
	$('#id_wx_openid').val(g_args.wx_openid);
	$('#id_bankcard').val(g_args.bankcard);
	$('#id_idcard').val(g_args.idcard);
	$('#id_bank_address').val(g_args.bank_address);
	$('#id_bank_account').val(g_args.bank_account);
	$('#id_bank_phone').val(g_args.bank_phone);
	$('#id_bank_province').val(g_args.bank_province);
	$('#id_bank_city').val(g_args.bank_city);
	$('#id_bank_type').val(g_args.bank_type);
	$('#id_zfb_name').val(g_args.zfb_name);
	$('#id_zfb_account').val(g_args.zfb_account);
	$('#id_agent_type').val(g_args.agent_type);


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
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">parentid</span>
                <input class="opt-change form-control" id="id_parentid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">wx_openid</span>
                <input class="opt-change form-control" id="id_wx_openid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">bankcard</span>
                <input class="opt-change form-control" id="id_bankcard" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">idcard</span>
                <input class="opt-change form-control" id="id_idcard" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">bank_address</span>
                <input class="opt-change form-control" id="id_bank_address" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">bank_account</span>
                <input class="opt-change form-control" id="id_bank_account" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">bank_phone</span>
                <input class="opt-change form-control" id="id_bank_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">bank_province</span>
                <input class="opt-change form-control" id="id_bank_province" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">bank_city</span>
                <input class="opt-change form-control" id="id_bank_city" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">bank_type</span>
                <input class="opt-change form-control" id="id_bank_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">zfb_name</span>
                <input class="opt-change form-control" id="id_zfb_name" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">zfb_account</span>
                <input class="opt-change form-control" id="id_zfb_account" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">agent_type</span>
                <input class="opt-change form-control" id="id_agent_type" />
            </div>
        </div>
*/
