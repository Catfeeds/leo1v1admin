interface GargsStatic {
	id_subject:	number;
	id_open_flag:	number;
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
	score	:any;
	open_flag	:any;
	subject_str	:any;
	open_str	:any;
	knowledge_detail	:any;
}

/*

tofile: 
	 mkdir -p ../question_new; vi  ../question_new/question_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question_new-question_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		id_subject:	$('#id_id_subject').val(),
		id_open_flag:	$('#id_id_open_flag').val()
    });
}
$(function(){


	$('#id_id_subject').val(g_args.id_subject);
	$('#id_id_open_flag').val(g_args.id_open_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_subject</span>
                <input class="opt-change form-control" id="id_id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_open_flag</span>
                <input class="opt-change form-control" id="id_id_open_flag" />
            </div>
        </div>
*/
