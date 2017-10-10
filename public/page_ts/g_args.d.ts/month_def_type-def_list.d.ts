interface GargsStatic {
	page_num:	number;
	page_count:	number;
	month_def_type:	string;//枚举列表: \App\Enums\Emonth_def_type
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
	month_def_type	:any;
	def_time	:any;
	start_time	:any;
	end_time	:any;
	month_def_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../month_def_type; vi  ../month_def_type/def_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/month_def_type-def_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			month_def_type:	$('#id_month_def_type').val()
        });
    }


	$('#id_month_def_type').val(g_args.month_def_type);
	$.enum_multi_select( $('#id_month_def_type'), 'month_def_type', function(){load_data();} )


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">month_def_type</span>
                <input class="opt-change form-control" id="id_month_def_type" />
            </div>
        </div>
*/
