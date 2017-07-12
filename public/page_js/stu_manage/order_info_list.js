/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-order_info_list.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page({
      sid              : g_sid,
      competition_flag : $('#id_competition_flag').val()
        });
    }
  $('#id_competition_flag').val(g_args.competition_flag);
  $('.opt-change').set_input_change_event(load_data);

    $.each($(".opt-back-money"),function(){
        var order_left     = $(this).get_opt_data("order_left");
        if(order_left==0){
            $(this).hide();
        }
    });



    $.each($(".opt-hand-over_info"),function(){
        var hand_over_view = $(this).get_opt_data("hand_over_view");
        if(hand_over_view == 0){
            $(this).hide();
        }
    });

    $.each($(".opt-hand-over"),function(){
        var hand_over_flag = $(this).get_opt_data("contract_type");
        if(hand_over_flag != 0){
            $(this).hide();
        }
    });




    $(".opt-back-money").on("click",function(){
        var orderid         = $(this).get_opt_data("orderid");
        var price           = $(this).get_opt_data("price");
        var per_price       = $(this).get_opt_data("per_price");
        var lesson_total    = $(this).get_opt_data("lesson_total");
        var order_left      = $(this).get_opt_data("order_left");

        var realname        = $(this).get_opt_data("realname");
        var phone           = $(this).get_opt_data("phone");
        var userid          = g_args.sid;

        var $id_stu_info      = $("<div/>");
        var $id_orderid       = $("<div/>");
        var $id_lesson_total  = $("<div/>");
        var $id_lesson_left   = $("<div/>");
        var $id_price         = $("<div/>");
        var $id_per_price     = $("<div/>");
        var $id_should_refund = $("<input/>");
        var $id_real_refund   = $("<input/>");
        var $id_refund_reason = $("<textarea/>");

        var arr=[
            ["学生", $id_stu_info] ,
            ["合同id", $id_orderid] ,
            ["购买课时", $id_lesson_total] ,
            ["剩余课时", $id_lesson_left] ,
            ["实付金额", $id_price] ,
            ["课时单价", $id_per_price] ,
            ["应退课时", $id_should_refund] ,
            ["退费金额", $id_real_refund] ,
            ["退费原因", $id_refund_reason] ,
        ];

        $id_stu_info.html(realname+"/"+phone);
        $id_orderid.html(orderid);
        $id_lesson_total.html(lesson_total);
        $id_lesson_left.html(order_left);
        $id_price.html(price);
        $id_per_price.html(per_price);

        var should_refund = 0;
        var real_refund   = 0;
        $.show_key_value_table("合同退费",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                should_refund = $id_should_refund.val();
                real_refund   = $id_real_refund.val();
                var refund_reason = $id_refund_reason.val();
                if(should_refund>order_left){
                    BootstrapDialog.alert("所退课时不足!");
                    return;
                }
                if(real_refund>price){
                    BootstrapDialog.alert("退费金额错误!");
                    return;
                }
                if(refund_reason==''){
                    BootstrapDialog.alert("请填写退费原因!");
                    return;
                }

                $.do_ajax("/user_manage/set_refund_order", {
                    "orderid"       : orderid,
                    "contractid"    : $(this).get_opt_data("contractid"),
                    "contract_type" : $(this).get_opt_data("contract_type"),
                    "userid"        : userid,
                    "lesson_total"  : lesson_total,
                    "order_left"    : order_left,
                    "should_refund" : should_refund,
                    "real_refund"   : real_refund,
                    "price"         : price,
                    "refund_reason" : refund_reason
                },function(result){
                    window.location.reload();
                });
            }
        },function(){
            $id_should_refund.on("change",function(){
                should_refund = $(this).val();
                real_refund = per_price*should_refund;
                if(should_refund>order_left){
                    BootstrapDialog.alert("所退课时不足!");
                    $id_should_refund.val(0);
                    $id_real_refund.val(0);
                }else{
                    $id_real_refund.val(real_refund);
                }
            });
            $id_real_refund.on("change",function(){
                should_refund=$id_should_refund.val();
                real_refund=$(this).val();
                if(real_refund>per_price*should_refund){
                    BootstrapDialog.alert("所退金额错误!");
                    $id_should_refund.val(0);
                    $id_real_refund.val(0);
                }
            });
        });

    });

    $('.opt-hand-over').on("click",function(){
        var orderid = $(this).get_opt_data('orderid');
        var sid     = g_sid;
        window.location.href="/stu_manage/init_info_by_contract_cc?orderid="+orderid+"&sid="+sid;
    });


    $('.opt-hand-over_info').on("click",function(){
        var orderid = $(this).get_opt_data('orderid');
        var sid     = g_sid;
        window.location.href="/stu_manage/init_info_by_contract_cr?orderid="+orderid+"&sid="+sid;
    });


});
