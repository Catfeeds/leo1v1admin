interface GargsStatic {
	teacherid:	number;
	dir:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	is_dir	:any;
	file_name	:any;
	abs_path	:any;
	file_size	:any;
	create_time	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_info_admin; vi  ../teacher_info_admin/file_store.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info_admin-file_store.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		teacherid:	$('#id_teacherid').val(),
		dir:	$('#id_dir').val()
		});
}
$(function(){


	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_dir').val(g_args.dir);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">dir</span>
                <input class="opt-change form-control" id="id_dir" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["dir title", "dir", "th_dir" ]])!!}
*/
