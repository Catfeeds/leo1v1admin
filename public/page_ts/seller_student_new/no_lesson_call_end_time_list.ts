/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-no_lesson_call_end_time_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            adminid:    $('#id_adminid').val(),
            phone:    $('#id_phone').val()
        });
    }

    $('#id_adminid').val(g_args.adminid);
    $('#id_phone').val(g_args.phone);
    $(".opt-telphone").on("click",function(){
        var me=this;
        var opt_data= $(this).get_opt_data();
        var phone    = ""+ opt_data.phone;
        phone=phone.split("-")[0];
        try{
            window.navigate(
                "app:1234567@"+phone+"");
        } catch(e){

        };
        $.do_ajax_t("/ss_deal/call_ytx_phone", {
            "phone": opt_data.phone
        } );
    });

    $(".opt-undo-test-lesson").on("click",function(){
        var me=this;
        var opt_data= $(this).get_opt_data();
        $.do_ajax("/seller_student_new/refresh_call_end",{
            "lessonid" : opt_data.lessonid,
            },function(ret){
                window.location.reload();
            });
    });

    $("#id_set_call_end_time").on("click",function(){
        var me=this;
        var opt_data= $(this).get_opt_data();
        $.do_ajax("/seller_student_new/set_call_end_time",{
            "lessonid" : opt_data.lessonid,
            },function(ret){
                window.location.reload();
            });
    });


    $('.opt-change').set_input_change_event(load_data);
});
