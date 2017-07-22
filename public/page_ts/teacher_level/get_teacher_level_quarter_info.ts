/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_teacher_level_quarter_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacher_money_type:	$('#id_teacher_money_type').val()
        });
    }

    Enum_map.append_option_list("teacher_money_type", $("#id_teacher_money_type"),true,[1,4,5]);

	$('#id_teacher_money_type').val(g_args.teacher_money_type);

    $(".opt-advance-require").on("click",function(){        
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
            


        BootstrapDialog.confirm("确定要申请晋升吗？", function(val){
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
                    'total_score':opt_data.total_score
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

	$('.opt-change').set_input_change_event(load_data);
});







