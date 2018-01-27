/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ex-actual_call_threshold.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
        $.reload_self_page ( {
        date_type_config:    $('#id_date_type_config').val(),
        date_type:    $('#id_date_type').val(),
        opt_date_type:    $('#id_opt_date_type').val(),
        start_time:    $('#id_start_time').val(),
        end_time:    $('#id_end_time').val()
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

    $('.opt-change').set_input_change_event(load_data);


    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));
    // 指定图表的配置项和数据
    var option = {
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['警戒线','实时接通率','预警线']
        },
        color: ['#FF0000', '#3CB371','#EEC900'],
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
            type : 'category',
            boundaryGap : false,
            data : ['周一','周二','周三','周四','周五','周六','周日']
        }
        ],
        yAxis : [
            {
            type : 'value'
        }
        ],
        series : [
            {
            name:'警戒线',
            type:'line',
            stack: '总量',
            data:[120, 132, 101, 134, 90, 230, 210]
        },
            {
            name:'实时接通率',
            type:'line',
            stack: '总量',
            data:[220, 182, 191, 234, 290, 330, 310]
        },
            {
            name:'预警线',
            type:'line',
            stack: '总量',
            data:[150, 232, 201, 154, 190, 330, 410]
        }
        ]
    };


    // var option = {
    //     title: {
    //         text: 'ECharts 入门示例'
    //     },
    //     tooltip: {},
    //     legend: {
    //         data:['销量']
    //     },
    //     xAxis: {
    //         data: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"]
    //     },
    //     yAxis: {},
    //     series: [{
    //         name: '销量',
    //         type: 'bar',
    //         data: [5, 20, 36, 10, 10, 20]
    //     }]
    // };


    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
});
