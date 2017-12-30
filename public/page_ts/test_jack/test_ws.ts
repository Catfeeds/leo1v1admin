/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_jack-test_ws.d.ts" />
$(function(){
    var url  = window.location.hostname;
    console.log(url);
    var ws = $.websocket("ws://" + url + ":8808/", {
        events: {
            "noti_order_payed": function (e) {
                alert("结束");
                console.log(e);
            }
        },open : function () {
            alert("开始");
            ws.send("check_pay_order",{
                "userid"      : 10001 ,
                "sub_orderid" :  88
            });
        }
    });
});


