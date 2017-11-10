/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_cash_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            cash:    $('#id_cash').val(),
            phone:    $('#id_phone').val(),
            type:    $('#id_type').val(),
            nickname:    $('#id_nickname').val(),
            cash_range:	$('#id_cash_range').val(),
            check_money_admin_nick:	$('#id_check_money_admin_nick').val(),
            date_type:	$('#id_date_type').val(),
            aid:    $('#id_aid').val(),
            agent_check_money_flag:	$('#id_agent_check_money_flag').val()
        });
    }

    Enum_map.append_option_list("agent_check_money_flag",$("#id_agent_check_money_flag"));

    $('#id_phone').val(g_args.phone);
    $('#id_cash').val(g_args.cash);
    $('#id_type').val(g_args.type);
    $('#id_aid').val(g_args.aid);
    $('#id_nickname').val(g_args.nickname);
    $('#id_cash_range').val(g_args.cash_range);
    $('#id_check_money_admin_nick').val(g_args.check_money_admin_nick);
    $('#id_agent_check_money_flag').val(g_args.agent_check_money_flag);

    $("#id_add").on("click",function(){
        var $aid  = $("<input/>");
        var $cash = $("<input/>");
        var $type = $("<select><option value='0'>请选择</option><option value='1'>银行卡</option><option value='2'>支付宝</option></select>");

        var arr=[
            ["转介绍id",  $aid],
            ["提现金额",  $cash],
            ["提现类型",  $type],
        ];
        $.show_key_value_table("新增数据", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_cash_add",{
                    "aid"  : $aid.val(),
                    "cash" : $cash.val(),
                    "type" : $type.val(),
                })
            }
        })
    });


    $(".opt-money-check").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $check_money_flag = $("<select/>");
        var $check_money_desc = $("<textarea rows='' cols=''>");
        Enum_map.append_option_list("agent_check_money_flag",$check_money_flag ,true );
        $check_money_flag.val(opt_data.check_money_flag);
        $check_money_desc.val(opt_data.check_money_desc);
        var arr=[
            ["财务审核",  $check_money_flag],
            ["财务审核说明",  $check_money_desc],
        ];

        $.show_key_value_table("财务确认", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_cash_edit",{
                    "id":opt_data.id,
                    "check_money_flag" : $check_money_flag.val(),
                    "check_money_desc" : $check_money_desc.val()
                })
            }
        })
    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除id为:" + opt_data.id + "的数据吗？",
            function(val) {
                if (val) {
                    $.do_ajax("/ajax_deal/agent_cash_del", {
                        "id": opt_data.id
                    })
                }
            })
    });


    $(".opt-wechat-desc").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/agent/agent_user_wechat?id="+ opt_data.aid);
    });


    $(".opt-user-link").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/agent/agent_user_link?id="+ opt_data.aid);
    });




    $('.opt-change').set_input_change_event(load_data);
    $('.opt-freeze').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var $freeze_money = $("<input/>");
        var $phone = $("<input/>")
        var $agent_freeze_type= $("<select id='id_agent_freeze_type'/>" );
        Enum_map.append_option_list("agent_freeze_type", $agent_freeze_type,true);
        var $agent_money_ex_type= $("<select/>" );
        Enum_map.append_option_list("agent_money_ex_type", $agent_money_ex_type,true);
        // var $agent_activity_time = $("<div id='id_date_range'></div>");
        var $agent_activity_time = $("<input/>");
        
        $(".table").append($agent_activity_time);
        // $('#id_date_range').select_date_range({
        //     'date_type' : g_args.date_type,
        //     'opt_date_type' : g_args.opt_date_type,
        //     'start_time'    : g_args.start_time,
        //     'end_time'      : g_args.end_time,
        //     date_type_config : JSON.parse( g_args.date_type_config),
        //     onQuery :function() {
        //         load_data();
        //     }
        // });    
        var arr=[
            ["冻结金额" ,$freeze_money ],
            ["冻结类型" ,$agent_freeze_type ],
            ["违规学员手机号",$phone],
            ["活动类型",$agent_money_ex_type],
            ["活动日期",$agent_activity_time]
        ] ;
        // console.log($agent_freeze_type.val());
        // if($agent_freeze_type.val() == 1 || $agent_freeze_type.val() == 2)
        //     arr.push(["违规学员手机号",$phone]);
        // else if($agent_freeze_type.val() == 2){
        //     arr.push(["违规学员手机号",$phone]);
        //     arr.push(["活动名称",$agent_money_ex_type]);

        // }else
        //     arr.push(["违规学员手机号",$phone]);

        $.show_key_value_table("冻结体现金额", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/agent/agent_money_freeze",{
                    "id" : opt_data.id,
                    "freeze_money" : $freeze_money.val(),
                    "agent_freeze_type" : $agent_freeze_type.val(),
                    "phone" : $phone.val(),
                    "agent_money_ex_type" : $agent_money_ex_type.val(),
                    "agent_activity_time" : $agent_activity_time.val(),
                    "cash" : opt_data.cash,
                    "agentid" : opt_data.agentid
                });
            }
        });
    })
    
    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        timepicker : true,
        onQuery :function() {
            load_data();
        }
    });

    $('#id_check_money_admin_nick').set_input_change_event(load_data);
});
