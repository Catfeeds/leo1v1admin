interface GargsStatic {
	editType:	number;
	knowledge_id:	number;
	level:	number;
	father_id:	number;
	father_subject:	number;
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
	 mkdir -p ../question_new; vi  ../question_new/knowledge_edit.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question_new-knowledge_edit.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		editType:	$('#id_editType').val(),
		knowledge_id:	$('#id_knowledge_id').val(),
		level:	$('#id_level').val(),
		father_id:	$('#id_father_id').val(),
		father_subject:	$('#id_father_subject').val()
		});
}
$(function(){


	$('#id_editType').val(g_args.editType);
	$('#id_knowledge_id').val(g_args.knowledge_id);
	$('#id_level').val(g_args.level);
	$('#id_father_id').val(g_args.father_id);
	$('#id_father_subject').val(g_args.father_subject);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">editType</span>
                <input class="opt-change form-control" id="id_editType" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["editType title", "editType", "th_editType" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">knowledge_id</span>
                <input class="opt-change form-control" id="id_knowledge_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["knowledge_id title", "knowledge_id", "th_knowledge_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">level</span>
                <input class="opt-change form-control" id="id_level" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["level title", "level", "th_level" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">father_id</span>
                <input class="opt-change form-control" id="id_father_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["father_id title", "father_id", "th_father_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">father_subject</span>
                <input class="opt-change form-control" id="id_father_subject" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["father_subject title", "father_subject", "th_father_subject" ]])!!}
*/
