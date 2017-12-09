interface GargsStatic {
	project:	string;
	cmdid:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	tags	:any;
	cmdid	:any;
	name	:any;
	desc	:any;
}

/*

tofile: 
	 mkdir -p ../proto; vi  ../proto/cmd_desc.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/proto-cmd_desc.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		project:	$('#id_project').val(),
		cmdid:	$('#id_cmdid').val()
		});
}
$(function(){


	$('#id_project').val(g_args.project);
	$('#id_cmdid').val(g_args.cmdid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">project</span>
                <input class="opt-change form-control" id="id_project" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cmdid</span>
                <input class="opt-change form-control" id="id_cmdid" />
            </div>
        </div>
*/
