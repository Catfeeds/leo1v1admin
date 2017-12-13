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
    //@desn:获取冻结原因
    $(".opt-freeze_reason").on('click',function(){
        var opt_data = $(this).get_opt_data();
        $.do_ajax("/agent/get_freeze_reason/",{
            'id':opt_data.id,
        },function(ret){
            if(ret.freeze_reason  == -1){
                alert('该条申请不存在冻结金额!');
            }else{
                var arr = [
                    ['冻结订单id',ret.freeze_reason.agent_cash_id],
                    ['冻结金额',ret.freeze_reason.freeze_money],
                    ['操作人',ret.freeze_reason.admin_account],
                    ['冻结时间',ret.freeze_reason.create_time],
                    ['冻结类型',ret.freeze_reason.agent_freeze_type_str],
                    ['违规学员手机号',ret.freeze_reason.phone],
                ];
                if(ret.freeze_reason.agent_freeze_type_str != null)
                    arr.push(['活动类型',ret.freeze_reason.agent_money_ex_type_str]);
                if(ret.freeze_reason.agent_activity_time != null)
                    arr.push(['活动时间',ret.freeze_reason.agent_activity_time]);

                show_key_value_table("冻结原因",arr );
                return false;
            }
        });
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

    //@desn:冻结申请体现金额
    $('.opt-freeze').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var $freeze_money = $("<input/>");
        var $phone = $("<input/>")
        var $agent_freeze_type= $("<select id='id_agent_freeze_type' />" );
        Enum_map.append_option_list("agent_freeze_type", $agent_freeze_type,true);
        var $agent_money_ex_type= $("<select/>" );
        Enum_map.append_option_list("agent_money_ex_type", $agent_money_ex_type,true);
        var $agent_activity_time = $("<input/>");
        $agent_activity_time.datetimepicker({
            lang:'ch',
            timepicker:true,
            format:'Y-m-d H:i:s',
            "onChangeDateTime" : function() {
            }
        });

        $(".table").append($agent_freeze_type);
        $(".table").append($agent_money_ex_type);
        $(".table").append($agent_activity_time);
        
        var arr=[
            ["冻结金额" ,$freeze_money ],
            ["冻结类型" ,$agent_freeze_type ],
            ["违规学员手机号",$phone],
            ["活动类型",$agent_money_ex_type],
            ["活动日期",$agent_activity_time]
        ] ;

        // 设置动态显示 [select]  --begin--
        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        var hidden_field=function(){
            show_field($agent_money_ex_type,false);
            show_field($agent_activity_time,false);
        }
        var reset_ui=function(agent_freeze_val) {
            if(agent_freeze_val == 3){ 
                show_field($agent_money_ex_type,true );
                show_field($agent_activity_time,true );
            }else{
                hidden_field();
            }
        };
        
        $('#id_agent_freeze_type').bind('change',function(){
            var agent_freeze_val = $(this).val();
            console.log(agent_freeze_val);
            reset_ui(agent_freeze_val);
        });
        
        // 设置动态显示 [select]  --end--

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
        hidden_field();
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
    function show_key_value_table(title,arr ,btn_config,onshownfunc){
        var table_obj = $("<table class=\"table table-bordered table-striped\"><tr><thead><td style=\"text-align:right;\">属性  </td>  <td> 值 </td> </thead></tr></table>");

        $.each(arr , function( index,element){
            var row_obj=$("<tr> </tr>" );
            var td_obj=$( "<td style=\"text-align:right; width:30%;\"></td>" );
            var v=element[0] ;
            td_obj.append(v);
            row_obj.append(td_obj);
            td_obj=$( "<td ></td>" );

            td_obj.append( element[1] );
            row_obj.append(td_obj);
            table_obj.append(row_obj);
        });
        var all_btn_config=[{
            label: '返回',
            action: function(dialog) {
                dialog.close();
            }
        }];
        if (btn_config){
            if($.isArray( btn_config)){
                $.each(btn_config ,function(){
                    all_btn_config.push(this);
                });
            }else{
                all_btn_config.push(btn_config );
            }
        }

        BootstrapDialog.show({
            title    : title,
            message  : table_obj ,
            closable : true,
            buttons  : all_btn_config ,
            onshown  : onshownfunc
        });
    }
    //@desn:查看体现资金来源
    $(".opt-money_detail").on("click",function(){
        //优学优享会员、学员用标识
        var opt_data=$(this).get_opt_data();
        var agentid = opt_data.aid;
        var this_cash_time = opt_data.create_time;
        var timestamp2 = Date.parse(new Date(this_cash_time));
        timestamp2 = timestamp2 / 1000;
        $.ajax({
            type     : "post",
            url      : "/agent/get_agent_cash_log",
            dataType : "json",
            size     : BootstrapDialog.SIZE_WIDE,
            data     : {"agentid":agentid,'this_cash_time':timestamp2},
            success  : function(result){
                var html_str=$("<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > <tr><th> 用户信息  <th> 推荐人信息 <th> 收入类型 <th> 收入金额 <th> 获得时间  </tr> </table></div>");
                $.each( result.agent_cash_log ,function(i,item){
                    console.log(item);
                    var html  = "<tr><td>"+item.agent_name +"</td><td>"+
                        item.agent_child_name+"</td><td>"+item.agent_income_type_str+"<td>"+ 
                        item.money +"</td><td>"+item.create_time+"</td></tr>";
                    html_str.find("table").append(html);
                } );

                var dlg = BootstrapDialog.show({
                    title    : '本次体现来源明细',
                    message  : html_str ,
                    closable : true,
                    buttons  : [{
                        label  : '返回',
                        action : function(dialog) {
                            dialog.close();
                        }
                    }]
                });

                if (!$.check_in_phone()) {
                    dlg.getModalDialog().css("width", "800px");
                }
            }
        });
    });

    if(g_account=="echo"){
        download_show();
    }

});
