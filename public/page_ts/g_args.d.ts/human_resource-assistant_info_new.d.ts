interface GargsStatic {
	is_part_time:	number;
	rate_score:	number;
	assistantid:	number;
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
	nick	:any;
	assistant_type	:any;
	gender	:any;
	birth	:any;
	phone	:any;
	assign_lesson_count	:any;
	email	:any;
	rate_score	:any;
	assistantid	:any;
	school	:any;
	prize	:any;
	work_year	:any;
	gender_str	:any;
	ass_nick	:any;
	is_part_time	:any;
	age	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/assistant_info_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-assistant_info_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			is_part_time:	$('#id_is_part_time').val(),
			rate_score:	$('#id_rate_score').val(),
			assistantid:	$('#id_assistantid').val()
        });
    }


	$('#id_is_part_time').val(g_args.is_part_time);
	$('#id_rate_score').val(g_args.rate_score);
	$('#id_assistantid').val(g_args.assistantid);


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
                <span class="input-group-addon">rate_score</span>
                <input class="opt-change form-control" id="id_rate_score" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
*/
