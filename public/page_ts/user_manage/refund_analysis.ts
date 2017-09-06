/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-refund_analysis.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            orderid       :  g_args.orderid	,
            apply_time    : g_args.apply_time	,
            qc_contact_status   : $("#id_qc_contact_status").val(),
            qc_advances_status  : $("#id_qc_advances_status").val(),
            qc_voluntarily_status  : $("#id_qc_voluntarily_status").val(),
        });
    }

    Enum_map.append_option_list( "qc_contact_status", $("#id_qc_contact_status"));
    Enum_map.append_option_list( "qc_advances_status", $("#id_qc_advances_status"));
    Enum_map.append_option_list( "qc_voluntarily_status", $("#id_qc_voluntarily_status"));
    $("#id_qc_contact_status").val(g_args.qc_contact_status);
    $("#id_qc_advances_status").val(g_args.qc_advances_status);
    $("#id_qc_voluntarily_status").val(g_args.qc_voluntarily_status);

    var adminid = $('#adminid').attr('data-adminid');


    $("#id_qc_msg").on("click",function(){
        var qc_other_reason = $("#id_qc_other_reason").val();
        var qc_analysia     = $("#id_qc_analysia").val();
        var qc_reply        = $("#id_qc_reply").val();

        var qc_contact_status      = $("#id_qc_contact_status").val();
        var qc_advances_status     = $("#id_qc_advances_status").val();
        var qc_voluntarily_status  = $("#id_qc_voluntarily_status").val();

        console.log(qc_voluntarily_status);

        if(adminid != 540 && adminid != 968 && adminid != 99 && adminid != 1024 ){
            alert('您没有修改权限!');
            load_data();
        } else {
            $.do_ajax( "/user_manage/add_qc_analysis_by_order_apply",{
                orderid         : g_args.orderid,
                apply_time      : g_args.apply_time	,
                qc_reply        : qc_reply,
                qc_analysia     : qc_analysia,
                qc_other_reason : qc_other_reason,
                qc_contact_status   : qc_contact_status,
                qc_advances_status  : qc_advances_status,
                qc_voluntarily_status : qc_voluntarily_status
            } ) ;
        }
    });


});
