interface GargsStatic {
	id_textbook:	number;
	id_subject:	number;
	id_grade:	number;
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
	 mkdir -p ../question_new; vi  ../question_new/textbook_knowledge_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question_new-textbook_knowledge_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		id_textbook:	$('#id_id_textbook').val(),
		id_subject:	$('#id_id_subject').val(),
		id_grade:	$('#id_id_grade').val()
		});
}
$(function(){


	$('#id_id_textbook').val(g_args.id_textbook);
	$('#id_id_subject').val(g_args.id_subject);
	$('#id_id_grade').val(g_args.id_grade);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_textbook</span>
                <input class="opt-change form-control" id="id_id_textbook" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["id_textbook title", "id_textbook", "th_id_textbook" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_subject</span>
                <input class="opt-change form-control" id="id_id_subject" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["id_subject title", "id_subject", "th_id_subject" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_grade</span>
                <input class="opt-change form-control" id="id_id_grade" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["id_grade title", "id_grade", "th_id_grade" ]])!!}
*/
