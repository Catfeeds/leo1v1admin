/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-seller_test_lesson_info_tongji.d.ts" />
function load_data(){
    $.reload_self_page ( {
        order_by_str: g_args.order_by_str,
        date_type_config:	$('#id_date_type_config').val(),
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val(),
        show_flag:	$('#id_show_flag').val(),
        lesson_money:	$('#id_lesson_money').val(),
        seller_flag:	$('#id_seller_flag').val(),

    });
}

$(function(){


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
    $('#id_order_by_str').val(g_args.order_by_str);
    $('#id_show_flag').val(g_args.show_flag);
    $('#id_seller_flag').val(g_args.seller_flag);
    $('#id_lesson_money').val(g_args.lesson_money);

    var seller_flag = $('#id_seller_flag').val();

    if(seller_flag == 1){
        $('.show_body td:nth-child(2)').html('老师');
        $('.show_body td:nth-child(11)').html('销售签单率<a href="javascript:;" class="fa td-sort-item fa-sort-down" data-field-name="tea_per"> </a>');
        $('.data').children("a:last-child").text('查看销售转化率');
    }else{
        $('.show_body td:nth-child(2)').html('销售');
        $('.show_body td:nth-child(11)').html('老师签单率<a href="javascript:;" class="fa td-sort-item fa-sort-down" data-field-name="tea_per"> </a>');
        $('.data').children("a:last-child").text('查看老师转化率');
    }


    $(".opt-teacher-lesson-per").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var adminid = opt_data.cur_require_adminid;
        console.log('身份'+seller_flag);

        if(adminid>0){
            $.do_ajax('/tongji_ss/get_seller_teacher_test_lesson_per',{
                "seller_flag":seller_flag,
                "adminid" : adminid,
                "start_time":g_args.start_time,
                "end_time":g_args.end_time
            },function(resp) {
                var per = resp.data;
                BootstrapDialog.alert("转化率:"+per+"%");

            });

        }else{
            BootstrapDialog.alert("请选择销售!");
        }

    });

    $(".success_lesson").on("click",function(){
        var adminid = $(this).data("adminid");
        console.log(adminid);
        //alert(adminid);
        if(adminid > 0){
            var title     = "试听成功详情";
            var html_node = $("<div id=\"div_table\"><table class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>老师</td><td>学生</td><td>年级</td><td>科目</td><td>合同</td><td width=\"120px\">签约失败说明</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_seller_test_lesson_success_info',{
                "adminid" : adminid,
                "start_time":g_args.start_time,
                "end_time":g_args.end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"];
                    var realname = item["realname"];
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    // var rev = item["rev"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+realname+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td><td>"+item["have_order"]+"</td><td>"+item["test_lesson_order_fail_desc"]+"</td></tr>");
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

            dlg.getModalDialog().css("width","1024px");

        }

    });


    $('.opt-change').set_input_change_event(load_data);
});
