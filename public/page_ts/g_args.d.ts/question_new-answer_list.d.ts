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
	answer_id	:any;
	question_id	:any;
	knowledge_id	:any;
	difficult	:any;
	step	:any;
	detail	:any;
	score	:any;
	title	:any;
	difficult_str	:any;
	step_str	:any;
}

/*

tofile: 
	 mkdir -p ../question_new; vi  ../question_new/answer_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question_new-answer_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
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
*/
