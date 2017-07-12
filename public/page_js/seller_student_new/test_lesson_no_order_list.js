/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-test_lesson_no_order_list.d.ts" />


$(function(){
    function load_data(){
        $.reload_self_page ( {
            grade:	$('#id_grade').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            phone:	$('#id_phone').val()
        });
    }

  Enum_map.append_option_list("grade",$("#id_grade"));


  $('#id_grade').val(g_args.grade);
  $('#id_phone').val(g_args.phone);

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


    $(".opt-set-self").on("click",function(){
        var opt_data=$(this).get_opt_data();

      $.do_ajax("/ss_deal/set_test_lesson_user_to_self",{
            "userid" : opt_data.userid
        });

        //opt_data.userid

    });
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


    init_noit_btn("id_opt_count",    "本周已抢名额" );
    init_noit_btn("id_last_count",    "本周剩余名额" );

});
