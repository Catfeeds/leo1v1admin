/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-get_broken_line_order_rate.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		teacherid:	$('#id_teacherid').val(),
		subject:	$('#id_subject').val(),
		teacher_subject:	$('#id_teacher_subject').val(),
		identity:	$('#id_identity').val(),
		grade_part_ex:	$('#id_grade_part_ex').val(),
		tea_status:	$('#id_tea_status').val(),
		teacher_account:	$('#id_teacher_account').val(),
		    fulltime_flag:	$('#id_fulltime_flag').val(),
		    fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
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
	  $('#id_teacherid').val(g_args.teacherid);
	  $('#id_subject').val(g_args.subject);
	  $('#id_teacher_subject').val(g_args.teacher_subject);
	  $('#id_identity').val(g_args.identity);
	  $('#id_grade_part_ex').val(g_args.grade_part_ex);
	  $('#id_tea_status').val(g_args.tea_status);
	  $('#id_teacher_account').val(g_args.teacher_account);
	  $('#id_fulltime_flag').val(g_args.fulltime_flag);
	  $('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);

    // //Get the context of the canvas element we want to select
    // var ctx = document.getElementById("myChart").getContext("2d");
    // var myNewChart = new Chart(ctx).PolarArea(data);

    // //Get context with jQuery - using jQuery's .get() method.
    // var ctx = $("#myChart").get(0).getContext("2d");
    // //This will get the first returned node in the jQuery collection.
    // var myNewChart = new Chart(ctx);

    // new Chart(ctx).PolarArea(data,options);



    var online_count_list=[];
    $.each( g_data_ex_list.time_list,function(j,item_list){
        //(i*300)*1000+86400-3600*8
        online_count_list[j]=[];
        $.each(item_list ,function(i, item){
            online_count_list[j].push([i*300000, item]);
        } )
    });

    // console.log(online_count_list);


    $("#id_pic_user_count").css({
        "height"  : "400px",
        "width"  : "95%"
    });


    var show_plot=function() {
        var id_name="id_pic_user_count";
        var plot_data_list=[];
        var start_time=$.strtotime(g_args.start_time);
        $.each( online_count_list , function(i,item_list)   {
            var date=$.DateFormat( start_time-i*86400, "YY-MM" );
            if (i==0) {
                plot_data_list.push({
                    data: online_count_list[0],
                    lines: { show: true,
                             lineWidth: 2},
                    label: date ,
                    color: "red",
                });
            }else{
                plot_data_list.push({
                    data: online_count_list[i],
                    lines: { show: true,
                             lineWidth:1
                           },
                    label: date,
                });
            }
        });


        // var plot=$.plot("#"+id_name, [[[10, 10], [40, 80]]] , { // 测试
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
                timeformat: "%M:%Y",
                minTickSize: [1, "month"]
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

    }
    show_plot();



	  $('.opt-change').set_input_change_event(load_data);
});
