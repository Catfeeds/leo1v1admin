/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-get_teaching_core_data.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){

    $(".custom").on("click",function(){
        var text = $(this).data("title");
        BootstrapDialog.alert("注释:"+text);

       // alert("备注:"+text);
    });


	$('.opt-change').set_input_change_event(load_data);
});

