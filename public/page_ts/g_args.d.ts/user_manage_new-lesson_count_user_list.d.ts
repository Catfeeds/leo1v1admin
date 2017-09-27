interface GargsStatic {
	start_time:	string;
	end_time:	string;
	lesson_count_start:	number;
	lesson_count_end:	number;
	assistantid:	number;
	type:	number;
	grade:	number;//App\Enums\Egrade
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
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/lesson_count_user_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-lesson_count_user_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			lesson_count_start:	$('#id_lesson_count_start').val(),
			lesson_count_end:	$('#id_lesson_count_end').val(),
			assistantid:	$('#id_assistantid').val(),
			type:	$('#id_type').val(),
			grade:	$('#id_grade').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade"));

	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);
	$('#id_lesson_count_start').val(g_args.lesson_count_start);
	$('#id_lesson_count_end').val(g_args.lesson_count_end);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_type').val(g_args.type);
	$('#id_grade').val(g_args.grade);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_time</span>
                <input class="opt-change form-control" id="id_start_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_time</span>
                <input class="opt-change form-control" id="id_end_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_count_start</span>
                <input class="opt-change form-control" id="id_lesson_count_start" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_count_end</span>
                <input class="opt-change form-control" id="id_lesson_count_end" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type</span>
                <input class="opt-change form-control" id="id_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>
*/
