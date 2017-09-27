interface GargsStatic {
	create_time:	string;
	teacherid:	number;
	subject:	number;
	page_count:	number;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	teacherid	:any;
	nick	:any;
	join_info	:any;
	index	:any;
	join_info_str	:any;
	xishu	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/teacher_meeting_join_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_meeting_join_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			create_time:	$('#id_create_time').val(),
			teacherid:	$('#id_teacherid').val(),
			subject:	$('#id_subject').val(),
			page_count:	$('#id_page_count').val()
        });
    }


	$('#id_create_time').val(g_args.create_time);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_subject').val(g_args.subject);
	$('#id_page_count').val(g_args.page_count);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">create_time</span>
                <input class="opt-change form-control" id="id_create_time" />
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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">page_count</span>
                <input class="opt-change form-control" id="id_page_count" />
            </div>
        </div>
*/
