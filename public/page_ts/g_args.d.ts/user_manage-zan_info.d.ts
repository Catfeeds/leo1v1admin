interface GargsStatic {
	start_date:	string;
	end_date:	string;
	praise_type:	number;
	userid:	number;
	page_num:	number;
	lessonid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: string;
declare var g_adminid: string;
interface RowData {
	add_userid	:any;
	userid	:any;
	ts	:any;
	reason	:any;
	praise_num	:any;
	lessonid	:any;
	type	:any;
	name	:any;
	add_user_name	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/zan_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-zan_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			start_date:	$('#id_start_date').val(),
			end_date:	$('#id_end_date').val(),
			praise_type:	$('#id_praise_type').val(),
			userid:	$('#id_userid').val(),
			lessonid:	$('#id_lessonid').val()
        });
    }


	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_praise_type').val(g_args.praise_type);
	$('#id_userid').val(g_args.userid);
	$('#id_lessonid').val(g_args.lessonid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

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
                <span class="input-group-addon">praise_type</span>
                <input class="opt-change form-control" id="id_praise_type" />
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
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>
*/
