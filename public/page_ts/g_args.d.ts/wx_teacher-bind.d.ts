interface GargsStatic {
	openid:	string;
	url:	string;
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
	 mkdir -p ../wx_teacher; vi  ../wx_teacher/bind.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/wx_teacher-bind.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			openid:	$('#id_openid').val(),
			url:	$('#id_url').val()
        });
    }


	$('#id_openid').val(g_args.openid);
	$('#id_url').val(g_args.url);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">openid</span>
                <input class="opt-change form-control" id="id_openid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">url</span>
                <input class="opt-change form-control" id="id_url" />
            </div>
        </div>
*/
