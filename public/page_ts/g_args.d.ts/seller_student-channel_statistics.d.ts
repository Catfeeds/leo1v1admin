interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	origin:	string;
	origin_ex:	string;
	admin_revisiterid:	number;
	groupid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: string;
declare var g_adminid: string;
interface RowData {
	al_count	:any;
	revisited_yi	:any;
	revisited_wei	:any;
	revisited_wuxiao	:any;
	no_call	:any;
	effective_a	:any;
	effective_b	:any;
	effective_c	:any;
	listened_dai	:any;
	listened_wei	:any;
	listened_yi	:any;
	reservation	:any;
	revisited_yipai	:any;
	revisited_yhf	:any;
	listen_dai	:any;
	listen_que	:any;
	listen_cannot	:any;
	listen_refuse	:any;
	money_all	:any;
	first_money	:any;
	key1	:any;
	key2	:any;
	key3	:any;
	key4	:any;
	key1_class	:any;
	key2_class	:any;
	key3_class	:any;
	key4_class	:any;
	level	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student; vi  ../seller_student/channel_statistics.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-channel_statistics.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			origin:	$('#id_origin').val(),
			origin_ex:	$('#id_origin_ex').val(),
			admin_revisiterid:	$('#id_admin_revisiterid').val(),
			groupid:	$('#id_groupid').val()
        });
    }


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
	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);
	$('#id_groupid').val(g_args.groupid);


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
*/
