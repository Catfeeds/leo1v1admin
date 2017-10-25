/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-share_knowledge.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){
    $('.push').on("click",function(){
        $.do_ajax( "/ajax_deal2/push_share_knowledge",{

        },function(resp){
            if(resp.ret==0){
                alert(resp.data);
            }else{
                alert(resp.info);
            }
        } ) ;
    });



	  $('.opt-change').set_input_change_event(load_data);
});
