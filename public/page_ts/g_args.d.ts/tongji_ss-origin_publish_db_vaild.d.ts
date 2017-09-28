interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	origin_ex:	string;
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
	tq_called_count	:any;
	tq_no_call_count	:any;
	no_call_count	:any;
	assigned_count	:any;
	invalid_count	:any;
	no_connected_count	:any;
	have_intention_a_count	:any;
	have_intention_b_count	:any;
	have_intention_c_count	:any;
	tq_call_fail_count	:any;
	tq_call_succ_invalid_count	:any;
	title	:any;
	valid_count	:any;
	key1	:any;
	key2	:any;
	key3	:any;
	key4	:any;
	key1_class	:any;
	key2_class	:any;
	key3_class	:any;
	key4_class	:any;
	level	:any;
	create_time	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/origin_publish_db_vaild.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-origin_publish_db_vaild.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			origin_ex:	$('#id_origin_ex').val()
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
	$('#id_origin_ex').val(g_args.origin_ex);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_ex</span>
                <input class="opt-change form-control" id="id_origin_ex" />
            </div>
        </div>
*/
