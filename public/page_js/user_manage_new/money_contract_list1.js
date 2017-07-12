/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-money_contract_list.d.ts" />

function load_data(){

    reload_self_page({
        studentid: $("#id_studentid").val(),
        contract_type: $("#id_contract_type").val(),
        check_money_flag : $("#id_check_money_flag").val(),
        start_time: $("#id_start_time").val(),
        origin: $("#id_origin").val(),
        from_type: $("#id_from_type").val(),
        "sys_operator" :$("#id_sys_operator").val(),
        end_time: $("#id_end_time").val(),
        account_role: $("#id_account_role").val()
        
    });
}

function isNumber( s ){
    var regu = "^[0-9]+$";
    var re = new RegExp(regu);
    if (s.search(re) != -1) {
        return true;
    } else {
        return false;
    }
}

$(function(){
    

    Enum_map.append_option_list( "check_money_flag", $("#id_check_money_flag"));
    Enum_map.append_option_list( "contract_from_type", $("#id_from_type"));
    Enum_map.append_option_list( "contract_type", $("#id_contract_type"));
    Enum_map.append_option_list( "account_role", $("#id_account_role"));


	//init  input data
	$("#id_start_time").val(g_args.start_time);
	$("#id_end_time").val(g_args.end_time);
    $("#id_studentid").val(g_args.studentid);
    $("#id_check_money_flag").val(g_args.check_money_flag );
    $("#id_origin").val(g_args.origin);
    $("#id_from_type").val(g_args.from_type);
    $("#id_contract_type").val(g_args.contract_type);
    $("#id_sys_operator").val(g_args.sys_operator);
    $("#id_account_role").val(g_args.account_role);

    $("#id_studentid").admin_select_user({
        "type"   : "student",
        "onChange": function(){
            load_data(  );
        }
    });
    
    


	//时间控件
	$('#id_start_time').datetimepicker({
		lang:'ch',
		timepicker:true,
		format:'Y-m-d H:i',
        onChangeDateTime :function(){
            load_data();
		}
	});
	
	$('#id_end_time').datetimepicker({
		lang:'ch',
		timepicker:true,
		format:'Y-m-d H:i',
        onChangeDateTime :function(){
            load_data(
			);
		}
	});//时间控件-over
	



	//点击进入个人主页
	$('.opt-user').on('click',function(){
		var userid = $(this).parent().data("userid");
		var nick   = $(this).parent().data("stu_nick");
		//$(this).attr('href','/stu_manage?sid = '+userid+'&nick='+nick+"&"  );
        window.open('/stu_manage?sid='+ userid+"&return_url="+ encodeURIComponent(window.location.href)) ;
        
	});

    set_input_enter_event($("#id_origin"),load_data);
    set_input_enter_event($(".opt-change"),load_data);



	$(".c_sel").on("change",function(){
        load_data();
	});


    $(" .opt-money-check").on("click",function(){
        var orderid=$(this).get_opt_data("orderid");
        var $check_money_flag = $("<select/>");
        var $check_money_desc = $("<textarea/>");
        Enum_map.append_option_list( "check_money_flag",  $check_money_flag ,true );
        
        var arr=[
            ["确认状态" , $check_money_flag],
            ["说明" ,  $check_money_desc],
        ];
        show_key_value_table("财务确认", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax("/user_deal/order_check_money",{
                    "orderid" :orderid,
                    "check_money_flag" : $check_money_flag.val(),
                    "check_money_desc" : $check_money_desc.val()
                });
            }
        });

    });

    
    $("#id_show_all").on("click",function(){
	    //
		var url= $(".page-opt-show-all" ).attr("data");
        if (!url) {
            alert("已经是全部了!");
            return ;
        }else{
		    var page_num=0xFFFFFFFF+1; 
            url=url.replace(/{Page}/, page_num  );
            $(this).attr("href",url);
        }
	    
    });

    $(" .opt-edit-invoice").on("click",function(){

        var orderid=$(this).get_opt_data("orderid");
        var is_invoice = $("<select/>");
        var invoice    = $("<input/>");
        Enum_map.append_option_list( "is_invoice",  is_invoice ,true );
        
        var arr=[
            ["是否需要发票" , is_invoice],
            ["发票" ,  invoice],
        ];
        show_key_value_table("编辑发票", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax("/user_manage_new/edit_invoice",{
                    "orderid"    : orderid,
                    "is_invoice" : is_invoice.val(),
                    "invoice"    : invoice.val()
                });
            }
        });

    });

    $(".opt-change-money").on("click", function(){
        var contractid=  $(this).parent().data('contractid');
        var orderid =  $(this).parent().data('orderid');
        var userid=  $(this).parent().data('userid');
        var html_node=$("<span> <input type=input style=\"width:80px\"> </input>元</span> ");

        var dlg=BootstrapDialog.show({
            title: '更改金额',
            message : html_node, 
            closable: true, 
            buttons: [{
                label: '返回',

                action: function(dialog) {
                    dialog.close();
                }
            },{
                label: '提交',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.ajax({
			            type     :"post",
			            url      :"/user_manage/set_contract_money",
			            dataType :"json",
			            data     :{
                            'orderid': orderid,
                            'userid':userid,
                            'price':   Math.round( parseFloat(html_node.find("input").val())*100)
                        }, success  : function(result){
                            if(result.ret == -1){
                                alert(result.info);
                            }else{
                                window.location.reload();    
                            }
                        }
                    });
                }
            }]
        });

        dlg.getModalDialog().css("width","250px");
        dlg.getModalDialog().css("min-width","250px");

    });


});


