/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-notify_phone.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }

    $("#id_notify_phone") .on("click",function(){
        //同步...
        var lesson_info = JSON.stringify({
            cmd: "noti_phone",
            phone: $.trim($("#id_phone" ).val() )
        });
        $.ajax({
            type: "get",
            url: "http://admin.yb1v1.com:9501/pc_phone_noti_user_lesson_info",
            dataType: "text",
            data: {
                'username': g_account,
                "lesson_info": lesson_info
            }
        });

    });


});


