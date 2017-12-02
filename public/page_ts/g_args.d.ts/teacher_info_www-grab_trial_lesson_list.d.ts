interface GargsStatic {
	_role:	number;
	_userid:	number;
	text:	string;
	time:	string;
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
	 mkdir -p ../teacher_info_www; vi  ../teacher_info_www/grab_trial_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info_www-grab_trial_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			_role:	$('#id__role').val(),
			_userid:	$('#id__userid').val(),
			text:	$('#id_text').val(),
			time:	$('#id_time').val()
        });
    }


	$('#id__role').val(g_args._role);
	$('#id__userid').val(g_args._userid);
	$('#id_text').val(g_args.text);
	$('#id_time').val(g_args.time);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">_role</span>
                <input class="opt-change form-control" id="id__role" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">_userid</span>
                <input class="opt-change form-control" id="id__userid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">text</span>
                <input class="opt-change form-control" id="id_text" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">time</span>
                <input class="opt-change form-control" id="id_time" />
            </div>
        </div>
*/
