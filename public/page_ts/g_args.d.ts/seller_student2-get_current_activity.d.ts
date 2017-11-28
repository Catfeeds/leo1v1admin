interface GargsStatic {
	id_open_flag:	number;
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
	id	:any;
	title	:any;
	power_value	:any;
	open_flag	:any;
	date_range_start	:any;
	date_range_end	:any;
	open_flag_str	:any;
	date_range_time	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student2; vi  ../seller_student2/get_current_activity.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student2-get_current_activity.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		id_open_flag:	$('#id_id_open_flag').val()
    });
}
$(function(){


	$('#id_id_open_flag').val(g_args.id_open_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_open_flag</span>
                <input class="opt-change form-control" id="id_id_open_flag" />
            </div>
        </div>
*/
