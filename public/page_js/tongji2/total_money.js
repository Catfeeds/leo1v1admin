/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-total_money.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
                  date_type:    $('#id_date_type').val(),
                  opt_date_type:    $('#id_opt_date_type').val(),
                  start_time:   $('#id_start_time').val(),
                  end_time: $('#id_end_time').val()
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


      $('.opt-change').set_input_change_event(load_data);

    $("#id_pic_user_count").css({
        "height"  : "400px",
        "width"  : "95%"
    });

    var user_count_list=[];
    var lesson_user_count_list=[];
    g_data_ex_list.sort(function(a,b){
        var a_v =a["title"];
        var b_v =b["title"];
        if(a_v>b_v )return  1 ;
        else if (  a_v==b_v ) return 0;
        else return -1;
    });

    $.each( g_data_ex_list,function(i,item){
        if (item["title"] !="全部") {
            user_count_list.push([ item["title"], item["user_count"]>0?item["user_count"]:0 ]);
            lesson_user_count_list.push([ item["title"], item["lesson_user_count"]>0?item["lesson_user_count"]:0 ]);
        }
    });

    var show_plot = function() {
        var id_name        = "id_pic_user_count";
        var plot_data_list = [];
        plot_data_list.push({
            data: lesson_user_count_list,
            lines: {
                show: true,
                lineWidth: 0.3,
            },
            label: "金额"
        });

        var plot = $.plot("#"+id_name, plot_data_list , {
            series: {
                lines: {
                    show: true,
                },points: {
                    show: true
                }
            },xaxis: {
                mode: "categories",
                tickLength: 0
            },grid: {
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
            ,legend: {
                show: true ,
                position:"nw"
            }
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
                    return "时间:"+data_item[0]+ "<br/>"+ item.series.label +":"+data_item[1]+ "<br/>";
                }
                $("#tooltip").html( title_funcion(data_item) ).css({top: item.pageY+5, left: item.pageX+5})
                    .fadeIn(200);
            } else {
                $("#tooltip").faseIn();
            }
        });


    }
    show_plot();



});

