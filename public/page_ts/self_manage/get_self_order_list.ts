/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-get_self_order_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){

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


    $('.opt-hand-over').on("click",function(){
        var orderid = $(this).get_opt_data('orderid');
        var sid = $(this).get_opt_data('userid');
        window.location.href="/stu_manage/init_info_by_contract_cc?orderid="+orderid+"&sid="+sid;
    });


    $('.opt-hand-over_info').on("click",function(){
        var orderid = $(this).get_opt_data('orderid');
        var sid = $(this).get_opt_data('userid');
        window.location.href="/stu_manage/init_info_by_contract_cr?orderid="+orderid+"&sid="+sid;
    });





	$('.opt-change').set_input_change_event(load_data);
});

