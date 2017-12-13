interface GargsStatic {
	project:	string;
	tag:	string;
	query_str:	string;
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
	 mkdir -p ../proto; vi  ../proto/cmd_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/proto-cmd_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		project:	$('#id_project').val(),
		tag:	$('#id_tag').val(),
		query_str:	$('#id_query_str').val()
		});
}
$(function(){


	$('#id_project').val(g_args.project);
	$('#id_tag').val(g_args.tag);
	$('#id_query_str').val(g_args.query_str);


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
                <span class="input-group-addon">tag</span>
                <input class="opt-change form-control" id="id_tag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">query_str</span>
                <input class="opt-change form-control" id="id_query_str" />
            </div>
        </div>
*/
