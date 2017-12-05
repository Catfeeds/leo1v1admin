interface GargsStatic {
	teacherid:	number;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	nick	:any;
	realname	:any;
	teacher_type	:any;
	gender	:any;
	birth	:any;
	phone	:any;
	email	:any;
	rate_score	:any;
	teacherid	:any;
	user_agent	:any;
	teacher_tags	:any;
	teacher_textbook	:any;
	create_meeting	:any;
	level	:any;
	work_year	:any;
	teacher_type_str	:any;
	gender_str	:any;
	age	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource ; vi  ../human_resource/index_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-index_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val()
        });
    }

	$('#id_teacherid').val(g_args.teacherid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
