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
    userid_stu:	$('#id_userid_stu').val()

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
    Enum_map.append_option_list("boolean",$("#id_is_test_user"));
    Enum_map.append_option_list("boolean",$("#id_need_receipt"));


    //init  input data
    $("#id_start_time").val(g_args.start_time);
    $("#id_end_time").val(g_args.end_time);
    $("#id_studentid").val(g_args.studentid);
    $("#id_check_money_flag").val(g_args.check_money_flag );
    $('#id_is_test_user').val(g_args.is_test_user);
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
        Enum_map.append_option_list( "check_money_flag",  $check_money_flag ,true );

        $.do_ajax( "/ajax_deal2/get_order_desc_html_str",{
            "str" : JSON.stringify( opt_data.order_price_desc)
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
                        "check_money_desc" : $check_money_desc.val()
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
            });


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
        var invoice    = $("<input/>");
        var  order_stamp_flag = $("<select/>");
        Enum_map.append_option_list( "is_invoice",  is_invoice ,true );
        Enum_map.append_option_list( "boolean", order_stamp_flag  ,true );
        var $check_money_desc = $("<textarea/>");
        $check_money_desc.val(opt_data.check_money_desc );
        invoice.val(opt_data.invoice);
        order_stamp_flag.val(opt_data.order_stamp_flag);
        is_invoice.val(opt_data.is_invoice );

        var arr=[
            ["财务确认说明" ,  $check_money_desc],
            ["是否需要发票" , is_invoice],
            ["发票" ,  invoice],
            ["合同是否已盖章" ,  order_stamp_flag],
        ];
        $.show_key_value_table("编辑", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_manage_new/edit_invoice",{
                    "orderid"    : orderid,
                    "check_money_desc" : $check_money_desc.val(),
                    "is_invoice" : is_invoice.val(),
                    "order_stamp_flag" : order_stamp_flag.val(),
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
        var title = "编辑子合同";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>类型</td><td>金额</td><td>分期期数</td><td>付款</td><td>操作</td></tr></table></div>");
        $.do_ajax("/ss_deal/get_child_order_list",{
            orderid: data.orderid,
        },function(resp){
            var data_list = resp.data;
            if(resp.ret != 0){
                alert(resp.info);
                return;
            }
            $.each(data_list,function(i,item){
                if(item["child_order_type"]==0){
                    html_node.find("table").append("<tr><td>"+item['child_order_type_str']+"</td><td>"+item['price']/100+"</td><td>"+item['period_num_info']+"</td><td>"+item['pay_status_str']+"</td><td><a href=\"javascript:;\" class=\"order_partition\"  data-status=\""+item["pay_status"]+"\" data-orderid=\""+item["parent_orderid"]+"\" data-child_orderid=\""+item['child_orderid']+"\">拆分</a></td></tr>");
                }else{
                    html_node.find("table").append("<tr><td>"+item['child_order_type_str']+"</td><td>"+item['price']/100+"</td><td>"+item['period_num_info']+"</td><td>"+item['pay_status_str']+"</td><td><a href=\"javascript:;\" class=\"update_child_order_info\" data-status=\""+item["pay_status"]+"\" data-orderid=\""+item["parent_orderid"]+"\" data-type=\""+item["child_order_type"]+"\" data-price=\""+item["price"]+"\" data-pnum=\""+item["period_num"]+"\" data-child_orderid=\""+item['child_orderid']+"\">修改</a>&nbsp&nbsp&nbsp&nbsp<a href=\"javascript:;\" class=\"delete_child_order_info\" data-status=\""+item["pay_status"]+"\" data-orderid=\""+item["parent_orderid"]+"\" data-child_orderid=\""+item['child_orderid']+"\">删除</a></td></tr>");
                }

            });
            html_node.find("table").find(".order_partition").each(function(){
                $(this).on("click",function(){
                    var parent_orderid = $(this).data("orderid");

                    var child_orderid = $(this).data("child_orderid");
                    var status = $(this).data("status");
                    if(status >0){
                        alert("已付款,不能拆分!");
                        return;
                    }
                    var id_child_order_type= $("<select> "+
                                               "<option value=1>首付款</option> "+
                                               "<option value=2>分期</option> "+
                                               "<option value=3>其他</option> "+
                                               "</select>");
                    var id_period_num= $("<select> "+
                                         "<option value=6>6期</option> "+
                                         "<option value=12>12期</option> "+
                                         "</select>");

                    var id_child_order_money=$("<input/>");


                    var arr=[
                        ["类型", id_child_order_type],
                        ["分期期数", id_period_num],
                        ["金额", id_child_order_money]
                    ];

                    id_child_order_type.on("change",function(){
                        if(id_child_order_type.val() ==2){
                            id_period_num.parent().parent().show();
                        }else{
                            id_period_num.parent().parent().hide();
                        }
                    });
                    $.show_key_value_table("增加子合同", arr, {
                        label: '确认',
                        cssClass: 'btn-warning',
                        action: function (dialog) {
                            $.do_ajax( '/ss_deal/add_child_order_info', {
                                "parent_orderid" : parent_orderid,
                                "child_orderid" : child_orderid,
                                "child_order_type"     : id_child_order_type.val(),
                                "period_num"           : id_period_num.val(),
                                "price"                : id_child_order_money.val()*100
                            });
                        }
                    },function(){
                        if(id_child_order_type.val() ==2){
                            id_period_num.parent().parent().show();
                        }else{
                            id_period_num.parent().parent().hide();
                        }

                    });


                });

            });

            html_node.find("table").find(".update_child_order_info").each(function(){
                $(this).on("click",function(){
                    var parent_orderid = $(this).data("orderid");

                    var child_orderid = $(this).data("child_orderid");
                    var status = $(this).data("status");
                    var child_oeder_type = $(this).data("type");
                    var child_oeder_mpney = $(this).data("price");
                    var period_num = $(this).data("pnum");

                    if(status >0){
                        alert("已付款,不能修改!");
                        return;
                    }
                    var id_child_order_type= $("<select> "+
                                               "<option value=1>首付款</option> "+
                                               "<option value=2>分期</option> "+
                                               "<option value=3>其他</option> "+
                                               "</select>");
                    var id_period_num= $("<select> "+
                                         "<option value=6>6期</option> "+
                                         "<option value=12>12期</option> "+
                                         "</select>");


                    var id_child_order_money=$("<input/>");
                    id_child_order_type.val(child_oeder_type);
                    id_period_num.val(period_num);
                    id_child_order_money.val(child_oeder_mpney/100);

                    var arr=[
                        ["类型", id_child_order_type],
                        ["分期期数", id_period_num],
                        ["金额", id_child_order_money]
                    ];
                    id_child_order_type.on("change",function(){
                        if(id_child_order_type.val() ==2){
                            id_period_num.parent().parent().show();
                        }else{
                            id_period_num.parent().parent().hide();
                        }
                    });

                    $.show_key_value_table("修改子合同", arr, {
                        label: '确认',
                        cssClass: 'btn-warning',
                        action: function (dialog) {
                            $.do_ajax( '/ss_deal/update_child_order_info', {
                                "parent_orderid" : parent_orderid,
                                "child_orderid" : child_orderid,
                                "child_order_type"     : id_child_order_type.val(),
                                "period_num"           : id_period_num.val(),
                                "price"                : id_child_order_money.val()*100
                            });
                        }
                    },function(){
                        if(id_child_order_type.val() ==2){
                            id_period_num.parent().parent().show();
                        }else{
                            id_period_num.parent().parent().hide();
                        }

                    });


                });

            });


            html_node.find("table").find(".delete_child_order_info").each(function(){
                $(this).on("click",function(){
                    var parent_orderid = $(this).data("orderid");

                    var child_orderid = $(this).data("child_orderid");
                    var status = $(this).data("status");
                    if(status >0){
                        alert("已付款,不能删除!");
                        return;
                    }

                    BootstrapDialog.confirm("确定要删除？", function(val){
                        if (val) {
                            $.do_ajax( '/ss_deal/delete_child_order_info', {
                                "parent_orderid" : parent_orderid,
                                "child_orderid" : child_orderid,
                            });

                        }
                    });



                });

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




});
