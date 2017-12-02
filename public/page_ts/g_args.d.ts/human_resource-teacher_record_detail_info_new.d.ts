interface GargsStatic {
	teacherid:	number;
	type:	number;
	add_time:	number;
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
	 mkdir -p ../human_resource; vi  ../human_resource/teacher_record_detail_info_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_record_detail_info_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val(),
			type:	$('#id_type').val(),
			add_time:	$('#id_add_time').val()
        });
    }


	$('#id_teacherid').val(g_args.teacherid);
	$('#id_type').val(g_args.type);
	$('#id_add_time').val(g_args.add_time);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type</span>
                <input class="opt-change form-control" id="id_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">add_time</span>
                <input class="opt-change form-control" id="id_add_time" />
            </div>
        </div>
*/
