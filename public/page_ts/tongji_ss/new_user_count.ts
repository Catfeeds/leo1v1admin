/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-new_user_count.d.ts" />

function load_data(){
    $.reload_self_page ( {
    date_type:	$('#id_date_type').val(),
    opt_date_type:	$('#id_opt_date_type').val(),
    start_time:	$('#id_start_time').val(),
    end_time:	$('#id_end_time').val(),
    origin_ex:	$('#id_origin_ex').val()
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


  $('#id_origin_ex').val(g_args.origin_ex);
  $('.opt-change').set_input_change_event(load_data);

    var online_count_list=[];
    $.each( g_data_ex_list.new_user_count_list,function(i, item){
    online_count_list.push([item["opt_time"]*1000+8*3600000, item["count"] ]);
    });
    var m_html_count_list=[];

    $.each( g_data_ex_list.m_html_count_list,function(i, item){
       m_html_count_list.push([item["log_time"]*1000+8*3600000, item["count"] ]);
    });

    var m_html_book_list=[];
    $.each( g_data_ex_list.m_html_book_list,function(i, item){
       m_html_book_list.push([item["log_time"]*1000+8*3600000, item["count"] ]);
    });



    $("#id_pic_user_count").css({
        "height"  : "400px",
        "width"  : "95%"
    });


    var show_plot=function( ) {
        var id_name="id_pic_user_count";
        var plot_data_list=[];
        var start_time=$.strtotime( g_args.start_time);

        plot_data_list.push({
            data: online_count_list,
            lines: { show: true },
            points: { show: true },
            label: "例子增加量" ,
            color: "red",
        });


        plot_data_list.push({
            data: m_html_count_list ,
            lines: { show: true },
            points: { show: true },
            label: "网页打开量" ,
            color: "blue",
        });

        plot_data_list.push({
            data: m_html_count_list ,
            lines: { show: true },
            points: { show: true },
            label: "预约请求量" ,
            color: "green",
        });







      var plot=$.plot("#"+id_name, plot_data_list, {
        series: {


        },
          yaxes: [{
            min: 0
        }],
          xaxis: {
                mode: "time",
                timeformat: "%H:%M",
                minTickSize: [1, "hour"]
        },
            legend: {
                show: true ,
                position:"nw"
            },

        grid: {
          hoverable: true,
          clickable: true,
        backgroundColor: { colors: [ "#fff", "#eee" ] },
        borderWidth: {
          top: 1,
          right: 1,
          bottom: 2,
          left: 2
        }

        }
            ,shadowSize:0

      });

      $("<div id='tooltip'></div>").css({
        position: "absolute",
        display: "none",
        border: "1px solid #fdd",
        padding: "2px",
        "background-color": "#fee",
        opacity: 0.80
      }).appendTo("body");

      $("#"+id_name).bind("plothover", function (event, pos, item) {
      if (item) {
                var data_item=item.series.data[item.dataIndex];


                var title_funcion=function( date_item) {
                    return "时间:"+ item.series.label+" "+ $.DateFormat( (data_item[0]) /1000+57600 ,"hh:mm")  + "<br/>新增:"+ data_item[1];
                }
        $("#tooltip").html( title_funcion(data_item) ).css({top: item.pageY+5, left: item.pageX+5})
          .fadeIn(200);
      } else {
        $("#tooltip").hide();
      }
      });


        /*
      $("#id"+id_name).bind("plotclick", function (event, pos, item) {
        if (item) {
          $("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
          plot.highlight(item.series, item.datapoint);
        }
      });
        */
    }
    show_plot();

});
