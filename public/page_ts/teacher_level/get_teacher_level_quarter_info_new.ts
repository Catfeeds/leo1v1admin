/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_teacher_level_quarter_info_new.d.ts" />

function load_data(){
    $.reload_self_page ( {
        order_by_str: g_args.order_by_str,
    teacher_money_type:	$('#id_teacher_money_type').val()
    });
}

$(function(){

    Enum_map.append_option_list("teacher_money_type", $("#id_teacher_money_type"),true,[1,4,5]);

  $('#id_teacher_money_type').val(g_args.teacher_money_type);

    $(".opt-advance-require").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        BootstrapDialog.confirm("确定要申请晋升吗？", function(val){
            if (val) {
                $.do_ajax( '/teacher_level/set_teacher_advance_require_new', {
                    'teacherid' : teacherid,
                    'level_before':opt_data.level,
                    'level_after':opt_data.level_after,
                });
            }
        });

    });
    $(".opt-advance-require-golden").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;

        BootstrapDialog.confirm("确定要直升金牌吗？", function(val){
            if (val) {
                $.do_ajax( '/teacher_level/set_teacher_advance_require', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.quarter_start,
                    'level_before':opt_data.level,
                    'level_after':opt_data.level_after,
                    'lesson_count':opt_data.lesson_count*100,
                    'lesson_count_score':opt_data.lesson_count_score,
                    'cc_test_num':opt_data.cc_test_num,
                    'cc_order_num':opt_data.cc_order_num,
                    'cc_order_per':opt_data.cc_order_per,
                    'cc_order_score':opt_data.cc_order_score,
                    'other_test_num':opt_data.other_test_num,
                    'other_order_num':opt_data.other_order_num,
                    'other_order_per':opt_data.other_order_per,
                    'other_order_score':opt_data.other_order_score,
                    'record_num':opt_data.record_num,
                    'record_score_avg':opt_data.record_score_avg,
                    'record_final_score':opt_data.record_final_score,
                    'is_refund'  :opt_data.is_refund ,
                    'total_score':opt_data.total_score,
                    'hand_flag':opt_data.hand_flag,
                    "golden_flag":1
                });
            }
        });

    });



    $(".show_refund_detail").on("click",function(){
        var teacherid = $(this).data("teacherid");
        var start_time = g_args.quarter_start;

        $.do_ajax( "/teacher_level/get_teacher_refund_detail_info",{
            "teacherid" :teacherid,
            "start_time":start_time
        },function(resp){
            var title = "学生退费详情";
            var list = resp.data;
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>时间</td><td>学生</td><td>老师责任占比</td></tr></table></div>");
            $.each(list,function(i,item){
                html_node.find("table").append("<tr><td>"+item['apply_time_str']+"</td><td>"+item['nick']+"</td><td>"+item['per']+"%</td></tr>");

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

            dlg.getModalDialog().css("width","800px");

        });

    });

    $("#id_add_teacher").on("click",function(){
        var id_teacherid           = $("<input/>");

        var id_score            = $("<input/>");



        var arr = [
            ["老师", id_teacherid],
            ["总得分", id_score],
        ];

        $.show_key_value_table("新增晋升老师", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {

                $.do_ajax('/teacher_level/add_teacher_advance_info',{
                    "teacherid"              : id_teacherid.val(),
                    "total_score"                : id_score.val(),
                });
            }
        },function(){
            $.admin_select_user(id_teacherid,"teacher");
        });
    });


    $(".opt-add-hand").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var realname = opt_data.realname;
        var start_time = g_args.quarter_start;
        var teacherid = opt_data.teacherid;
        BootstrapDialog.confirm("确定刷新数据吗？", function(val){
            if (val) {
                $.do_ajax( '/teacher_level/update_teacher_advance_info_hand', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.quarter_start,
                    'realname':opt_data.realname
                });
            }
        });



    });

    $(".opt-send-wx").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var realname = opt_data.realname;
        BootstrapDialog.confirm("确定发送微信吗？", function(val){
            if (val) {
                $.do_ajax( '/teacher_level/send_teacher_advince_wx', {
                    'teacherid' : teacherid,
                    'realname':opt_data.realname
                });
            }
        });

        
    });
    
    $('.opt-change').set_input_change_event(load_data);
});
