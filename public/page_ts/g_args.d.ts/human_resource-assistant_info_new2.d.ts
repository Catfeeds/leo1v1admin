interface GargsStatic {
	is_part_time:	string;//枚举列表: \App\Enums\Eassistant_type
 	rate_score:	string;//枚举列表: \App\Enums\Estar_level
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
	email	:any;
	rate_score	:any;
	assistantid	:any;
	school	:any;
	prize	:any;
	ass_nick	:any;
	is_part_time	:any;
	gender_str	:any;
	age	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/assistant_info_new2.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-assistant_info_new2.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			is_part_time:	$('#id_is_part_time').val(),
			rate_score:	$('#id_rate_score').val(),
			assistantid:	$('#id_assistantid').val()
        });
    }


	$('#id_is_part_time').val(g_args.is_part_time);
	$.enum_multi_select( $('#id_is_part_time'), 'assistant_type', function(){load_data();} )
	$('#id_rate_score').val(g_args.rate_score);
	$.enum_multi_select( $('#id_rate_score'), 'star_level', function(){load_data();} )
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
