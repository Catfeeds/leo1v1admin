/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-refund_analysis.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            orderid       : g_args.orderid	,
            apply_time    : g_args.apply_time	,
            teacherid     : $('#id_teacher').val(),
            subject      : $("#id_subject").val(),

        });
    }



    var adminid = $('#adminid').attr('data-adminid');

    var qc_contact_status  = $('#opt_qc_contact_status').attr('data-val');
    var qc_advances_status = $('#opt_qc_advances_status').attr('data-val');
    var qc_voluntarily_status  = $('#opt_qc_voluntarily_status').attr('data-val');

    $("#id_teacher").val(g_args.teacherid);

    $.admin_select_user( $("#id_teacher"), "teacher");

    Enum_map.append_option_list( "qc_contact_status", $("#id_qc_contact_status"));
    Enum_map.append_option_list( "qc_advances_status", $("#id_qc_advances_status"));
    Enum_map.append_option_list( "qc_voluntarily_status", $("#id_qc_voluntarily_status"));

    Enum_map.append_option_list( "subject", $("#id_subject"));

    $("#id_subject").val(g_args.subject);

    $("#id_qc_contact_status").find('option[value="'+qc_contact_status+'"]').attr('selected',1);
    $("#id_qc_advances_status").find('option[value="'+qc_advances_status+'"]').attr('selected',1);
    $("#id_qc_voluntarily_status").find('option[value="'+qc_voluntarily_status+'"]').attr('selected',1);



    $("#id_qc_msg").on("click",function(){
        var qc_other_reason = $("#id_qc_other_reason").val();
        var qc_analysia     = $("#id_qc_analysia").val();
        var qc_reply        = $("#id_qc_reply").val();

        var qc_contact_status      = $("#id_qc_contact_status").val();
        var qc_advances_status     = $("#id_qc_advances_status").val();
        var qc_voluntarily_status  = $("#id_qc_voluntarily_status").val();

        var teacherid  = $('#id_teacher').val();
        var subject    = $('#id_subject').val();

        if(qc_contact_status<1){
            alert('请选择联系状态!');
            load_data();
            return;
        }

        if(qc_advances_status<1){
            alert('请选择提升状态!');
            load_data();
            return;
        }

        if(qc_voluntarily_status<1){
            alert('请选择是否自愿状态!');
            load_data();
            return;
        }

        if(adminid != 540 && adminid != 968 && adminid != 99 && adminid != 1024 && adminid!=1184 && adminid!=1370 ){
            alert('您没有修改权限!');
            load_data();
        } else {
            $.do_ajax( "/user_manage/add_qc_analysis_by_order_apply",{
                teacherid       : teacherid,
                subject         : subject,
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
