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
	assess_time	:any;
	assess_adminid	:any;
	content	:any;
	assess_res	:any;
	advise_reason	:any;
	assess_nick	:any;
	assess_res_str	:any;
	assess_time_str	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_info; vi  ../teacher_info/teacher_assess.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-teacher_assess.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val()
        });
    }


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
