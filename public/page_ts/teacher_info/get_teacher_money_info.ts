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

    var salary_modal = '';
    $('button[data-toggle]').on('click', function() {
        var value = $(this).val();
        if (salary_modal != '' & salary_modal != value ) {
            salary_modal = value;
            var that = $(this);
            if ($('.direct-chat-contacts-open').length == 0) {
                $(that).click();
                $(that).click();
            } else {
                setTimeout(function(){
                    $(that).click();
                }, 500);
            }
        }
        salary_modal = value;
        if (value == 'salary') {
            var content = '<div class="col-sm-12"><h5>薪资规则</h5>'
                +'<p>例如：李老师带了三个学生A、B、C。</p>'
                +'<div class="col-sm-offset-1">'
                +'<p>A第五次试听课分数为90<br/>'
                +'B第五次试听课分数为92<br/>'
                +'那么，李老师的第五次试听课平均分为(90+92)/2=91分</p></div></div>';
        } else {
            var content = '<div class="col-sm-12"><h5>晋升规则</h5>'
                +'<p>例如：李老师带了三个学生A、B、C。</p>'
                +'<div class="col-sm-offset-1">'
                +'<p>A第五次试听课分数为90<br/>'
                +'B第五次试听课分数为92<br/>'
                +'那么，李老师的第五次试听课平均分为(90+92)/2=91分</p></div></div>';
        }
        $('.direct-chat-contacts').empty();
        $('.direct-chat-contacts').append(content);
    });

    var show_teacher_money = function(money_list,html){
        var button_html = "<button type='button'><i class='fa fa-minus'></i></button>";
        $('#id_teacher_money_list').empty();
        $.each(money_list,function(key,value){
            html_list +="<tr><td><but</td>";
            $.each(value,function(k,v){

            });
            html_list +="</tr>";


        });
        $('#id_teacher_money_list').html(html_list);

    }

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
        //薪资详情
        show_teacher_money(month_info[curnum].list,html_list);

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
        var html_list =
            "<tr>"
            +"<th class='text-cen' style='width:25px'></th>"
            +"<th class='text-cen' style='width:150px'>分类</th>"
            +"<th class='text-cen'>姓名</th>"
            +"<th class='text-cen'>时间</th>"
            +"<th class='text-cen'>状态</th>"
            +"<th class='text-cen'>扣款 </th>"
            +"<th class='text-cen'>金额</th>"
            +"<!-- <th class='text-cen'>操作</th> -->"
            +"</tr>";

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
