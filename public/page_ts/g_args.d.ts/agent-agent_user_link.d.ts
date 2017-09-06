interface GargsStatic {
	phone:	number;
	id:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	p1_name	:any;
	p1_id	:any;
	p1_test_lesson_flag_str	:any;
	p1_price	:any;
	p1_p_agent_level	:any;
	p1_p_agent_level_str	:any;
	p1_p_price	:any;
	p1_agent_status_money	:any;
	p1_agent_status_money_open_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/agent_user_link.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_user_link.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			phone:	$('#id_phone').val(),
			id:	$('#id_id').val()
        });
    }


	$('#id_phone').val(g_args.phone);
	$('#id_id').val(g_args.id);


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
*/
