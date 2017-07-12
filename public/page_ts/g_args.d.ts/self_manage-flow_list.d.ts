interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	post_adminid:	number;
	flow_check_flag:	number;//App\Enums\Eflow_check_flag
	flow_type:	number;//App\Enums\Eflow_type
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
	flowid	:any;
	nodeid	:any;
	node_type	:any;
	add_time	:any;
	flow_check_flag	:any;
	check_msg	:any;
	check_time	:any;
	adminid	:any;
	flow_status	:any;
	post_adminid	:any;
	post_time	:any;
	post_msg	:any;
	flow_type	:any;
	from_key_int	:any;
	from_key_str	:any;
	from_key2_int	:any;
	flow_type_str	:any;
	node_name	:any;
	post_admin_nick	:any;
	line_data	:any;
	flow_check_flag_str	:any;
	flow_status_str	:any;
}

/*

tofile: 
	 mkdir -p ../self_manage; vi  ../self_manage/flow_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-flow_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			post_adminid:	$('#id_post_adminid').val(),
			flow_check_flag:	$('#id_flow_check_flag').val(),
			flow_type:	$('#id_flow_type').val()
        });
    }

	Enum_map.append_option_list("flow_check_flag",$("#id_flow_check_flag"));
	Enum_map.append_option_list("flow_type",$("#id_flow_type"));

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
	$('#id_post_adminid').val(g_args.post_adminid);
	$('#id_flow_check_flag').val(g_args.flow_check_flag);
	$('#id_flow_type').val(g_args.flow_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">post_adminid</span>
                <input class="opt-change form-control" id="id_post_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">审核</span>
                <select class="opt-change form-control" id="id_flow_check_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">类型</span>
                <select class="opt-change form-control" id="id_flow_type" >
                </select>
            </div>
        </div>
*/
