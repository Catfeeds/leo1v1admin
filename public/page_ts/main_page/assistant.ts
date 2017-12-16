 /// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-assistant.d.ts" />

$(function(){

    function load_data(){
        $.reload_self_page ( {
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val()
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

    function show_top( $person_body_list) {

        $($person_body_list[0]).find("td").css(
            {
                "color" :"red"
            }
        );
        $($person_body_list[1]).find("td").css(
            {
                "color" :"orange"
            }
        );

        $($person_body_list[2]).find("td").css(
            {
                "color" :"blue"
            }
        );

    }

    show_top( $("#id_lesson_count_list > tr")) ;
    show_top( $("#id_assistant_renew_list > tr")) ;

    // $("#show_warning_info").on("click",function(){
    //     $.do_ajax('/user_deal/get_train_lesson_comment',{
    //         "lessonid":lessonid,
    //         "lesson_type":2
    //     },function(resp) {

    //     });
 
    // });

    var init_noit_btn=function( id_name, title ,type) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value = parseInt( btn.text() );
        if (value >0 ) {
            btn.addClass("btn-warning");
        }
        btn.attr('data-warning', type);
    };

    init_noit_btn("warning-one", "预警1～5","1" );
    init_noit_btn("warning-two", "预警5～7", "2" );
    init_noit_btn("warning-three", "预警超时", "3" );
    $(".opt-warning-type").on("click",function(){

        window.location.href="/user_manage_new/ass_revisit_warning_info_sub?revisit_warning_type="+$(this).attr('data-warning');
    });


    $('.opt-change').set_input_change_event(load_data);
});
