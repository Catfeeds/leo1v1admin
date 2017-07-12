/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-user_count.d.ts" />

function load_data(){

    $.reload_self_page ( {
        order_by_str                   : g_args.order_by_str,
        start_time                     : $('#id_start_time').val(),
        end_time                       : $('#id_end_time').val(),
			stu_test_paper_flag:	$('#id_stu_test_paper_flag').val(),
			  grade:	$('#id_grade').val(),
        check_add_time_count           : $('#id_check_add_time_count').iCheckValue(),
        check_first_revisit_time_count : $('#id_check_first_revisit_time_count').iCheckValue(),
        check_test_lesson_count        : $('#id_check_test_lesson_count').iCheckValue(),
        check_call_old_count           : $('#id_check_call_old_count').iCheckValue(),
        check_order_count              : $('#id_check_order_count').iCheckValue(),
        admin_revisiterid              : $('#id_admin_revisiterid').val(),
        seller_groupid_ex              : $('#id_seller_groupid_ex').val(),
        opt_date_type                  : $('#id_opt_date_type').val()
    });
}

$(function(){

	  Enum_map.append_option_list("boolean",$("#id_stu_test_paper_flag"));
	  $('#id_stu_test_paper_flag').val(g_args.stu_test_paper_flag);

    $("#id_date_range").select_date_range({
        "opt_date_type" : g_args.opt_date_type,
        "start_time"    : g_args.start_time,
        "end_time"      : g_args.end_time,
        onQuery :function() {
            load_data();
        }
    });
    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
	  $('#id_grade').val(g_args.grade);

    $.enum_multi_select($("#id_grade"),"grade",function(){
        load_data();
    } ) ;
    $("#id_seller_groupid_ex").init_seller_groupid_ex(g_adminid_right);

    $.do_ajax( "/authority/get_group_user_list_ex", {
        groupid:1
    },function(data){
        var user_list=data.user_list;
        var $select=$("#id_admin_revisiterid" );
        $.each(user_list, function(i,item){
            $select.append( "<option value="+item.adminid+"> "+item.admin_nick+ " </option>" );
        });
        $("#id_admin_revisiterid").val(g_args.admin_revisiterid);
    });


    if ( g_args.check_add_time_count ) {
        $("#id_check_add_time_count").iCheck("check");
    }
    if ( g_args.check_first_revisit_time_count ) {
        $("#id_check_first_revisit_time_count").iCheck("check");
    }
    if ( g_args.check_test_lesson_count ) {
        $("#id_check_test_lesson_count").iCheck("check");
    }

    if ( g_args.check_call_old_count ) {
        $("#id_check_call_old_count").iCheck("check");
        if(g_args.seller_groupid_ex){
            $("#id_check_call_old_count").iCheck("uncheck");
        }
    }
    if ( g_args.check_order_count ) {
        $("#id_check_order_count").iCheck("check");
    }



    $(".opt-check").on("ifChanged",function(){
        show_plot();
    } );





    $('.opt-change').set_input_change_event(load_data);


    $("#id_pic_user_count").css({
        "height"  : "400px",
        "width"  : "95%"
    });

    var add_time_list=[];
    var user_list=[];
    var first_revisit_time_list=[];
    var test_lesson_list=[];
    var call_old_list=[];
    var order_list=[];
    g_data_ex_list.sort(function(a,b){
        var a_v =a["title"];
        var b_v =b["title"];
        if(a_v>b_v )return  1 ;
        else if (  a_v==b_v ) return 0;
        else return -1;
    });

    $.each( g_data_ex_list,function(i,item){
        if (item["title"] !="全部") {
            add_time_list.push([ item["title"], item["add_time_count"]>0?item["add_time_count"]:0 ]);
            first_revisit_time_list.push([ item["title"], item["first_revisit_time_count"]>0?item["first_revisit_time_count"]:0 ]);
            test_lesson_list.push([ item["title"], item["test_lesson_count"]>0?item["test_lesson_count"]:0 ]);
            var call_old_count= (item["call_count"]? item["call_count"]:0)- (item["first_revisit_time_count"]?item["first_revisit_time_count"]:0);
            if (call_old_count<0) call_old_count=0;
            call_old_list.push([ item["title"], call_old_count]);
            order_list.push([ item["title"], item["order_count_new"]>0?item["order_count_new"]:0 ]);
        }
    });

    var show_plot=function( ) {
        var id_name="id_pic_user_count";
        var plot_data_list=[];
        if ($("#id_check_add_time_count").iCheckValue() ) {
            plot_data_list.push(
                {
                    data: add_time_list,
                    lines: { show: true
                             , lineWidth: 0.3},
                    label: "新进例子数"
                    //color: "rgb(50,50,255)",
                });
        }

        if ($("#id_check_first_revisit_time_count").iCheckValue()  ) {
            plot_data_list.push(
                {
                    data: first_revisit_time_list,
                    lines: { show: true
                             , lineWidth: 0.3},
                    label: "消耗例子数"
                });
        }

        if ($("#id_check_call_old_count").iCheckValue()  ) {
            plot_data_list.push(
                {
                    data: call_old_list ,
                    lines: { show: true
                             , lineWidth: 0.3},
                    label: "回访旧例子数"
                });
        }

        if ($("#id_check_test_lesson_count").iCheckValue() ) {
            plot_data_list.push({
                data: test_lesson_list ,
                lines: { show: true
                         , lineWidth: 0.3},
                label: "排课数"
            });
        }

        if ($("#id_check_order_count").iCheckValue() ) {
            plot_data_list.push({
                data: order_list ,
                lines: { show: true
                         , lineWidth: 0.3},
                label: "新签合同数"
            });
        }



        var plot=$.plot("#"+id_name, plot_data_list , {
            series: {
                lines: {
                    show: true
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
                    return "日期:"+data_item[0]+ "<br/>"+ item.series.label +":"+data_item[1]+ "<br/>";
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



    var get_row_date_query_str=function( a_link )  {
        var opt_data=$(a_link).parent().parent().find(".row-data").get_self_opt_data();
        var start_time = g_args.start_time.substr(0,4)+"-"+opt_data.title ;
        var end_time  = start_time ;
        var opt_date_type =1;
        if (opt_data.title  =="全部" ) {
            start_time=g_args.start_time;
            end_time=g_args.end_time;
            opt_date_type = 0;
        }
        return "&start_time="+start_time +
            "&end_time="+end_time +
            "&opt_date_type="+opt_date_type+
            "&seller_groupid_ex="+g_args.seller_groupid_ex;
    };


    $(".id_test_lesson_each").on("click",function(){
        var date_str=get_row_date_query_str(this);
        $.wopen("/seller_student_new2/test_lesson_plan_list?accept_flag=1&date_type=4&"+date_str );
    });

    $(".id_test_lesson_count_succ_each").on("click",function(){
        var date_str=get_row_date_query_str(this);
        $.wopen("/seller_student_new2/test_lesson_plan_list?accept_flag=1&success_flag=-2&date_type=4&"+date_str );
    });

    $(".id_test_lesson_count_fail_need_money").on("click",function(){
        var date_str=get_row_date_query_str(this);
        $.wopen("/seller_student_new2/test_lesson_plan_list?accept_flag=1&success_flag=2&test_lesson_fail_flag=-2&date_type=4&"+date_str );
    });

    $(".id_seller_require_test_lesson_count").on("click",function(){
        var date_str=get_row_date_query_str(this);
        $.wopen("/seller_student_new2/test_lesson_plan_list?require_admin_type=2&accept_flag=1&date_type=1&"+date_str );
    });


    $(".id_seller_test_lesson_count").on("click",function(){
        var date_str=get_row_date_query_str(this);
        $.wopen("/seller_student_new2/test_lesson_plan_list?require_admin_type=2&accept_flag=1&date_type=4&"+date_str );
    });

    $(".id_seller_test_lesson_count_succ").on("click",function(){
        var date_str=get_row_date_query_str(this);
        $.wopen("/seller_student_new2/test_lesson_plan_list?accept_flag=1&success_flag=-2&date_type=4&require_admin_type=2&"+date_str );

    });


    $(".id_seller_test_lesson_count_fail_need_money").on("click",function(){
        var date_str=get_row_date_query_str(this);
        $.wopen("/seller_student_new2/test_lesson_plan_list?accept_flag=1&success_flag=2&test_lesson_fail_flag=-2&date_type=4&require_admin_type=2&"+date_str );
    });

    show_plot();

});
