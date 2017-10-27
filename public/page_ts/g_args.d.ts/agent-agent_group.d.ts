interface GargsStatic {
	group_colconel:	string;
	colconel_agent_id:	number;
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
	group_id	:any;
	group_name	:any;
	create_time	:any;
	phone	:any;
	nickname	:any;
	colconel_agent_id	:any;
	member_num	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/agent_group.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_group.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		group_colconel:	$('#id_group_colconel').val(),
		colconel_agent_id:	$('#id_colconel_agent_id').val()
    });
}
$(function(){


	$('#id_group_colconel').val(g_args.group_colconel);
	$('#id_colconel_agent_id').val(g_args.colconel_agent_id);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">group_colconel</span>
                <input class="opt-change form-control" id="id_group_colconel" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">colconel_agent_id</span>
                <input class="opt-change form-control" id="id_colconel_agent_id" />
            </div>
        </div>
*/
