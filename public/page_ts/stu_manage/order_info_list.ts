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

    // $.each($(".opt-hand-over_info"),function(){
    //     var hand_over_view = $(this).get_opt_data("hand_over_view");
    //     if(hand_over_view == 0){
    //         $(this).hide();
    //     }
    // });

    // $.each($(".opt-hand-over"),function(){
    //     var hand_over_flag = $(this).get_opt_data("contract_type");
    //     if(hand_over_flag != 0){
    //         $(this).hide();
    //     }
    // });





    $('.opt-hand-over').on("click",function(){
        var orderid = $(this).get_opt_data('orderid');
        var sid     = g_sid;
        window.location.href="/stu_manage/init_info_by_contract_cc?orderid="+orderid+"&sid="+sid;
    });


    // $('.opt-hand-over_info').on("click",function(){
    //     var orderid = $(this).get_opt_data('orderid');
    //     var sid     = g_sid;
    //     window.location.href="/stu_manage/init_info_by_contract_cr?orderid="+orderid+"&sid="+sid;
    // });


});
