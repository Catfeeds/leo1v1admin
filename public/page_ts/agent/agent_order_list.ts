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

        var $orderid  = $("<input disabled='disabled' />");
        var $aid  = $("<input/>");
        var $pid      = $("<input/>");
        var $p_price  = $("<input/>");
        var $p_level      = $("<select><option value='0'>无</option><option value='1'>黄金</option><option value='2'>水晶</option><select/>");
        var $ppid     = $("<input/>");
        var $pp_price = $("<input/>");
        var $pp_level      = $("<select><option value='0'>无</option><option value='1'>黄金</option><option value='2'>水晶</option><select/>");

        $orderid.val(opt_data.orderid);
        $aid.val(opt_data.aid);
        $pid.val(opt_data.pid);
        $p_price.val(opt_data.p_price);
        $p_level.val(opt_data.p_level);
        $ppid.val(opt_data.ppid);
        $pp_price.val(opt_data.pp_price);
        $pp_level.val(opt_data.pp_level);
        var arr=[
            ["orderid",  $orderid],
            ["上级转介绍费",  $p_price],
            ["订单确认时上级当前等级",  $p_level],
            ["上上级转介绍费",  $pp_price],
            ["订单确认时上上级当前等级",  $pp_level],
        ];

        $.show_key_value_table("修改信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_order_edit",{
                    "orderid":opt_data.orderid,
                    "p_price" : $p_price.val() ,
                    "p_level" : $p_level.val() ,
                    "pp_price" : $pp_price.val(),
                    "pp_level" : $pp_level.val() ,
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
            '/stu_manage/lesson_record?sid='+ opt_data.userid +"&return_url="+ encodeURIComponent(window.location.href)
        );
    });

    $('.opt-change').set_input_change_event(load_data);
});
