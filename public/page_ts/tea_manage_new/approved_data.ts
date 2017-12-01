/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage_new-approved_data.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        date_type_config:	$('#id_date_type_config').val(),
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val(),
        teacherid    : $("#id_teacherid").val(),

    });
}
$(function(){

    $("#id_teacherid").val(g_args.teacherid);
    $.admin_select_user( $("#id_teacherid"),"teacher", load_data);


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


    $(".violation_num").on("click",function(){
        var teacherid    = $(this).attr('data-teacherid');
        var html_node    = $.obj_copy_node("#id_assign_log");

        BootstrapDialog.show({
            title: "老师违规详情",
            message: html_node,
            closable: true
        });


        $.do_ajax("/ss_deal/get_violation_info",{
                'teacherid': teacherid,
            },function (result) {
                console.log(result);

                if (result['ret'] == 0) {
                    var data = result['data'];

                    var html_str = "";
                    //cancel_num
                    // $.each(data, function (i, item) {
                    var cls = "success";

                    html_str = "<tr class=\"" + cls + "\" > <td>" + '迟到' + "<td>" + data.late_num +"</tr>"+"<tr class=\"" + cls + "\">"+"<td>"+'未评论'+"<td>"+data.comment_num+"</tr>"+"<tr class=\"" + cls + "\">"+"<td>"+'未传课件'+"<td>"+data.tea_cw_num + "<tr class=\"" + cls + "\">" + "<td>"+'未布置作业'+ "<td>" +data.work_num +"</tr>" + "<tr class=\"" + cls + "\">" + "<td>"+'旷课'+ "<td>" +data.cancel_num +"</tr>";
                    // });

                    html_node.find(".data-body").html(html_str);

                }
            }
        );


        // $.ajax({
        //     type: "post",
        //     url: "/ss_deal/get_violation_info",
        //     dataType: "json",
        //     data: {
        //         'teacherid': teacherid,
        //     },
        //     success: function (result) {
        //         console.log(result);

        //         if (result['ret'] == 0) {
        //             var data = result['data'];

        //             var html_str = "";
        //             // $.each(data, function (i, item) {
        //             var cls = "success";

        //             html_str = "<tr class=\"" + cls + "\" > <td>" + '迟到' + "<td>" + data.late_num +"</tr>"+"<tr class=\"" + cls + "\">"+"<td>"+'未评论'+"<td>"+data.comment_num+"</tr>"+"<tr class=\"" + cls + "\">"+"<td>"+'未传课件'+"<td>"+data.tea_cw_num + "<tr class=\"" + cls + "\">" + "<td>"+'未布置作业'+ "<td>" +data.work_num +"</tr>";
        //             // });

        //             html_node.find(".data-body").html(html_str);

        //         }
        //     }
        // });

    });



  $('.opt-change').set_input_change_event(load_data);
});
