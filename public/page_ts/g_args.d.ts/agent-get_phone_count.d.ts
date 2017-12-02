interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	phone:	string;
	is_called_phone:	number;//App\Enums\Eboolean
	uid:	number;
	page_num:	number;
	page_count:	number;
	seller_student_status:	string;//枚举列表: \App\Enums\Eseller_student_status
 	agent_type:	number;
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
	uid	:any;
	phone	:any;
	start_time	:any;
	end_time	:any;
	duration	:any;
	is_called_phone	:any;
	record_url	:any;
	account	:any;
	seller_student_status	:any;
	load_wav_self_flag	:any;
	is_called_phone_str	:any;
	seller_student_status_str	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/get_phone_count.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-get_phone_count.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			phone:	$('#id_phone').val(),
			is_called_phone:	$('#id_is_called_phone').val(),
			uid:	$('#id_uid').val(),
			seller_student_status:	$('#id_seller_student_status').val(),
			agent_type:	$('#id_agent_type').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_is_called_phone"));

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
	$('#id_phone').val(g_args.phone);
	$('#id_is_called_phone').val(g_args.is_called_phone);
	$('#id_uid').val(g_args.uid);
	$('#id_seller_student_status').val(g_args.seller_student_status);
	$.enum_multi_select( $('#id_seller_student_status'), 'seller_student_status', function(){load_data();} )
	$('#id_agent_type').val(g_args.agent_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_is_called_phone" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">uid</span>
                <input class="opt-change form-control" id="id_uid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_student_status</span>
                <input class="opt-change form-control" id="id_seller_student_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">agent_type</span>
                <input class="opt-change form-control" id="id_agent_type" />
            </div>
        </div>
*/
