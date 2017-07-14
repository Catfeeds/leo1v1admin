interface GargsStatic {
	cc_name:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	cc_name	:any;
	sum	:any;
}

/*

tofile: 
	 mkdir -p ../tongji; vi  ../tongji/seller_week_lesson.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-seller_week_lesson.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			cc_name:	$('#id_cc_name').val()
        });
    }


	$('#id_cc_name').val(g_args.cc_name);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cc_name</span>
                <input class="opt-change form-control" id="id_cc_name" />
            </div>
        </div>
*/
