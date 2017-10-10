
/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-contract.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      start_time:	$('#id_start_time').val(),
			      end_time:	$('#id_end_time').val(),
			      opt_date_type:	$('#id_opt_date_type').val(),
			      date_type:	$('#id_date_type').val(),
            stu_from_type : $("#id_stu_from_type").val(),
			      contract_type:	$('#id_contract_type').val(),

        });
    }

    $("#id_date_range").select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        timepicker : true,
        onQuery :function() {
            load_data();
        }
    });

    Enum_map.append_option_list( "contract_from_type", $("#id_stu_from_type"));
	Enum_map.append_option_list("contract_type",$("#id_contract_type")); 
    $("#id_stu_from_type").val(g_args.stu_from_type);
	$('#id_contract_type').val(g_args.contract_type);

    var date_v=new Date(1000);


	$('.opt-change').set_input_change_event(load_data);

    
    $("#id_contract_money, #id_contract_user").css({
        "height"  : "400px", 
        "width"  : "95%" 
    });

   var money_list=[];
   var user_list=[];
    $.each( g_data_ex_list,function(i,item){
		money_list.push([ item["title"], item["money"]>0?item["money"]:0 ]);
		user_list.push([ item["title"], item["order_count"]>0?item["order_count"]:0 ]);
    });

    var show_plot=function( id_name,label, data_list,color,title_funcion ) {
	    var plot=$.plot("#"+id_name, [
            {
                data: data_list,
                lines: { show: true
                         , lineWidth: 0.3, fill: 0.2 },
                color:color,
                label:label
                //color: "rgb(50,50,255)",
            }
        ], {
		    series: {
			    lines: {
				    show: true,
                    fill:true
			    },

			    points: {
				    show: true
			    }

		    },
		    xaxis: {
			    mode: "categories",
			    tickLength: 0
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

                
				$("#tooltip").html( title_funcion(data_item) ).css({top: item.pageY+5, left: item.pageX+5})
					.fadeIn(200);
			} else {
				$("#tooltip").hide();
			}
	    });

	    $("#id"+id_name).bind("plotclick", function (event, pos, item) {
		    if (item) {
			    $("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
			    plot.highlight(item.series, item.datapoint);
		    }
	    });
    }

    show_plot("id_contract_money","金额",money_list,null,function(data_item){
        return "日期:"+data_item[0]+ "<br/>" +"金额:"+data_item[1]+ "<br/>";
    });

    show_plot("id_contract_user","人数",user_list, "rgb(50,50,255)",function(data_item){
        return "日期:"+data_item[0]+ "<br/>" +"人数:"+data_item[1]+ "<br/>";
    });


    
});


