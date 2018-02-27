interface GargsStatic {
	nickname:	number;
	phone:	number;
	id:	number;
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
	 mkdir -p ../agent; vi  ../agent/user_center_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-user_center_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		nickname:	$('#id_nickname').val(),
		phone:	$('#id_phone').val(),
		id:	$('#id_id').val()
		});
}
$(function(){


	$('#id_nickname').val(g_args.nickname);
	$('#id_phone').val(g_args.phone);
	$('#id_id').val(g_args.id);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">nickname</span>
                <input class="opt-change form-control" id="id_nickname" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["nickname title", "nickname", "th_nickname" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["phone title", "phone", "th_phone" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id</span>
                <input class="opt-change form-control" id="id_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["id title", "id", "th_id" ]])!!}
*/
