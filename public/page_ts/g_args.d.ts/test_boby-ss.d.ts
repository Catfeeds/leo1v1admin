interface GargsStatic {
	page_num:	number;
	page_count:	number;
	nick_phone:	string;
	account_role:	string;//枚举列表: \App\Enums\Eaccount_role
 }
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	uid	:any;
	account	:any;
	account_role	:any;
	name	:any;
	phone	:any;
	create_time	:any;
	account_role_str	:any;
	unick	:any;
}

/*

tofile: 
	 mkdir -p ../test_boby; vi  ../test_boby/ss.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_boby-ss.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			nick_phone:	$('#id_nick_phone').val(),
			account_role:	$('#id_account_role').val()
        });
    }


	$('#id_nick_phone').val(g_args.nick_phone);
	$('#id_account_role').val(g_args.account_role);
	$.enum_multi_select( $('#id_account_role'), 'account_role', function(){load_data();} )


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">nick_phone</span>
                <input class="opt-change form-control" id="id_nick_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <input class="opt-change form-control" id="id_account_role" />
            </div>
        </div>
*/
