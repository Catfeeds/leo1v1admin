interface GargsStatic {
	question_id:	number;
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
	 mkdir -p ../question_new; vi  ../question_new/question_edit.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question_new-question_edit.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		question_id:	$('#id_question_id').val()
		});
}
$(function(){


	$('#id_question_id').val(g_args.question_id);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">question_id</span>
                <input class="opt-change form-control" id="id_question_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["question_id title", "question_id", "th_question_id" ]])!!}
*/
