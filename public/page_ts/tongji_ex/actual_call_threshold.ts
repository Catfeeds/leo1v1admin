/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ex-actual_call_threshold.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        date_type_config : $('#id_date_type_config').val(),
        date_type        : $('#id_date_type').val(),
        opt_date_type    : $('#id_opt_date_type').val(),
        start_time       : $('#id_start_time').val(),
        end_time         : $('#id_end_time').val(),
        adminid          : $('#id_admin_revisiterid').val(),
        called_flag      : $('#id_called_flag').val(),
    });
}
$(function(){
    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
    Enum_map.append_option_list("boolean",$("#id_called_flag"));
    $('#id_called_flag').val(g_args.called_flag);
    $('#id_admin_revisiterid').val(g_args.adminid);
    $.admin_select_user(
        $('#id_admin_revisiterid'),
        "admin", load_data ,false, {
            " main_type": 2,
            select_btn_config: [
                {
                "label": "[已分配]",
                "value": -2
            }, {
                "label": "[未分配]",
                "value": 0
            }]
        }
    );

    $('.opt-change').set_input_change_event(load_data);
    var online_count_list=[];
    var time_list=[];
    var threshold_list=[];
    var threshold_min_list=[];
    var threshold_max_list=[];
    $.each( g_data_ex_list,function(j,item_list){
        $.each(item_list ,function(i, item){
            if(i=='time') {
                time_list.push(item);
            }else if(i=='threshold'){
                threshold_list.push(item);
            }else if(i=='threshold_min'){
                threshold_min_list.push(item);
            }else if(i=='threshold_max'){
                threshold_max_list.push(item);
            }
        })
    });

    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));
    // 指定图表的配置项和数据
    var option = {
        title: {
            text: '抢新拨通率'
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['警戒线','实时接通率','预警线']
        },
        color: ['#FF0000', '#3CB371','#EEC900'],
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line','bar','stack','tiled']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
            type : 'category',
            boundaryGap : false,
            data : time_list
        }
        ],
        yAxis : [
            {
            type : 'value',
            axisLabel : {
                formatter: '{value}'
            },
            min  : 0,
            max  : 100,
            splitNumber:10,
        }
        ],
        series : [
            {
            name:'警戒线',
            type:'line',
            // stack: '总量',
            data:threshold_min_list
        },
            {
            name:'实时接通率',
            type:'line',
            // stack: '总量',
            data:threshold_list
        },
            {
            name:'预警线',
            type:'line',
            // stack: '总量',
            data:threshold_max_list
        }
        ]
    };
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
});
