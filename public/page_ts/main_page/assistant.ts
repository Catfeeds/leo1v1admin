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

    $("#show_warning_info").on("click",function(){
        $.do_ajax('/ajax_deal2/get_assistant_warning_info',{
            "opt_date_type":g_args.opt_date_type,
            "start_time"   :g_args.start_time,
            "end_time"   :g_args.end_time,
        },function(resp) {
            var warning = resp.warning;
            var month_info = resp.month_info; 
            var today_info = resp.today_info; 
            $("#warning-one").text(warning['warning_type_one']);
            $("#warning-two").text(warning['warning_type_two']);
            $("#warning-three").text(warning['warning_type_three']);
            $("#today_revisit_num").text(today_info['revisit_num']);
            $("#today_goal").text(today_info['goal']);
            $("#today_call_num").text(today_info['call_num']);
            var goal_three = today_info['goal']*3;
            $("#today_goal_three").text(goal_three+':00');
            $("#month_revisit_num").text(month_info['revisit_num']);
            if(month_info['stu_num']>0){
                var month_stu_num=month_info['stu_num'];
            }else{
                var month_stu_num=0;
            }
            $("#month_stu_num").text(month_stu_num*2);
            $("#month_call_num").text(month_info['call_num']);
            var month_goal_three = month_stu_num*6;
            $("#month_stu_num_three").text(month_goal_three+':00');                                              
            init_noit_btn("warning-one", "预警1～5","1" );
            init_noit_btn("warning-two", "预警5～7", "2" );
            init_noit_btn("warning-three", "预警超时", "3" );



        });
 
    });

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

    $(".opt-warning-type").on("click",function(){

        window.location.href="/user_manage_new/ass_revisit_warning_info_sub?revisit_warning_type="+$(this).attr('data-warning');
    });


    $('.opt-change').set_input_change_event(load_data);
});
