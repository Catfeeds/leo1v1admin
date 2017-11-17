interface GargsStatic {
	teacherid:	number;
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
	teacherid	:any;
	subject	:any;
	grade_start	:any;
	grade_end	:any;
	grade_part_ex	:any;
	phone	:any;
	realname	:any;
	subject_str	:any;
	grade_start_str	:any;
	grade_end_str	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/reaearch_teacher_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-reaearch_teacher_lesson_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		teacherid:	$('#id_teacherid').val()
    });
}
$(function(){


	$('#id_teacherid').val(g_args.teacherid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
*/
