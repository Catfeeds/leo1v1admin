interface GargsStatic {
	group_id:	number;
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
	id	:any;
	phone	:any;
	nickname	:any;
	group_name	:any;
	cycle_student_count	:any;
	cycle_test_lesson_count	:any;
	cycle_order_money	:any;
	cycle_member_count	:any;
	cycle_order_count	:any;
}

/*

tofile: 
	 mkdir -p ../agent_info; vi  ../agent_info/members_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent_info-members_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		group_id:	$('#id_group_id').val()
    });
}
$(function(){


	$('#id_group_id').val(g_args.group_id);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">group_id</span>
                <input class="opt-change form-control" id="id_group_id" />
            </div>
        </div>
*/
