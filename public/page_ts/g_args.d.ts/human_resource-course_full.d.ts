interface GargsStatic {
	teacherid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
/*

tofile: 
	 mkdir -p ../human_resource ; vi  ../human_resource/course_full.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-course_full.d.ts" />

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
