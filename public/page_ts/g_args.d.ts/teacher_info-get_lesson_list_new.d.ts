interface GargsStatic {
	userid:	number;
	start_date:	string;
	end_date:	string;
	lesson_type:	number;
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
	 mkdir -p ../teacher_info; vi  ../teacher_info/get_lesson_list_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_lesson_list_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			userid:	$('#id_userid').val(),
			start_date:	$('#id_start_date').val(),
			end_date:	$('#id_end_date').val(),
			lesson_type:	$('#id_lesson_type').val()
        });
    }


	$('#id_userid').val(g_args.userid);
	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_lesson_type').val(g_args.lesson_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_date</span>
                <input class="opt-change form-control" id="id_start_date" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_date</span>
                <input class="opt-change form-control" id="id_end_date" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_type</span>
                <input class="opt-change form-control" id="id_lesson_type" />
            </div>
        </div>
*/
