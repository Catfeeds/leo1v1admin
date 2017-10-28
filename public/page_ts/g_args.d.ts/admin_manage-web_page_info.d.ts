interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	del_flag:	number;//\App\Enums\Eboolean
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
	web_page_id	:any;
	title	:any;
	url	:any;
	add_time	:any;
	add_adminid	:any;
	del_flag	:any;
	add_adminid_nick	:any;
	del_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../admin_manage; vi  ../admin_manage/web_page_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-web_page_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		del_flag:	$('#id_del_flag').val()
    });
}
$(function(){

	Enum_map.append_option_list("boolean",$("#id_del_flag"));

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
	$('#id_del_flag').val(g_args.del_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_del_flag" >
                </select>
            </div>
        </div>
*/
