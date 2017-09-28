interface GargsStatic {
	is_part_time:	number;
	ass_nick:	string;
	phone:	string;
	score:	number;
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
	nick	:any;
	assistant_type	:any;
	gender	:any;
	birth	:any;
	phone	:any;
	email	:any;
	rate_score	:any;
	assistantid	:any;
	school	:any;
	prize	:any;
	number	:any;
	ass_nick	:any;
	is_part_time	:any;
	age	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/assistant_info2.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-assistant_info2.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			is_part_time:	$('#id_is_part_time').val(),
			ass_nick:	$('#id_ass_nick').val(),
			phone:	$('#id_phone').val(),
			score:	$('#id_score').val()
        });
    }


	$('#id_is_part_time').val(g_args.is_part_time);
	$('#id_ass_nick').val(g_args.ass_nick);
	$('#id_phone').val(g_args.phone);
	$('#id_score').val(g_args.score);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_part_time</span>
                <input class="opt-change form-control" id="id_is_part_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">ass_nick</span>
                <input class="opt-change form-control" id="id_ass_nick" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">score</span>
                <input class="opt-change form-control" id="id_score" />
            </div>
        </div>
*/
