/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_seller_require_modify_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			record_type:	$('#id_record_type').val(),
			change_type:	$('#id_change_type').val()
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
    $('#id_change_type').val(g_args.change_type);
	$('#id_record_type').val(g_args.record_type);
    
    var init_noit_btn=function( id_name, title) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value =btn.data("value");
        btn.text(value);
        if (value >0 ) {
            btn.addClass("btn-warning");
        }
    };

    init_noit_btn("id_have_lesson",    "总人数" );
    init_noit_btn("id_no_lesson",    "试听成功数" );
    init_noit_btn("id_all_tea",    "签单成功数" );
    init_noit_btn("id_all_lesson",    "签单率" );
    $("#id_have_lesson").on("click",function(){
        $(".order_info").each(function(){
            $(this).parent().show();
        });
        $(".order_info").each(function(){
                $(this).parent().show();
        });
            
    });

    $("#id_no_lesson").on("click",function(){
        $(".order_info").each(function(){
                $(this).parent().show();
        });

        $(".lesson_info").each(function(){
            var lesson_flag = $(this).data("value");
            //alert(lesson_info);
            if(lesson_flag ==0){
                $(this).parent().hide();
            }
        });
      
    });

    $("#id_all_tea").on("click",function(){
        $(".lesson_info").each(function(){
            $(this).parent().show();
        });

        $(".order_info").each(function(){
            var order_flag = $(this).data("value");
            //alert(lesson_info);
            if(order_flag ==0){
                $(this).parent().hide();
            }
        });
    });



	$('.opt-change').set_input_change_event(load_data);
});




