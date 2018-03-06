interface GargsStatic {
	rule_id:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	detail_id	:any;
	level	:any;
	name	:any;
	content	:any;
	deduct_marks	:any;
	punish_type	:any;
	add_punish	:any;
	rank_num	:any;
	create_time	:any;
	deduct_marks_str	:any;
	level_str	:any;
	punish	:any;
}

/*

tofile: 
	 mkdir -p ../rule_txt; vi  ../rule_txt/rule_detail.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/rule_txt-rule_detail.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		rule_id:	$('#id_rule_id').val()
		});
}
$(function(){


	$('#id_rule_id').val(g_args.rule_id);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">rule_id</span>
                <input class="opt-change form-control" id="id_rule_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["rule_id title", "rule_id", "th_rule_id" ]])!!}
*/
