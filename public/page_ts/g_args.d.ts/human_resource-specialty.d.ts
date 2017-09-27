interface GargsStatic {
	grade:	number;
	teacherid:	number;
	subject:	number;
	page_num:	number;
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
	grade	:any;
	degree	:any;
	introduction	:any;
	nick	:any;
	grade_str	:any;
	subject_str	:any;
	degree_str	:any;
	number	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/specialty.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-specialty.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			grade:	$('#id_grade').val(),
			teacherid:	$('#id_teacherid').val(),
			subject:	$('#id_subject').val()
        });
    }


	$('#id_grade').val(g_args.grade);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_subject').val(g_args.subject);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>
*/
