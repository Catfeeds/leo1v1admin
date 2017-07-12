interface GargsStatic {
	register_flag:	number;
	class_time:	number;
	type:	number;
	start_date:	string;
	end_date:	string;
	grade:	number;
	status:	number;
	trial_type:	number;
	page_num:	number;
	sys_operator_type:	number;
	book_user:	string;
	book_origin:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../user_book ; vi  ../user_book/phone_user_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_book-phone_user_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			register_flag:	$('#id_register_flag').val(),
			class_time:	$('#id_class_time').val(),
			type:	$('#id_type').val(),
			start_date:	$('#id_start_date').val(),
			end_date:	$('#id_end_date').val(),
			grade:	$('#id_grade').val(),
			status:	$('#id_status').val(),
			trial_type:	$('#id_trial_type').val(),
			sys_operator_type:	$('#id_sys_operator_type').val(),
			book_user:	$('#id_book_user').val(),
			book_origin:	$('#id_book_origin').val()
        });
    }


	$('#id_register_flag').val(g_args.register_flag);
	$('#id_class_time').val(g_args.class_time);
	$('#id_type').val(g_args.type);
	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_grade').val(g_args.grade);
	$('#id_status').val(g_args.status);
	$('#id_trial_type').val(g_args.trial_type);
	$('#id_sys_operator_type').val(g_args.sys_operator_type);
	$('#id_book_user').val(g_args.book_user);
	$('#id_book_origin').val(g_args.book_origin);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">register_flag</span>
                <input class="opt-change form-control" id="id_register_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">class_time</span>
                <input class="opt-change form-control" id="id_class_time" />
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
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">trial_type</span>
                <input class="opt-change form-control" id="id_trial_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sys_operator_type</span>
                <input class="opt-change form-control" id="id_sys_operator_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">book_user</span>
                <input class="opt-change form-control" id="id_book_user" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">book_origin</span>
                <input class="opt-change form-control" id="id_book_origin" />
            </div>
        </div>
*/
