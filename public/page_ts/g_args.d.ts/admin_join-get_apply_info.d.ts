interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	user_name:	string;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	phone	:any;
	add_time	:any;
	name	:any;
	education	:any;
	residence	:any;
	gender	:any;
	english	:any;
	polity	:any;
	carded	:any;
	marry	:any;
	child	:any;
	email	:any;
	post	:any;
	dept	:any;
	address	:any;
	strong	:any;
	interest	:any;
	non_compete	:any;
	is_labor	:any;
	work_info	:any;
	family_info	:any;
	is_fre	:any;
	fre_name	:any;
	education_info	:any;
	birth	:any;
}

/*

tofile: 
	 mkdir -p ../admin_join ; vi  ../admin_join/get_apply_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_join-get_apply_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			user_name:	$('#id_user_name').val()
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
	$('#id_user_name').val(g_args.user_name);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
            </div>
        </div>
*/
