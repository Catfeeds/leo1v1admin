/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/period_order-get_all_payed_order_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			contract_type:	$('#id_contract_type').val(),
			contract_status:	$('#id_contract_status').val(),
			pay_status:	$('#id_pay_status').val(),
			channel:	$('#id_channel').val(),
            userid:	$('#id_userid').val(),
			parent_orderid:	$('#id_parent_orderid').val(),
			child_orderid:	$('#id_child_orderid').val(),
            repay_status:	$('#id_repay_status').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
    Enum_map.append_option_list( "contract_type", $("#id_contract_type"));
    Enum_map.append_option_list( "repay_status", $("#id_repay_status"));
	$('#id_contract_type').val(g_args.contract_type);
	$('#id_contract_status').val(g_args.contract_status);
	$('#id_pay_status').val(g_args.pay_status);
	$('#id_channel').val(g_args.channel);
    $('#id_userid').val(g_args.userid);
	$('#id_parent_orderid').val(g_args.parent_orderid);
	$('#id_child_orderid').val(g_args.child_orderid);
	$('#id_repay_status').val(g_args.repay_status);

    $.admin_select_user( $("#id_userid"), "student", load_data );
    

    //点击进入个人主页
    $('.opt-user').on('click',function(){
        var userid = $(this).parent().data("userid");
        var nick   = $(this).parent().data("nick");
        //$(this).attr('href','/stu_manage?sid = '+userid+'&nick='+nick+"&"  );
        window.open('/stu_manage?sid='+ userid+"&return_url="+ encodeURIComponent(window.location.href)) ;

    });


    $(".opt-order-detail-info").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var orderid = opt_data.child_orderid;
        
        var title = "分期还款详情";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>期数</td><td>用户账户</td><td>还款明细状态</td><td>已还款日期</td><td>当期应还款日</td><td>当期应还款金额[总]</td><td>已还金额</td><td>未还金额</td></tr></table></div>");
        $.do_ajax("/ajax_deal2/get_baidu_period_detail_info",{
            orderid: orderid,
        },function(resp){
            var data_list = resp.data;
            if(resp.ret != 0){
                alert(resp.info);
                return;
            }
            $.each(data_list,function(i,item){
                html_node.find("table").append("<tr><td>"+item['period']+"</td><td>"+item['bid']+"</td><td>"+item['bStatus_str']+"</td><td>"+item['paidTime_str']+"</td><td>"+item['dueDate_str']+"</td><td>"+item['money']/100+"</td><td>"+item['paidMoney']/100+"</td><td>"+item['unpaidMoney']/100+"</td></tr>");

            });
           
            var dlg=BootstrapDialog.show({
                title:title,
                message :  html_node   ,
                closable: true,
                buttons:[{
                    label: '返回',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();

                    }
                }],
                onshown:function(){

                }

            });

            dlg.getModalDialog().css("width","1000px");

        });

    });

    $(".opt-order-detail-info-new").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var orderid = opt_data.child_orderid;
        
        var title = "分期还款详情";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>期数</td><td>用户账户</td><td>还款明细状态</td><td>已还款日期</td><td>当期应还款日</td><td>当期应还款金额[总]</td><td>已还金额</td><td>未还金额</td><td>还款状态确认</td></tr></table></div>");
        $.do_ajax("/ajax_deal2/get_baidu_period_detail_info_new",{
            orderid: orderid,
        },function(resp){
            var data_list = resp.data;
            if(resp.ret != 0){
                alert(resp.info);
                return;
            }
            $.each(data_list,function(i,item){
                html_node.find("table").append("<tr><td>"+item['period']+"</td><td>"+item['bid']+"</td><td>"+item['b_status_str']+"</td><td>"+item['paid_time_str']+"</td><td>"+item['due_date_str']+"</td><td>"+item['money']/100+"</td><td>"+item['paid_money']/100+"</td><td>"+item['un_paid_money']/100+"</td><td>"+item['repay_status_str']+"</td></tr>");

            });
            
            var dlg=BootstrapDialog.show({
                title:title,
                message :  html_node   ,
                closable: true,
                buttons:[{
                    label: '返回',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();

                    }
                }],
                onshown:function(){

                }

            });

            dlg.getModalDialog().css("width","1000px");

        });

    });

    if(g_account_role==13){
        download_show();
    }

	$('.opt-change').set_input_change_event(load_data);
});
