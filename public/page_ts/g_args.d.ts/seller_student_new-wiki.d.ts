interface GargsStatic {
	wiki_key:	string;
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
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/wiki.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-wiki.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			wiki_key:	$('#id_wiki_key').val()
        });
    }


	$('#id_wiki_key').val(g_args.wiki_key);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">wiki_key</span>
                <input class="opt-change form-control" id="id_wiki_key" />
            </div>
        </div>
*/
