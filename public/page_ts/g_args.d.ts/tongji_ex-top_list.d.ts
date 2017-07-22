interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	tongji_type:	number;//App\Enums\Etongji_type
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
	tongji_type	:any;
	logtime	:any;
	adminid	:any;
	value	:any;
	top_index	:any;
	top_index2	:any;
	admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ex; vi  ../tongji_ex/top_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ex-top_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
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
	$('#id_tongji_type').val(g_args.tongji_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">分类</span>
                <select class="opt-change form-control" id="id_tongji_type" >
                </select>
            </div>
        </div>
*/
