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
        if ( curnum == 0  ) {
            curnum = 0;
        }else if(curnum>=max_num){
            curnum = max_num-1;
        }
        var show_info = [];
        // var num = 0;
        // var loopnum = curnum;
        // while (num < max_num)
        // {
        //     if(loopnum < max_num && loopnum >= 0) {
        //         if( month_info[loopnum] !== undefined ) {
        //             show_info[show_info.length] = month_info[loopnum];
        //         }
        //     }
        //     loopnum++;
        //     num++;
        // }
        // console.log("loopnum:"+(loopnum-1));
        // console.log("month_info:"+month_info[loopnum-1]);

        console.log(month_info);
        $('#line-chart').empty();
        //月份显示
        $('#id_year').text(month_info[curnum].date);
        //薪资情况
        $('#id_teacher_level').text(month_info[curnum].level_str);
        $('#id_normal_lesson_total').text(month_info[curnum].normal_lesson_total+"课时");
        $('#id_trial_lesson_total').text(month_info[curnum].trial_lesson_total+"课时");
        $('#id_all_money').text(month_info[curnum].all_money+"元");

        var line = new Morris.Line({
            element    : 'line-chart',
            resize     : true,
            data       : month_info,
            xkey       : 'date',
            ykeys      : ['all_money'],
            labels     : ['工资'],
            lineColors : ['#00a6ff'],
            hideHover  : true,
            parseTime  : false,
            smooth     : false,
        });
    }

    var max_num = month_info.length;
    if(max_num>0){
        var curnum = max_num-1;
        $(".no-money-title").hide();
        $('#id_prev_year').on('click', function() {
            curnum--;
            month_change();
        });
        $('#id_next_year').on('click', function() {
            curnum++;
            month_change();
        });
        month_change();
    }else{
        $(".has-money").hide();
    }


});
