interface GargsStatic {
	phone:	number;
	id:	number;
	page_num:	number;
	page_count:	number;
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
	phone	:any;
	nickname	:any;
	test_lessonid	:any;
	sys_operator	:any;
	account	:any;
	name	:any;
	account_role	:any;
	userid	:any;
	self_order_count	:any;
	self_order_price	:any;
	is_test_lesson_str	:any;
	agent_info	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/agent_child_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_child_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		phone:	$('#id_phone').val(),
		id:	$('#id_id').val(),
		type:	$('#id_type').val()
    });
}
$(function(){


	$('#id_phone').val(g_args.phone);
	$('#id_id').val(g_args.id);
	$('#id_type').val(g_args.type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id</span>
                <input class="opt-change form-control" id="id_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type</span>
                <input class="opt-change form-control" id="id_type" />
            </div>
        </div>
*/
