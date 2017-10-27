/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_teacher_money_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
	  $('.opt-change').set_input_change_event(load_data);

    $('button[data-key]').on('click', function() {
        var key = $(this).attr('data-key');
        $('.'+key).slideToggle('show');
        $(this).children().toggleClass('fa-plus');
        $(this).children().toggleClass('fa-minus');
    });

    var month_change = function (){
        //清除线谱
        $('#line-chart').empty();
        var show_info = [];
        var num       = 0;
        var loop_num  = 0;
        var check_num = 5;
        var left_free = 0;
        var right_free = 0;
        var half_num = check_num/2;
        if(check_num>max_num){
            check_num = max_num;
            loop_num = 0;
        }else{
            //如果
            left_free  = cur_num-half_num;
            right_free = max_num-cur_num-half_num;
            if(left_free>0 ){
                if(right_free<=0){
                    loop_num = max_num-check_num;
                }else if(right_free>0){
                    loop_num = left_free;
                }
            }else if(left_free<=0){
                loop_num = 0;
            }
        }
        loop_num=Math.ceil(loop_num);
        // console.log(month_info);
        while (num < check_num)
        {
            if(loop_num>= 0) {
                if( month_info[loop_num] !== undefined ) {
                    show_info[show_info.length] = month_info[loop_num];
                }
            }
            loop_num++;
            num++;
        }

        var line = new Morris.Line({
            element    : 'line-chart',
            resize     : true,
            data       : show_info,
            xkey       : 'date',
            ykeys      : ['all_money'],
            labels     : ['工资'],
            lineColors : ['#00a6ff'],
            hideHover  : true,
            parseTime  : false,
            smooth     : false,
        });

        //月份显示
        $('#id_year').text(month_info[cur_num].date);
        //薪资情况
        $('#id_teacher_level').text(month_info[cur_num].level_str);
        $('#id_normal_lesson_total').text(month_info[cur_num].normal_lesson_total+"课时");
        $('#id_trial_lesson_total').text(month_info[cur_num].trial_lesson_total+"课时");
        $('#id_all_money').text(month_info[cur_num].all_money+"元");
        if(month_info[cur_num].reward_money==undefined){
            $('#id_reward_money').text("0元");
        }else{
            $('#id_reward_money').text(month_info[cur_num].reward_money+"元");
        }

        $(".date-tbody").each(function(){
            var date = $(this).data("date");
            if(date==month_info[cur_num].date){
                $(this).show();
            }else{
                $(this).hide();
            }
        });

        $(".show_key").each(function(){
            var show_key = $(this).data("show_key");
            $("."+show_key).hide();
        });
    }

    var max_num = month_info.length;
    if(max_num>0){
        var cur_num = max_num-1;
        $(".no-money-title").hide();
        $('#id_prev_year').on('click', function() {
            cur_num--;
            if(cur_num<0){
                cur_num = 0;
            }
            month_change();
        });
        $('#id_next_year').on('click', function() {
            cur_num++;
            if(cur_num>=max_num){
                cur_num = max_num-1;
            }
            month_change();
        });
        month_change();
    }else{
        $(".has-money").hide();
    }

    $(".show_key").on("click",function(){
        var data = $(this).data("show_key");
        $('.'+data).toggle('show');
        $(this).children().toggleClass('fa-plus');
        $(this).children().toggleClass('fa-minus');
    });

});
