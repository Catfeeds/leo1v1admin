interface GargsStatic {
	teacherid:	number;
	userid:	number;
	account_role_self:	number;
	ass_master_flag:	number;
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
	 mkdir -p ../human_resource; vi  ../human_resource/regular_course.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-regular_course.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		teacherid:	$('#id_teacherid').val(),
		userid:	$('#id_userid').val(),
		account_role_self:	$('#id_account_role_self').val(),
		ass_master_flag:	$('#id_ass_master_flag').val()
    });
}
$(function(){


	$('#id_teacherid').val(g_args.teacherid);
	$('#id_userid').val(g_args.userid);
	$('#id_account_role_self').val(g_args.account_role_self);
	$('#id_ass_master_flag').val(g_args.ass_master_flag);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role_self</span>
                <input class="opt-change form-control" id="id_account_role_self" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">ass_master_flag</span>
                <input class="opt-change form-control" id="id_ass_master_flag" />
            </div>
        </div>
*/
