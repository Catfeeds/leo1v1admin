interface GargsStatic {
	cur_require_adminid:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	origin_userid_flag:	number;//App\Enums\Eboolean 
	require_admin_type:	number;//App\Enums\Eaccount_role 
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	test_lesson_order_fail_flag	:any;
	count	:any;
	test_lesson_order_fail_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/order_fail_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-order_fail_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			cur_require_adminid:	$('#id_cur_require_adminid').val(),
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			origin_userid_flag:	$('#id_origin_userid_flag').val(),
			require_admin_type:	$('#id_require_admin_type').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_origin_userid_flag"));
	Enum_map.append_option_list("account_role",$("#id_require_admin_type"));

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
	$('#id_cur_require_adminid').val(g_args.cur_require_adminid);
	$('#id_origin_userid_flag').val(g_args.origin_userid_flag);
	$('#id_require_admin_type').val(g_args.require_admin_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cur_require_adminid</span>
                <input class="opt-change form-control" id="id_cur_require_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_origin_userid_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <select class="opt-change form-control" id="id_require_admin_type" >
                </select>
            </div>
        </div>
*/
