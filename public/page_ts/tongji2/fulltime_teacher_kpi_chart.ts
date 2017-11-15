/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-fulltime_teacher_kpi_chart.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    var start_time = $("#id_start_time").val();
    var end_time   = $("#id_end_time").val();
    $.reload_self_page ( {
        "start_time" : start_time,
        "end_time"   : end_time,
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
    $('#id_start_time').datetimepicker({
        lang:'ch',
        timepicker:false,
        format:'Y-m-d',
        onChangeDateTime :function(){
            load_data();
        }
    });

    $('#id_end_time').datetimepicker({
        lang:'ch',
        timepicker:false,
        format:'Y-m-d',
        onChangeDateTime :function(){
            load_data();
        }
    });

    init_flot();
    init_flot_set_lesson();
    init_flot_test_lesson();

});
function init_flot(){
    $("#id_pic_cc_transfer").css({
        "height"  : "400px",
        "width"  : "95%"
    });
    var cc_transfer_all=[];
    var cc_transfer_sh=[];
    var cc_transfer_wh=[];
    $.each( g_data_ex_list,function(i,item){
        if (item["title"] !="全部") {

            cc_transfer_all.push([ item["title"], item["cc_transfer_all"]>0?item["cc_transfer_all"]:0 ]);
            cc_transfer_sh.push([ item["title"], item["cc_transfer_sh"]>0?item["cc_transfer_sh"]:0 ]);
            cc_transfer_wh.push([ item["title"], item["cc_transfer_wh"]>0?item["cc_transfer_wh"]:0 ]);
        }
    });

    var show_plot=function( ) {
        var id_name="id_pic_cc_transfer";
        var plot_data_list=[];
        plot_data_list.push(
            {
                data: cc_transfer_all,
                lines: { show: true
                         , lineWidth: 2},
                label: "全职整体"
            });

       
        plot_data_list.push(
            {
                data: cc_transfer_sh,
                lines: { show: true
                         , lineWidth: 2},
                label: "教学一组（上海）"
            });

     
        plot_data_list.push(
            {
                data: cc_transfer_wh,
                lines: { show: true
                         , lineWidth: 2},
                label: "教学二组(武汉)"
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
}


function init_flot_set_lesson(){
    $("#id_pic_lesson_count").css({
        "height"  : "400px",
        "width"  : "95%"
    });
    var lesson_count_all=[];
    var lesson_count_sh=[];
    var lesson_count_wh=[];
    $.each( g_data_ex_list,function(i,item){
        if (item["title"] !="全部") {

            lesson_count_all.push([ item["title"], item["lesson_count_all"]>0?item["lesson_count_all"]:0 ]);
            lesson_count_sh.push([ item["title"], item["lesson_count_sh"]>0?item["lesson_count_sh"]:0 ]);
            lesson_count_wh.push([ item["title"], item["lesson_count_wh"]>0?item["lesson_count_wh"]:0 ]);
        }
    });

    var show_plot=function( ) {
        var id_name="id_pic_lesson_count";
        var plot_data_list=[];
        plot_data_list.push(
            {
                data: lesson_count_all,
                lines: { show: true
                         , lineWidth: 2},
                label: "全职整体"
            });

       
        plot_data_list.push(
            {
                data: lesson_count_sh,
                lines: { show: true
                         , lineWidth: 2},
                label: "教学一组（上海）"
            });

     
        plot_data_list.push(
            {
                data: lesson_count_wh,
                lines: { show: true
                         , lineWidth: 2},
                label: "教学二组(武汉)"
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
}

function init_flot_test_lesson(){
    $("#id_pic_student_num").css({
        "height"  : "400px",
        "width"  : "95%"
    });
    var student_num_all=[];
    var student_num_sh=[];
    var student_num_wh=[];
    $.each( g_data_ex_list,function(i,item){
        if (item["title"] !="全部") {

            student_num_all.push([ item["title"], item["student_num_all"]>0?item["student_num_all"]:0 ]);
            student_num_sh.push([ item["title"], item["student_num_sh"]>0?item["student_num_sh"]:0 ]);
            student_num_wh.push([ item["title"], item["student_num_wh"]>0?item["student_num_wh"]:0 ]);
        }
    });

    var show_plot=function( ) {
        var id_name="id_pic_student_num";
        var plot_data_list=[];
        plot_data_list.push(
            {
                data: student_num_all,
                lines: { show: true
                         , lineWidth: 2},
                label: "全职整体"
            });

       
        plot_data_list.push(
            {
                data: student_num_sh,
                lines: { show: true
                         , lineWidth: 2},
                label: "教学一组（上海）"
            });

     
        plot_data_list.push(
            {
                data: student_num_wh,
                lines: { show: true
                         , lineWidth: 2},
                label: "教学二组(武汉)"
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
}