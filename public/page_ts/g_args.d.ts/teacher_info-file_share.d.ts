interface GargsStatic {
	sign:	string;
	dir:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	is_dir	:any;
	file_name	:any;
	abs_path	:any;
	file_size	:any;
	create_time	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_info; vi  ../teacher_info/file_share.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-file_share.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			sign:	$('#id_sign').val(),
			dir:	$('#id_dir').val()
        });
    }


	$('#id_sign').val(g_args.sign);
	$('#id_dir').val(g_args.dir);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sign</span>
                <input class="opt-change form-control" id="id_sign" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">dir</span>
                <input class="opt-change form-control" id="id_dir" />
            </div>
        </div>
*/
