interface GargsStatic {
	phone:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../admin_join; vi  ../admin_join/file_print_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_join-file_print_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			phone:	$('#id_phone').val()
        });
    }


	$('#id_phone').val(g_args.phone);


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
*/
