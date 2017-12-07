/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_jack-test_ws.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){


    var ws = $.websocket("ws://" + window.location.hostname + ":8808/", {
        events: {
            "noti_order_payed": function (e) {
                alert("xxxx");
                /*
                $.ajax({
                    'url': "/wx_login/check",
                    'type': 'POST',
                    'data': {'admin_code': admin_code },
                    'dataType': 'json',
                    success: function(ret) {
                        if (ret.flag ) {
                            if ( $.query.get("to_url") ){
                                window.location.href= $.query.get("to_url") ;
                            }else{
                                window.location.reload();
                            }
                        }
                    }
                });
                */
            }
        },open : function () {
            alert("xxxx");
            ws.send("check_pay_order",{
                "userid": 10001 ,
                "sub_orderid":  88
            });
        }
    });


	$('.opt-change').set_input_change_event(load_data);
});


