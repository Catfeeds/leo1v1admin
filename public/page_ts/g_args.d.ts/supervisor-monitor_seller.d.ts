interface GargsStatic {
	date:	string;
	st_application_nick:	string;
	userid:	number;
	teacherid:	number;
	run_flag:	number;
	assistantid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	lessonid	:any;
	userid	:any;
	teacherid	:any;
	assistantid	:any;
	lesson_start	:any;
	lesson_end	:any;
	courseid	:any;
	lesson_type	:any;
	lesson_num	:any;
	current_server	:any;
	server_type	:any;
	st_application_nick	:any;
	index	:any;
	region	:any;
	ip	:any;
	port	:any;
	room_id	:any;
	lesson_time	:any;
	lesson_type_str	:any;
	assistant_nick	:any;
	teacher_nick	:any;
	student_nick	:any;
	server_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../supervisor ; vi  ../supervisor/monitor_seller.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/supervisor-monitor_seller.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date:	$('#id_date').val(),
			st_application_nick:	$('#id_st_application_nick').val(),
			userid:	$('#id_userid').val(),
			teacherid:	$('#id_teacherid').val(),
			run_flag:	$('#id_run_flag').val(),
			assistantid:	$('#id_assistantid').val()
        });
    }


	$('#id_date').val(g_args.date);
	$('#id_st_application_nick').val(g_args.st_application_nick);
	$('#id_userid').val(g_args.userid);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_run_flag').val(g_args.run_flag);
	$('#id_assistantid').val(g_args.assistantid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">date</span>
                <input class="opt-change form-control" id="id_date" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">st_application_nick</span>
                <input class="opt-change form-control" id="id_st_application_nick" />
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
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">run_flag</span>
                <input class="opt-change form-control" id="id_run_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
*/
