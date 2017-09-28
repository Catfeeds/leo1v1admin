interface GargsStatic {
	teacherid:	number;
	grade:	number;
	subject:	number;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
/*

tofile: 
	 mkdir -p ../human_resource ; vi  ../human_resource/preview.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-preview.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val(),
			grade:	$('#id_grade').val(),
			subject:	$('#id_subject').val()
        });
    }

	$('#id_teacherid').val(g_args.teacherid);
	$('#id_grade').val(g_args.grade);
	$('#id_subject').val(g_args.subject);


	$('.opt-change').set_input_change_event(load_data);
});



*/
