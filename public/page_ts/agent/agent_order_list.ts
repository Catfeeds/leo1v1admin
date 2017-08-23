/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_order_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            orderid      : $('#orderid').val(),
            aid      : $('#aid').val(),
            pid      : $('#id_pid').val(),
            p_price  : $('#id_price').val(),
            ppid     : $('#id_ppid').val(),
            pp_price : $('#id_pp_price').val(),
        });
    }

    $("#id_add").on("click",function(){
        var $orderid      = $("<input/>");
        var $aid      = $("<input/>");
        var $pid      = $("<input/>");
        var $p_price  = $("<input/>");
        var $ppid     = $("<input/>");
        var $pp_price = $("<input/>");

        var arr=[
            ["orderid",  $orderid],
            ["aid",  $aid],
            ["上级转介绍id",  $pid],
            ["上级转介绍费",  $p_price],
            ["上上级转介绍id",  $ppid],
            ["上上级转介绍费",  $pp_price],
        ];
        $.show_key_value_table("新增转介绍订单", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_order_add",{
                    "orderid"      : $orderid.val(),
                    "aid"      : $aid.val(),
                    "pid"      : $pid.val(),
                    "p_price"  : $p_price.val(),
                    "ppid"     : $ppid.val(),
                    "pp_price" : $pp_price.val(),
                })
            }
        })
    });

    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();

        var $orderid  = $("<input/>");
        var $aid  = $("<input/>");
        var $pid      = $("<input/>");
        var $p_price  = $("<input/>");
        var $ppid     = $("<input/>");
        var $pp_price = $("<input/>");

        $orderid.val(opt_data.orderid);
        $aid.val(opt_data.aid);
        $pid.val(opt_data.pid);
        $p_price.val(opt_data.p_price);
        $ppid.val(opt_data.ppid);
        $pp_price.val(opt_data.pp_price);
        var arr=[
            ["orderid",  $orderid],
            // ["aid",  $aid],
            // ["pid",  $pid],
            ["上级转介绍费",  $p_price],
            // ["ppid",  $ppid],
            ["上上级转介绍费",  $pp_price],
        ];

        $.show_key_value_table("修改信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_order_edit",{
                    "orderid":opt_data.orderid,
                    // "aid":$aid.val(),
                    // "orderid_new":$orderid.val(),
                    // "pid" : $pid.val() ,
                    "p_price" : $p_price.val() ,
                    // "ppid" : $ppid.val(),
                    "pp_price" : $pp_price.val()
                })
            }
        })
    });


    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除订单id为:" + opt_data.orderid + "的代理吗？",
            function(val) {
                if (val) {
                    $.do_ajax("/ajax_deal/agent_order_del", {
                        "orderid": opt_data.orderid
                    })
                }
            })
    });

    //点击进入个人主页
    $('.opt-user').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open(
            '/stu_manage?sid='+ opt_data.userid +"&return_url="+ encodeURIComponent(window.location.href)
        );
    });

    $('.opt-change').set_input_change_event(load_data);
});
