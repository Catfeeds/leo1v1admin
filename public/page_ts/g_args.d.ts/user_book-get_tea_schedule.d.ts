interface GargsStatic {
	start_date:	string;
	end_date:	string;
	phone:	string;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: string;
declare var g_adminid: string;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../user_book; vi  ../user_book/get_tea_schedule.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_book-get_tea_schedule.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			start_date:	$('#id_start_date').val(),
			end_date:	$('#id_end_date').val(),
			phone:	$('#id_phone').val()
        });
    }


	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_phone').val(g_args.phone);


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
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>
*/
