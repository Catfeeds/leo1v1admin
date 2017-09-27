interface GargsStatic {
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: string;
declare var g_adminid: string;
interface RowData {
	note_id	:any;
	note_name	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/get_question_tongji.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-get_question_tongji.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }




	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
