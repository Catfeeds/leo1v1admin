/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-day_report.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
            seller_groupid_ex:	$('#id_seller_groupid_ex').val()

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

	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);

    $("#id_seller_groupid_ex").init_seller_groupid_ex();


	$('.opt-change').set_input_change_event(load_data);
    init_flot();
    init_flot_set_lesson();
    init_flot_test_lesson();
});


function init_flot() {
    $("#id_pic_new_money").css({
        "height"  : "400px", 
        "width"  : "95%" 
    });

    var order_list=[];
    var percent_list=[];
    
    var max_persent=100;
    $.each( g_data_ex_list.month_date_money_list,function(i,item){
        var time=$.strtotime(i)*1000;
		order_list.push([time , item["money"]>0?item["money"]:0 ]);
        if (max_persent< item["month_finish_persent"] ){
            max_persent= item["month_finish_persent"] ;
        }
		percent_list.push([ time, item["month_finish_persent"] ]);
    });

    var barOptions = {
        series: {
        },
        xaxis: {
            mode: "time",
            timeformat: "%m-%d",
            minTickSize: [1, "day"]
        },
        yaxes: [{
            min: 0
        }, {
            // align if we are to the right
            min: 0,
            max: max_persent,
            alignTicksWithAxis:  1, 
            position: "right",
            tickFormatter: function (v, axis) {
                return  ""+ parseInt(v) +"%";
            }
        }],
        grid: {
            hoverable: true
        },
        legend: {
            show: true ,
            position:"nw"
        },
        tooltip: true,
        tooltipOpts: {
            content: "x: %x, y: %y"
        }
    };
    var barData = {
        label: "新签金额",
        data:  order_list,
        bars: {
            show: true,
            barWidth: 46400000
        }

    };
    var lineData= {
        label: "月度新签完成率",
        data:  percent_list,
        yaxis: 2,
        lines: {
            show: true,
        }
		,points: {
			show: true
		}

    };


    $.plot($("#id_pic_new_money"), [barData,lineData], barOptions);

	    $("<div id='tooltip'></div>").css({
		    position: "absolute",
		    display: "none",
		    border: "1px solid #fdd",
		    padding: "2px",
		    "background-color": "#fee",
		    opacity: 0.80
	    }).appendTo("body");

	    $("#id_pic_new_money" ).bind("plothover", function (event, pos, item) {
			if (item) {
                var data_item=item.series.data[item.dataIndex];

                var title_funcion=function( date_item) {
                    return "日期:"+$.DateFormat(data_item[0]/1000,"MM-dd")+  "<br/>"+ item.series.label +":"+data_item[1]+ "<br/>";
                }
				$("#tooltip").html( title_funcion(data_item) ).css({top: item.pageY+5, left: item.pageX+5})
					.fadeIn(200);
			} else {
				$("#tooltip").hide();
			}
	    });



}
function init_flot_set_lesson() {
    $("#id_pic_set_lesson").css({
        "height"  : "400px", 
        "width"  : "95%" 
    });

    var set_lesson_list=[];
    $.each( g_data_ex_list.month_date_set_lesson_list ,function(i,item){
        var time=$.strtotime(i)*1000;
		set_lesson_list.push([time , item["set_lesson_count"]>0?item["set_lesson_count"]:0 ]);
    });

    var barOptions = {
        series: {
        },
        xaxis: {
            mode: "time",
            timeformat: "%m-%d",
            minTickSize: [1, "day"]
        },
        yaxes: [{
            min: 0
        }],
        grid: {
            hoverable: true
        },
        legend: {
            show: true ,
            position:"nw"
        },
    };
    var barData = {
        label: "排课数",
        data:  set_lesson_list,
        color:  "#729FCF",
        bars: {
            show: true,
            barWidth: 46400000
        }

    };


    $.plot($("#id_pic_set_lesson"), [barData], barOptions);


	    $("#id_pic_set_lesson" ).bind("plothover", function (event, pos, item) {
			if (item) {
                var data_item=item.series.data[item.dataIndex];

                var title_funcion=function( date_item) {
                    return "日期:"+$.DateFormat(data_item[0]/1000,"MM-dd")+  "<br/>"+ item.series.label +":"+data_item[1]+ "<br/>";
                }
				$("#tooltip").html( title_funcion(data_item) ).css({top: item.pageY+5, left: item.pageX+5})
					.fadeIn(200);
			} else {
				$("#tooltip").hide();
			}
	    });



}


function init_flot_test_lesson() {
    var id_name="id_pic_test_lesson";
    $("#"+id_name).css({
        "height"  : "400px", 
        "width"  : "95%" 
    });

    var test_lesson_list=[];
    var percent_list=[];
    $.each( g_data_ex_list.month_date_test_lesson_list,function(i,item){
        var time=$.strtotime(i)*1000;
		test_lesson_list.push([time , item["test_lesson_count"]>0?item["test_lesson_count"]:0 ]);
		percent_list.push([ time, item["test_lesson_fail_percent"] ]);
    });

    var barOptions = {
        series: {
        },
        xaxis: {
            mode: "time",
            timeformat: "%m-%d",
            minTickSize: [1, "day"]
        },
        yaxes: [{
            min: 0
        }, {
            // align if we are to the right
            min: 0,
            max: 100,
            alignTicksWithAxis:  1, 
            position: "right",
            tickFormatter: function (v, axis) {
                return  ""+ parseInt(v) +"%";
            }
        }],
        grid: {
            hoverable: true
        },
        legend: {
            show: true ,
            position:"nw"
        },
        tooltip: true,
        tooltipOpts: {
            content: "x: %x, y: %y"
        }
    };
    var barData = {
        label: "上课数",
        data:  test_lesson_list,
        color:  "#EF6464",
        bars: {
            show: true,
            barWidth: 46400000
        }

    };
    var lineData= {
        label: "试听失败率",
        data:  percent_list,
        color:  "#729FCF",
        yaxis: 2,
        lines: {
            show: true,
        }
		,points: {
			show: true
		}

    };


    $.plot($("#"+id_name), [barData,lineData], barOptions);

	    $("<div id='tooltip'></div>").css({
		    position: "absolute",
		    display: "none",
		    border: "1px solid #fdd",
		    padding: "2px",
		    "background-color": "#fee",
		    opacity: 0.80
	    }).appendTo("body");

	    $("#"+id_name ).bind("plothover", function (event, pos, item) {
			if (item) {
                var data_item=item.series.data[item.dataIndex];

                var title_funcion=function( date_item) {
                    return "日期:"+$.DateFormat(data_item[0]/1000,"MM-dd")+  "<br/>"+ item.series.label +":"+data_item[1]+ "<br/>";
                }
				$("#tooltip").html( title_funcion(data_item) ).css({top: item.pageY+5, left: item.pageX+5})
					.fadeIn(200);
			} else {
				$("#tooltip").hide();
			}
	    });



}

