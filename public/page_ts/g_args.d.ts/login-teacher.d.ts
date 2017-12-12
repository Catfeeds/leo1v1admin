interface GargsStatic {
	download:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../login; vi  ../login/teacher.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/login-teacher.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		download:	$('#id_download').val()
		});
}
$(function(){


	$('#id_download').val(g_args.download);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">download</span>
                <input class="opt-change form-control" id="id_download" />
            </div>
        </div>
*/
