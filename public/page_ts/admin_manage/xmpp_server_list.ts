/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-xmpp_server_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ({

        });
    }


    $("#id_add").on("click",function(){
        var $ip=$("<input/>");
        var $server_name=$("<input/>");
        var $server_desc=$("<input/>");
        var $websocket_port=$("<input/>");
        var $xmpp_port=$("<input/>");
        var $webrtc_port=$("<input/>");
        var $weights=$("<input/>");
        var arr=[
            ["服务器名称", $server_name],
            ["服务器说明", $server_desc],
            ["ip", $ip],
            ["weights", $weights],
            ["xmpp_port", $xmpp_port],
            ["webrtc_port", $webrtc_port],
            ["websocket_port", $websocket_port],
        ];
        $xmpp_port.val("5222");
        $webrtc_port.val("5061");
        $websocket_port.val("20061");
        $weights.val(0);

        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal2/xmpp_server_add",{
                    ip: $ip.val(),
                    server_name: $server_name.val(),
                    server_desc: $server_desc.val(),
                    "weights": $weights.val(),
                    "xmpp_port": $xmpp_port.val(),
                    "webrtc_port": $webrtc_port.val(),
                    "websocket_port": $websocket_port.val(),

                });
            }
        });
    });

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var $weights=$("<input/>");
        var arr=[
            ["服务器名称",  opt_data.server_name],
            ["服务器说明", opt_data.server_desc],
            ["ip",  opt_data.ip],
            ["weights", $weights],
        ];
        $weights.val(opt_data.weights);

        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal2/xmpp_server_set",{
                    "id" : opt_data.id,
                    "weights" : $weights.val() ,
                });
            }
        });
    });

    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除 "+ opt_data.server_name + ":" + opt_data.server_desc ,
            function(val){
                if (val) {
                    $.do_ajax("/ajax_deal2/xmpp_server_del",{
                        "id" : opt_data.id,
                    });
                }
            });

    });



    $('.opt-change').set_input_change_event(load_data);
});
