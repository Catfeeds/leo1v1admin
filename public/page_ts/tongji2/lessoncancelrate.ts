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

    // $(window).on("load",function(){

        console.log('cc'+dateArr);
        console.log(rateArr);
        var chart = Highcharts.chart('container', {
            chart: {
                type: 'line'
            },
            title: {
                text: '课次取消率'
            },
            xAxis: {
                categories: dateArr
            },
            yAxis: {
                title: {
                    text: '取消率(%)'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true,// 开启数据标签
                        format : '{y:.2f}%'
                    },
                    enableMouseTracking: true // 关闭鼠标跟踪，对应的提示框、点击事件会失效
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{point.key}</span><br>',
                pointFormat: '<span >取消率</span>: <b>{point.y:.2f}%</b><br/>'
            },

            series: [{
                name: '取消率',
                data: rateArr
            }]
        });

    // });


  $('.opt-change').set_input_change_event(load_data);
});
