/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-subject_transfer.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    var start_time = $("#id_start_time").val();
    var end_time   = $("#id_end_time").val();
    $.reload_self_page ( {
		    "start_time" : start_time,
            "end_time" : end_time,
    });
}
$(function(){



    $('#id_start_time').val(g_args.start_time);
    $('#id_end_time').val(g_args.end_time);
    $("#id_pic_user_count").css({
        "height"  : "400px",
        "width"  : "95%"
    });
    var subject_chinese=[];
    var subject_math=[];
    var subject_english=[];


    $.each( g_data_ex_list,function(i,item){
        if (item["title"] !="全部") {
            subject_chinese.push([ item["title"], item["subject_chinese"]>0?item["subject_chinese"]:0 ]);
            subject_math.push([ item["title"], item["subject_math"]>0?item["subject_math"]:0 ]);
            subject_english.push([ item["title"], item["subject_english"]>0?item["subject_english"]:0 ]);
        }
    });

    var show_plot=function( ) {
        var id_name="id_pic_user_count";
        var plot_data_list=[];
        plot_data_list.push(
            {
                data: subject_chinese,
                lines: { show: true
                         , lineWidth: 2},
                label: "语文"
            });

        plot_data_list.push(
            {
                data: subject_math,
                lines: { show: true
                         , lineWidth: 2},
                label: "数学"
            });

        plot_data_list.push(
            {
                data: subject_english,
                lines: { show: true
                         , lineWidth: 2},
                label: "英语"
            });

        var plot=$.plot("#"+id_name, plot_data_list , {
            series: {
                lines: {
                    show: true,
                    colors: ["#00c0ef", "#dd4b39", "#f39c12"]
                },

                points: {
                    show: true
                }

            },
            xaxis: {
                mode: "categories",
                tickLength: 0
            },
            yaxis:{
                min: 0,
                max: 100,
                tickSize: 10,        
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
            ,legend: {
                show: true ,
                position:"nw"
            },
            colors: ["#00c0ef", "#dd4b39", "#f39c12"]
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
                    return "日期:"+data_item[0]+ "<br/>"+ item.series.label +":"+data_item[1]+ "<br/>";
                }
                $("#tooltip").html( title_funcion(data_item) ).css({top: item.pageY+5, left: item.pageX+5})
                    .fadeIn(200);
            } else {
                $("#tooltip").hide();
            }
        });


    }
    show_plot();

	$('.opt-change').set_input_change_event(load_data);
});

