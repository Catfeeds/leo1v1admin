/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-tongji_change_lesson_for_jy.ts" />
function load_data(){
    $.reload_self_page ( {
        teacher_money_type:	$('#id_teacher_money_type').val(),
		    order_by_str:	g_args.order_by_str,
			  seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
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

    Enum_map.append_option_list('teacher_money_type',$('#id_teacher_money_type'));

	  $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
    $('#id_teacher_money_type').val(g_args.teacher_money_type);

    $("#id_seller_groupid_ex").init_seller_groupid_ex();

    var get_row_date_query_str=function( a_link )  {
        var opt_data=$(a_link).parent().parent().find(".row-data").get_self_opt_data();
        var start_time = g_args.start_time;
        var end_time  = g_args.end_time ;
        var opt_date_type =1;
        var assistantid = opt_data.assistantid;
        if (opt_data.ass_nick  =="全部" ) {
            assistantid = -1;
        }
        return "&start_time="+start_time +
            "&date_type="+g_args.date_type+
            "&end_time="+end_time +
            "&opt_date_type="+g_args.opt_date_type+
            "&assistantid="+assistantid;
    };


    $(".id_valid_count").on("click",function(){
	      var date_str=get_row_date_query_str(this);
	      $.wopen("/tea_manage/lesson_list?"+date_str+"&lesson_type=-2&confirm_flag=-1&subject=-1&grade=-1&studentid=-1&teacherid=-1&test_seller_id=-1&is_with_test_user=0&has_performance=-1&lesson_count=-1&lesson_cancel_reason_type=0");
    });


    $('.show_detail').on("click",function(){
        var teacherid = $(this).attr('date-teacherid');
        var lesson_cancel_reason_type = $(this).attr('date-lesson_cancel_reason_type');
        var start_time = $('#id_start_time').val();
        var end_time   = $('#id_end_time').val();
        var html_node    = $.obj_copy_node("#id_assign_log");

        BootstrapDialog.show({
            title: "详情列表",
            message: html_node,
            closable: true
        });

        $.do_ajax('/ss_deal2/show_change_lesson_by_teacher',{
            'teacherid' : teacherid,
            'start_time':start_time,
            'end_time'  : end_time,
            'lesson_cancel_reason_type':lesson_cancel_reason_type
        },function(result){
            var data     = result['data'];
            var html_str = "";
            $.each(data, function (i, item) {
                var cls = "success";

                html_str += "<tr class=\"" + cls + "\" > <td>" + item.teacher_nick + "<td>" + item.lesson_type_str + "<td>" + item.lesson_start+'-'+item.lesson_end + "<td>" + item.grade_str+ "<td>"+item.subject_str+"<td>"+item.nick+"<td>"+item.ass_nick+ "<td>" +item.lesson_count+ "<td>" + item.lesson_cancel_reason_type_str+ "</tr>";
            });

            html_node.find(".data-body").html(html_str);

        });

    });

    $.each($("tr"),function(i,item){
        var leave_num = $(this).children().find(".row-data").data("teacher_leave_num");
       
       if(leave_num>=3){
            $(this).addClass("bg_red");
        }

        
    });



	  $('.opt-change').set_input_change_event(load_data);
});


