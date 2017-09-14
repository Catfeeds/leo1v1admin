/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-online_user_count_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
      date_type:	$('#id_date_type').val(),
      opt_date_type:	$('#id_opt_date_type').val(),
      start_time:	$('#id_start_time').val(),
      end_time:	$('#id_end_time').val(),
      week_flag:	$('#id_week_flag').val()
        });
    }

  Enum_map.append_option_list("boolean",$("#id_week_flag"));

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

  $('#id_week_flag').val(g_args.week_flag);

  $('.opt-change').set_input_change_event(load_data);

    var online_count_list=[];
    $.each( g_data_ex_list.time_list,function(j,item_list){
        //(i*300)*1000+86400-3600*8
        online_count_list[j]=[];
        $.each(item_list ,function(i, item){
            if (j==0) {
                online_count_list[j].push([i*60000, item["online_count"] ]);
            }else if(j==2){
                online_count_list[j].push([i*60000, item["value"] ]);
            }else{
                online_count_list[j].push([i*300000, item ]);
            }
        } )
    });

    $("#id_pic_user_count").css({
        "height"  : "400px",
        "width"  : "95%"
    });


    var show_plot=function( ) {
        var id_name="id_pic_user_count";
        var plot_data_list=[];
        var start_time=$.strtotime( g_args.start_time);

        $.each( online_count_list , function(i,item_list)   {

            //var date=$.DateFormat( start_time-i*86400, "MM-dd" );
            if (i==0) {
                plot_data_list.push({
                    data: online_count_list[0],
                    lines: { show: true,
                             lineWidth: 2},
                    label: "实际",

                    color: "red",
                });
            }else if (i==1 ){
                plot_data_list.push({
                    data: online_count_list[i],
                    lines: { show: true,
                             lineWidth:1
                           },
                    label: "预期",
                });
            }else{
                plot_data_list.push({
                    data: online_count_list[i],
                    lines: { show: true,
                             lineWidth:1
                           },
                    label: "课后视频未处理",
                });
            }

        });


      var plot=$.plot("#"+id_name, plot_data_list.reverse() , {
        series: {
          lines: {
            show: true
          },

          points: {
            show: false
          }

        }, yaxes: [{
            min: 0
            }], xaxis: {
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
                    return "时间:"+ item.series.label+" "+ $.DateFormat( (data_item[0]) /1000+57600 ,"hh:mm")  + "<br/>课数:"+ data_item[1];
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
