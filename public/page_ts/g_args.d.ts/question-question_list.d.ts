interface GargsStatic {
	subject:	number;
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
	question_id	:any;
	title	:any;
	subject	:any;
	detail	:any;
	subject_str	:any;
}

/*

tofile: 
	 mkdir -p ../question; vi  ../question/question_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question-question_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		subject:	$('#id_subject').val()
    });
}
$(function(){


	$('#id_subject').val(g_args.subject);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>
*/
