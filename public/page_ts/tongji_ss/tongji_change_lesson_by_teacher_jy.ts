/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-test_lesson_ass.d.ts" />
function load_data(){
    $.reload_self_page ( {
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

	  $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
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

	$('.opt-change').set_input_change_event(load_data);
});


