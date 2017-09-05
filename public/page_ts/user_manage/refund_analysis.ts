/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-refund_analysis.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            orderid       :  g_args.orderid	,
            apply_time    : g_args.apply_time	,
            is_test_user  : $("#id_is_test_user").val(),

        });
    }


    var adminid = $('#adminid').attr('data-adminid');


    $("#id_qc_msg").on("click",function(){

        var qc_other_reason = $("#id_qc_other_reason").val();
        var qc_analysia     = $("#id_qc_analysia").val();
        var qc_reply        = $("#id_qc_reply").val();

        if(adminid != 540 && adminid != 968){
            alert('您没有修改权限!');
            load_data();
        } else {
            $.do_ajax( "/user_manage/add_qc_analysis_by_order_apply",{
                orderid         : g_args.orderid,
                apply_time      : g_args.apply_time	,
                qc_reply        : qc_reply,
                qc_analysia     : qc_analysia,
                qc_other_reason : qc_other_reason
            } ) ;
        }
    });


});
