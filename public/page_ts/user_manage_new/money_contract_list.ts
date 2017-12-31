/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-money_contract_list.d.ts" />

function load_data(){

    $.reload_self_page({
        studentid: $("#id_studentid").val(),
        contract_type: $("#id_contract_type").val(),
        contract_status:	$('#id_contract_status').val(),
        check_money_flag : $("#id_check_money_flag").val(),
        start_time: $("#id_start_time").val(),
        origin: $("#id_origin").val(),
        from_type: $("#id_from_type").val(),
        "sys_operator" :$("#id_sys_operator").val(),
        end_time: $("#id_end_time").val(),
        is_test_user:	$('#id_is_test_user').val(),
        need_receipt:	$('#id_need_receipt').val(),
        account_role: $("#id_account_role").val(),
        userid_stu:	$('#id_userid_stu').val(),
        can_period_flag:	$('#id_can_period_flag').val(),
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
    Enum_map.append_option_list("can_period_flag",$("#id_can_period_flag"));
    Enum_map.append_option_list("boolean",$("#id_is_test_user"));
    Enum_map.append_option_list("boolean",$("#id_need_receipt"));


    //init  input data
    $("#id_start_time").val(g_args.start_time);
    $("#id_end_time").val(g_args.end_time);
    $("#id_studentid").val(g_args.studentid);
    $("#id_check_money_flag").val(g_args.check_money_flag );
    $('#id_is_test_user').val(g_args.is_test_user);
    $('#id_can_period_flag').val(g_args.can_period_flag);
    $("#id_origin").val(g_args.origin);
    $("#id_from_type").val(g_args.from_type);
    $("#id_contract_type").val(g_args.contract_type);
    $('#id_contract_status').val(g_args.contract_status);
    $.enum_multi_select( $('#id_contract_status'),"contract_status" ,function( ){ load_data() });
    $("#id_sys_operator").val(g_args.sys_operator);
    $("#id_account_role").val(g_args.account_role);
    $('#id_need_receipt').val(g_args.need_receipt);
    $('#id_userid_stu').val(g_args.userid_stu);

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

    $('.opt-change').set_input_change_event(load_data);


    $(".c_sel").on("change",function(){
        load_data();
    });


    $(" .opt-money-check").on("click",function(){
        var orderid           = $(this).get_opt_data("orderid");
        var opt_data          = $(this).get_opt_data();
        var $check_money_flag = $("<select/>");
        var $check_money_desc = $("<textarea/>");
        var $can_period_flag = $("<select/>");
        Enum_map.append_option_list( "check_money_flag",  $check_money_flag ,true );
        Enum_map.append_option_list( "can_period_flag",  $can_period_flag ,true );
        $can_period_flag.val(opt_data.can_period_flag);
        $.do_ajax( "/ajax_deal2/get_order_activity_list",{
            "orderid" : opt_data.orderid
        },function(resp){
            var list= resp.list;
            var data=opt_data.order_price_desc;
            if( list.length >0 )  {
                data=list;
            }
            $.do_ajax( "/ajax_deal2/get_order_desc_html_str",{
                "str" : JSON.stringify(list )
            }, function (resp){
                var arr=[
                    [ "学生" , opt_data.stu_nick ],
                    [ "年级" , opt_data.grade_str ],
                    [ "试听课时间" , opt_data.lesson_start ],
                    [ "奥赛" , opt_data.competition_flag_str ],
                    [ "课时数" , opt_data.lesson_total*opt_data.default_lesson_count/100  ],
                    [ "原价" , opt_data.discount_price/100 ],
                    [ "现价" , opt_data.price ],
                    [ "单价" , opt_data.price/opt_data.lesson_total*100 ],
                    ["计算流程", $( resp.html_str ) ],
                    [ "申请状态" , opt_data.flow_status_str ],
                    [ "申请说明" , opt_data.flow_post_msg ],

                    [ "原因" , opt_data.discount_reason ],
                    [ "确认状态" , $check_money_flag ],
                    [ "是否分期" , $can_period_flag ],
                    [ "说明" ,  $check_money_desc ],
                ];

                $.show_key_value_table("财务确认", arr ,{
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        var check_money_flag= $check_money_flag.val();
                        $.do_ajax("/user_deal/order_check_money",{
                            "orderid"          : orderid,
                            "check_money_flag" : check_money_flag,
                            "check_money_desc" : $check_money_desc.val(),
                            "can_period_flag"  : $can_period_flag.val(),
                        },function( ){
                            if (check_money_flag == 1 ) { //支付成功
                                $.do_ajax("/user_manage/set_contract_payed_new", {
                                    'orderid'    : orderid,
                                    "channelid"  : 1 ,
                                    'userid'     : opt_data.userid,
                                    'pay_number' : 1
                                });
                            }
                        });
                    }

                } );


            } );

        } );


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

        var opt_data=$(this).get_opt_data();
        var orderid=$(this).get_opt_data("orderid");
        var is_invoice = $("<select/>");
        var can_period_flag = $("<select/>");
        var invoice    = $("<input/>");
        var  order_stamp_flag = $("<select/>");
        Enum_map.append_option_list( "is_invoice",  is_invoice ,true );
        Enum_map.append_option_list( "can_period_flag",can_period_flag,true );
        Enum_map.append_option_list( "boolean", order_stamp_flag  ,true );
        var $check_money_desc = $("<textarea/>");
        $check_money_desc.val(opt_data.check_money_desc );
        invoice.val(opt_data.invoice);
        order_stamp_flag.val(opt_data.order_stamp_flag);
        is_invoice.val(opt_data.is_invoice );
        can_period_flag.val(opt_data.can_period_flag);

        var arr=[
            ["财务确认说明" ,  $check_money_desc],
            ["是否需要发票" , is_invoice],
            ["发票" ,  invoice],
            ["合同是否已盖章" ,  order_stamp_flag],
            ["是否分期" ,can_period_flag],
        ];
        $.show_key_value_table("编辑", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_manage_new/edit_invoice",{
                    "orderid"          : orderid,
                    "check_money_desc" : $check_money_desc.val(),
                    "is_invoice"       : is_invoice.val(),
                    "order_stamp_flag" : order_stamp_flag.val(),
                    "invoice"          : invoice.val(),
                    "can_period_flag"  : can_period_flag.val(),
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

    $(".opt-relation-order").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"          : "/ss_deal/get_relation_order_list_js",
            select_primary_field : "orderid",
            select_display       : "orderid",

            //其他参数
            "args_ex" : {
                orderid:opt_data.orderid,
                contract_type: opt_data.contract_type
            },
            //字段列表
            'field_list' :[
                {
                    title:"当前",
                    render:function(val,item) {
                        return item.self_flag_str;
                    }
                },{
                    title:"时间",
                    render:function(val,item) {
                        return item.order_time;
                    }

                },{
                    title:"学生",
                    render:function(val,item) {
                        return item.student_nick;
                    }
                },{
                    title:"申请人",
                    render:function(val,item) {
                        return item.sys_operator;
                    }
                },{


                    title:"分类",
                    render:function(val,item) {
                        return item.contract_type_str ;
                    }
                },{

                    title:"赠送原因",
                    render:function(val,item) {
                        return item.from_parent_order_type_str ;
                    }

                },{
                    title:"课时数",
                    //width :50,
                    render:function(val,item) {
                        return item.lesson_total* item.default_lesson_count/100;
                    }
                },{
                    title:"金额",
                    render:function(val,item) {
                        return item.price;
                    }

                }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : function(require_id,row_data){
            },

            //加载数据后，其它的设置
            "onLoadData"       : null,

        });


    });

    $(".opt-flow-node-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.flow_show_node_list( opt_data.flowid);
    });

    $(".opt-update-order-time").on("click",function(){
	    var data = $(this).get_opt_data();
        var id_order_time_date = $("<input/>");

        var arr = [
            ["下单时间",id_order_time_date]
        ];

        id_order_time_date.datetimepicker({
            lang       : 'ch',
            timepicker : true,
            format     : 'Y-m-d H:i',
        });

        $.show_key_value_table("修改下单时间",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/user_manage_new/update_order_time",{
                    "orderid"         : data.orderid,
                    "order_time_date" : id_order_time_date.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        });
    });

    $(".opt-update-price").on("click",function(){
	      var data = $(this).get_opt_data();
        var id_price = $("<input>");
        var id_discount_price = $("<input>");

        var arr = [
            ["实付价格",id_price],
            ["原始价格",id_discount_price],
        ];
        id_price.val(data.price);
        id_discount_price.val(data.discount_price/100);

        $.show_key_value_table("修改合同",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/user_manage_new/update_order_price",{
                    "orderid"        : data.orderid,
                    "price"          : id_price.val(),
                    "discount_price" : id_discount_price.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        });

    });

    $(".opt-order-partition-info").on("click",function(){
        var data = $(this).get_opt_data();
        /*if(data.contract_status>0){
          alert("已付款合同不能拆分");
          return;
          }*/
        var title = "子合同详情";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>子合同id</td><td>类型</td><td>金额</td><td>分期期数</td><td>付款</td><td>渠道</td><td>订单号</td><td>付款时间</td><td>家长姓名</td></tr></table></div>");
        $.do_ajax("/ss_deal/get_child_order_list",{
            orderid: data.orderid,
        },function(resp){
            var data_list = resp.data;
            if(resp.ret != 0){
                alert(resp.info);
                return;
            }
            $.each(data_list,function(i,item){
                html_node.find("table").append("<tr><td>"+item['child_orderid']+"</td><td>"+item['child_order_type_str']+"</td><td>"+item['price']/100+"</td><td>"+item['period_num_info']+"</td><td>"+item['pay_status_str']+"</td><td>"+item["channel"]+"</td><td>"+item["from_orderno"]+"</td><td>"+item["pay_time_str"]+"</td><td>"+item["parent_name"]+"</td></tr>");               

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

            dlg.getModalDialog().css("width","900px");

        });

    });

    $(".opt-child-order-trandfer").on("click",function(){
        var data = $(this).get_opt_data();
        var id_child_orderid = $("<input>");
        var id_target_orderid = $("<input>");
        var arr = [
            ["合同id",  id_child_orderid] ,
            ["目标合同", id_target_orderid] ,
        ];
        id_child_orderid.on("click",function(){
            var m = $(this);
            get_child_order_list_by_type(m,data.orderid,1);
        });
        id_target_orderid.on("click",function(){
            var m = $(this);
            get_child_order_list_by_type(m,data.orderid,2);
        });


        var get_child_order_list_by_type = function(btn,parent_orderid,target_type){
            $.do_ajax("/ajax_deal2/get_child_orderid_list",{
                "parent_orderid" : parent_orderid,
                "target_type"    : target_type
            },function(response){
                var data_list   = [];
                var select_list = [];
                $.each( response.data,function(){
                    data_list.push([this["child_orderid"] , this["parent_orderid"], this["child_order_type_str"],this["pay_status_str"],this["channel"] ,this["from_orderno"],this["price"]/100]);

                  
                });

                $(this).admin_select_dlg({
                    header_list     : [ "子合同","父合同","类型","付款","渠道","订单号","价格" ],
                    data_list       : data_list,
                    multi_selection : true,
                    select_list     : select_list,
                    onChange        : function( select_list,dlg) {
                        btn.val(select_list);
                        dlg.close();
                    }
                });

                
            });

        }


        $.show_key_value_table("合同转移", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                if(id_child_orderid.val() == "" || id_target_orderid.val() == ""){
                    BootstrapDialog.alert("请选择合同");
                    return;
                }
                $.do_ajax("/ajax_deal2/set_child_orderid_transfer", {
                    "child_orderid_list"       : id_child_orderid.val(),
                    "target_orderid_list"       : id_target_orderid.val(),
                });
            }
        });

    });

    $(".opt-del").on("click", function(){
        var orderid = $(this).parent().data('orderid');
        console.log(orderid);
        var userid  = $(this).parent().data('userid');

        var name  = $(this).closest("tr").find(".stu_nick").text();
        var price = $(this).closest("tr").find(".price").text();

        var dlg=BootstrapDialog.show({
            title: '删除合同',
            message : "删除["+name+"]的合同 ?! 金额："+price+"元",
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
                        url      :"/user_manage/del_contract",
                        dataType :"json",
                        data     :{
                            'orderid': orderid,
                            'userid':userid
                        }, success  : function(result){
                            if(result.ret == -1){
                                BootstrapDialog.alert(result.info);
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


    // if(g_adminid==889 || g_adminid==716 || g_adminid==301 || g_adminid==780){
    //     download_show();
    // }
    if(g_adminid==889 || g_adminid==716 || g_adminid==301 || g_adminid==780 || g_adminid==778){
        download_show();
    }

});
