interface GargsStatic {
	page_num:	number;
	page_count:	number;
	grade:	number;
	subject:	number;
	address:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	textbook_str	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/check.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-check.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		grade:	$('#id_grade').val(),
		subject:	$('#id_subject').val(),
		address:	$('#id_address').val()
    });
}
$(function(){


	$('#id_grade').val(g_args.grade);
	$('#id_subject').val(g_args.subject);
	$('#id_address').val(g_args.address);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">address</span>
                <input class="opt-change form-control" id="id_address" />
            </div>
        </div>
*/
