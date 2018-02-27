interface GargsStatic {
	name:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	channel_id	:any;
	channel_name	:any;
	up_group_name	:any;
	group_name	:any;
	account	:any;
	main_type_class	:any;
	up_group_name_class	:any;
	group_name_class	:any;
	account_class	:any;
	level	:any;
}

/*

tofile: 
	 mkdir -p ../channel_manage; vi  ../channel_manage/admin_channel_manage.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/channel_manage-admin_channel_manage.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		name:	$('#id_name').val()
		});
}
$(function(){


	$('#id_name').val(g_args.name);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">name</span>
                <input class="opt-change form-control" id="id_name" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["name title", "name", "th_name" ]])!!}
*/
