interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	test_seller_id:	number;
	groupid:	number;
	self_groupid:	number;
	is_group_leader_flag:	number;
	tongji_type:	number;//App\Enums\Etongji_type
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	sys_operator	:any;
	all_price	:any;
	all_count	:any;
	index	:any;
}

/*

tofile: 
	 mkdir -p ../main_page; vi  ../main_page/seller.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-seller.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			test_seller_id:	$('#id_test_seller_id').val(),
			groupid:	$('#id_groupid').val(),
			self_groupid:	$('#id_self_groupid').val(),
			is_group_leader_flag:	$('#id_is_group_leader_flag').val(),
			tongji_type:	$('#id_tongji_type').val()
        });
    }

	Enum_map.append_option_list("tongji_type",$("#id_tongji_type"));

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
	$('#id_test_seller_id').val(g_args.test_seller_id);
	$('#id_groupid').val(g_args.groupid);
	$('#id_self_groupid').val(g_args.self_groupid);
	$('#id_is_group_leader_flag').val(g_args.is_group_leader_flag);
	$('#id_tongji_type').val(g_args.tongji_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_seller_id</span>
                <input class="opt-change form-control" id="id_test_seller_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">self_groupid</span>
                <input class="opt-change form-control" id="id_self_groupid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_group_leader_flag</span>
                <input class="opt-change form-control" id="id_is_group_leader_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">分类</span>
                <select class="opt-change form-control" id="id_tongji_type" >
                </select>
            </div>
        </div>
*/
