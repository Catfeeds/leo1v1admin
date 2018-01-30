/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-lessoncancelrate.d.ts" />

function load_data(){
  if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
    // order_by_str : g_args.order_by_str,
    date_type_config:	$('#id_date_type_config').val(),
    date_type:	$('#id_date_type').val(),
    opt_date_type:	$('#id_opt_date_type').val(),
    start_time:	$('#id_start_time').val(),
    end_time:	$('#id_end_time').val()
    });
}
$(function(){

    // $('#id_date_range').select_date_range({
    //     'date_type' : g_args.date_type,
    //     'opt_date_type' : g_args.opt_date_type,
    //     'start_time'    : g_args.start_time,
    //     'end_time'      : g_args.end_time,
    //     "th_input_id" : "th_date_range",
    //     date_type_config : JSON.parse( g_args.date_type_config),
    //     onQuery :function() {
    //         load_data();
    //     }
    // });





     $(window).load(function() {
        alert("hello again");
     });

    var chart = Highcharts.chart('container', {
        chart: {
            type: 'line'
        },
        title: {
            text: '月平均气温'
        },
        subtitle: {
            text: '数据来源: WorldClimate.com'
        },
        xAxis: {
            categories: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月']
        },
        yAxis: {
            title: {
                text: '气温 (°C)'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true          // 开启数据标签
                },
                enableMouseTracking: false // 关闭鼠标跟踪，对应的提示框、点击事件会失效
            }
        },
        series: [{
            name: '东京',
            data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        }, {
            name: '伦敦',
            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        }]
    });



  $('.opt-change').set_input_change_event(load_data);
});
