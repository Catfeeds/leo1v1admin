/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-notify_phone.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }

    $("#id_notify_phone") .on("click",function(){
        var phone=  $.trim( $("#id_phone").val());

        try{
            window.navigate(
                "app:1234567@"+phone+"");
        } catch(e){

        };
        $.do_ajax_t("/ss_deal/call_ytx_phone", {
            "phone": phone
        } );
    });


});


