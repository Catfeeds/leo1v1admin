interface GargsStatic {
	resource_type:	number;
	subject:	number;
	grade:	number;
	file_title:	string;
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
	resource_id	:any;
	resource_type	:any;
	file_title	:any;
	file_size	:any;
	file_type	:any;
	update_time	:any;
	edit_adminid	:any;
	down_num	:any;
	error_num	:any;
	is_use	:any;
	is_use_str	:any;
	nick	:any;
}

/*

tofile: 
	 mkdir -p ../resource; vi  ../resource/get_all.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-get_all.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		resource_type:	$('#id_resource_type').val(),
		subject:	$('#id_subject').val(),
		grade:	$('#id_grade').val(),
		file_title:	$('#id_file_title').val()
    });
}
$(function(){


	$('#id_resource_type').val(g_args.resource_type);
	$('#id_subject').val(g_args.subject);
	$('#id_grade').val(g_args.grade);
	$('#id_file_title').val(g_args.file_title);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">resource_type</span>
                <input class="opt-change form-control" id="id_resource_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">file_title</span>
                <input class="opt-change form-control" id="id_file_title" />
            </div>
        </div>
*/
