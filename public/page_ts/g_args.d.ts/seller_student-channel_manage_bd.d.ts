interface GargsStatic {
	key1:	string;
	key2:	string;
	key3:	string;
	key4:	string;
	value:	string;
	origin_level:	number;
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
	key1	:any;
	key2	:any;
	key3	:any;
	key4	:any;
	value	:any;
	origin_level	:any;
	create_time	:any;
	origin_level_str	:any;
	create_time_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student; vi  ../seller_student/channel_manage_bd.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-channel_manage_bd.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			key1:	$('#id_key1').val(),
			key2:	$('#id_key2').val(),
			key3:	$('#id_key3').val(),
			key4:	$('#id_key4').val(),
			value:	$('#id_value').val(),
			origin_level:	$('#id_origin_level').val()
        });
    }


	$('#id_key1').val(g_args.key1);
	$('#id_key2').val(g_args.key2);
	$('#id_key3').val(g_args.key3);
	$('#id_key4').val(g_args.key4);
	$('#id_value').val(g_args.value);
	$('#id_origin_level').val(g_args.origin_level);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key1</span>
                <input class="opt-change form-control" id="id_key1" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key2</span>
                <input class="opt-change form-control" id="id_key2" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key3</span>
                <input class="opt-change form-control" id="id_key3" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key4</span>
                <input class="opt-change form-control" id="id_key4" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">value</span>
                <input class="opt-change form-control" id="id_value" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_level</span>
                <input class="opt-change form-control" id="id_origin_level" />
            </div>
        </div>
*/
