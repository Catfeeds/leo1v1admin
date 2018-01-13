/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-seller_student_ws.d.ts" />


$(function(){
    $.do_ajax("/seller_api/get_login_jump_key",{},function(resp){
        //var hostname="localhost";
        var hostname= window.location.hostname ;
        var ws = $.websocket("ws://" +hostname+ ":9501/", {
            events: {
                "bind_notify_seller_count_info": function (e) {
                    if (!e.login_flag) {
                        alert("登录失败");
                    }
                },
                "noti_seller_count": function (e) {
                    var data  = JSON.stringify(e);
                    $("#id_message" ).append("<div> " + data+  " </div> ");
                }
            }
            , open: function () {
                ws.send("bind_notify_seller_count_info",{
                    "adminid": resp.adminid,
                    "login_jump_key": resp.login_jump_key,
                });
            }
        });


    });



});
