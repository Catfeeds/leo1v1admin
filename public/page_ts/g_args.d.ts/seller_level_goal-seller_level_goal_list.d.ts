interface GargsStatic {
	seller_level:	number;
	level_goal:	number;
	level_face:	string;
	level_icon:	string;
	num:	number;
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
	seller_level	:any;
	level_goal	:any;
	level_face	:any;
	create_time	:any;
	num	:any;
	level_icon	:any;
	seller_level_goal	:any;
	seller_level_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_level_goal; vi  ../seller_level_goal/seller_level_goal_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_level_goal-seller_level_goal_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		seller_level:	$('#id_seller_level').val(),
		level_goal:	$('#id_level_goal').val(),
		level_face:	$('#id_level_face').val(),
		level_icon:	$('#id_level_icon').val(),
		num:	$('#id_num').val()
    });
}
$(function(){


	$('#id_seller_level').val(g_args.seller_level);
	$('#id_level_goal').val(g_args.level_goal);
	$('#id_level_face').val(g_args.level_face);
	$('#id_level_icon').val(g_args.level_icon);
	$('#id_num').val(g_args.num);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_level</span>
                <input class="opt-change form-control" id="id_seller_level" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">level_goal</span>
                <input class="opt-change form-control" id="id_level_goal" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">level_face</span>
                <input class="opt-change form-control" id="id_level_face" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">level_icon</span>
                <input class="opt-change form-control" id="id_level_icon" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">num</span>
                <input class="opt-change form-control" id="id_num" />
            </div>
        </div>
*/
