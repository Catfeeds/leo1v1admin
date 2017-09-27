interface GargsStatic {
	reward_count_type:	number;
	rule_type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	reward_count_type	:any;
	rule_type	:any;
	num	:any;
	money	:any;
	reward_count_type_str	:any;
	rule_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/teacher_reward_rule_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-teacher_reward_rule_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			reward_count_type:	$('#id_reward_count_type').val(),
			rule_type:	$('#id_rule_type').val()
        });
    }


	$('#id_reward_count_type').val(g_args.reward_count_type);
	$('#id_rule_type').val(g_args.rule_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">reward_count_type</span>
                <input class="opt-change form-control" id="id_reward_count_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">rule_type</span>
                <input class="opt-change form-control" id="id_rule_type" />
            </div>
        </div>
*/
