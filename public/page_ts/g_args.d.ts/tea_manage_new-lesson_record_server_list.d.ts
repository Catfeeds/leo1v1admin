interface GargsStatic {
	page_num:	number;
	page_count:	number;
	record_audio_server1:	string;
	xmpp_server_name:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	lessonid	:any;
	record_audio_server1	:any;
	xmpp_server_name	:any;
	lesson_start	:any;
	lesson_end	:any;
	userid	:any;
	teacherid	:any;
	index	:any;
	lesson_time	:any;
	student_nick	:any;
	teacher_nick	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage_new; vi  ../tea_manage_new/lesson_record_server_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage_new-lesson_record_server_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			record_audio_server1:	$('#id_record_audio_server1').val(),
			xmpp_server_name:	$('#id_xmpp_server_name').val()
        });
    }


	$('#id_record_audio_server1').val(g_args.record_audio_server1);
	$('#id_xmpp_server_name').val(g_args.xmpp_server_name);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">record_audio_server1</span>
                <input class="opt-change form-control" id="id_record_audio_server1" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">xmpp_server_name</span>
                <input class="opt-change form-control" id="id_xmpp_server_name" />
            </div>
        </div>
*/
