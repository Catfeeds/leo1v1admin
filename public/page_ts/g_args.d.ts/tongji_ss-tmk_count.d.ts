interface GargsStatic {
	origin:	string;
	origin_ex:	string;
	seller_groupid_ex:	string;
	admin_revisiterid:	number;
	groupid:	number;
	origin_level:	string;//枚举列表: \App\Enums\Eorigin_level
 	wx_invaild_flag:	number;//\App\Enums\Eboolean
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	check_value	:any;
	all_count	:any;
	assigned_count	:any;
	tq_called_count	:any;
	tq_no_call_count	:any;
	no_call_count	:any;
	invalid_count	:any;
	no_connected_count	:any;
	have_intention_a_count	:any;
	have_intention_b_count	:any;
	have_intention_c_count	:any;
	tq_call_fail_count	:any;
	tmk_assigned_count	:any;
	title	:any;
	new_user_count	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/tmk_count.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-tmk_count.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			origin:	$('#id_origin').val(),
			origin_ex:	$('#id_origin_ex').val(),
			seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
			admin_revisiterid:	$('#id_admin_revisiterid').val(),
			groupid:	$('#id_groupid').val(),
			origin_level:	$('#id_origin_level').val(),
			wx_invaild_flag:	$('#id_wx_invaild_flag').val(),
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_wx_invaild_flag"));

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
	$('#id_origin').val(g_args.origin);
	$('#id_origin_ex').val(g_args.origin_ex);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);
	$('#id_groupid').val(g_args.groupid);
	$('#id_origin_level').val(g_args.origin_level);
	$.enum_multi_select( $('#id_origin_level'), 'origin_level', function(){load_data();} )
	$('#id_wx_invaild_flag').val(g_args.wx_invaild_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_ex</span>
                <input class="opt-change form-control" id="id_origin_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">admin_revisiterid</span>
                <input class="opt-change form-control" id="id_admin_revisiterid" />
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
                <span class="input-group-addon">origin_level</span>
                <input class="opt-change form-control" id="id_origin_level" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_wx_invaild_flag" >
                </select>
            </div>
        </div>
*/
