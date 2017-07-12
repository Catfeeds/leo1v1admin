interface GargsStatic {
	code:	string;
	admin_code:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../wx_login ; vi  ../wx_login/login.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/wx_login-login.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			code:	$('#id_code').val(),
			admin_code:	$('#id_admin_code').val()
        });
    }

	$('#id_code').val(g_args.code);
	$('#id_admin_code').val(g_args.admin_code);


	$('.opt-change').set_input_change_event(load_data);
});



*/
