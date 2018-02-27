interface GargsStatic {
	page_num:	number;
	page_count:	number;
	assistantid:	number;
	campus_id:	number;
	groupid:	number;
	leader_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/get_ass_tran_to_seller_detail_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-get_ass_tran_to_seller_detail_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		assistantid:	$('#id_assistantid').val(),
		campus_id:	$('#id_campus_id').val(),
		groupid:	$('#id_groupid').val(),
		leader_flag:	$('#id_leader_flag').val()
		});
}
$(function(){


	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_campus_id').val(g_args.campus_id);
	$('#id_groupid').val(g_args.groupid);
	$('#id_leader_flag').val(g_args.leader_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">campus_id</span>
                <input class="opt-change form-control" id="id_campus_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["campus_id title", "campus_id", "th_campus_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["groupid title", "groupid", "th_groupid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">leader_flag</span>
                <input class="opt-change form-control" id="id_leader_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["leader_flag title", "leader_flag", "th_leader_flag" ]])!!}
*/
