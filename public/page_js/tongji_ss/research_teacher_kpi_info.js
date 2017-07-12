/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-research_teacher_kpi_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      date_type_config : $('#id_date_type_config').val(),
			      date_type        : $('#id_date_type').val(),
			      opt_date_type    : $('#id_opt_date_type').val(),
			      start_time       : $('#id_start_time').val(),
			      end_time         : $('#id_end_time').val(),
			      type_flag        : $('#id_type_flag').val()
        });
    }


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

    
	$('#id_type_flag').val(g_args.type_flag);

    $("#id_opt_date_type").hide();
    function show_top( $person_body_list) {

        $($person_body_list[0]).find("td").css(
            {
                "color" :"red"
            }
        );
        $($person_body_list[1]).find("td").css(
            {
                "color" :"orange"
            }
        );

        $($person_body_list[2]).find("td").css(
            {
                "color" :"blue"
            }
        );

    }


    show_top( $("#id_score_list > tr")) ;
    show_top( $("#id_group_score_list > tr")) ;

    $("#id_read_rule").on("click",function(){
        var title = "评分相关规则";
        var html_node= $("<table class=\"table table-bordered\" ><tr><td align=\"center\">项目</td><td colspan=\"2\" align=\"center\">老师面试(20分)</td><td colspan=\"4\" align=\"center\">监课反馈(20分)</td><td align=\"center\">投诉处理(5分)</td><td colspan=\"3\" align=\"center\">学科培训(20分)</td><td colspan=\"5\" align=\"center\">转化率(35分)</td></tr><tr><td>评分项目</td><td>审核时长</td><td>面试签单率</td><td>新入职反馈时长</td><td>反馈数量</td><td>首次试听转化率</td><td>反馈后转化率提升度</td><td>处理解决效率</td><td>培训次数</td><td>培训参与度</td><td>培训质量</td><td>试听量占比</td><td>签单率</td><td>换老师签单率</td><td>扩课签单率</td><td>转介绍签单率</td></tr><tr><td>分值</td><td>5</td><td>15</td><td>5</td><td>5</td><td>5</td><td>5</td><td>5</td><td>5</td><td>5</td><td>5</td><td>5</td><td>15</td><td>5</td><td>5</td><td>5</td></tr><tr><td>标准线(语数英)</td><td>3天</td><td>18%</td><td>4天</td><td>17</td><td>15%</td><td>3%</td><td>1天</td><td></td><td></td><td></td><td></td><td>17%</td><td>70%</td><td>70%</td><td>70%</td></tr><tr><td>标准线(综合学科)</td><td>3天</td><td>23%</td><td>4天</td><td>17</td><td>20%</td><td>3%</td><td>1天</td><td></td><td></td><td></td><td></td><td>22%</td><td>75%</td><td>75%</td><td>75%</td></tr><tr><td>大于平均分和标准线且排名第一</td><td>5</td><td>15</td><td>5</td><td>5</td><td>5</td><td>5</td><td>5</td><td>5</td><td>5</td><td>5</td><td>5</td><td>15</td><td>5</td><td>5</td><td>5</td></tr><tr><td>大于平均分</td><td>3</td><td>10</td><td>3</td><td>3</td><td>3</td><td>3</td><td>3</td><td>3</td><td>3</td><td>3</td><td>3</td><td>10</td><td>3</td><td>3</td><td>3</td></tr><td>低于平均分</td><td>1</td><td>5</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>5</td><td>1</td><td>1</td><td>1</td></tr></table><br><div style=\"color:red\">备注:</div><div>1.反馈数量标准线是根据当月试听成功数*8%/教研老师数得到,17为参考值</div><br><div>2.试听量占比评分标准如下:</div><div>1)个人占比达到15% 得5分，占比 10% 得3分，占比5% 得1分</div><div>2)学科占比达到30%  得5分，占比20％ 得3分，占比10% 得1分 </div><br><div>3.涉及时长项目,低于平均值分数更多</div><br><div>4.学科培训暂不计分</div>");
        
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

        dlg.getModalDialog().css("width","1200px");

    });


    $(".five_score").each(function(){
        var score = $(this).text();
        if(score == 5){
            $(this).css("color","red").css("font-size","16px").css("font-weight","bold");
        }
        
    });

    $(".twenty_five_score").each(function(){
        var score = $(this).text();
        if(score == 15){
            $(this).css("color","red").css("font-size","16px").css("font-weight","bold");
        }
        
    });

	$('.opt-change').set_input_change_event(load_data);
});

