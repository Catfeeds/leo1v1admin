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

    $('circle').each(function(){
        console.log($(this).text());
        alert($(this).text())
    });

    $('select').change(function() {
        var salary = $(this).val();
        if (salary == 1) {
            $('#salary').text('10.00');
        } else if (salary == 2) {
            $('#salary').text('20.00');
        } else {
            $('#salary').text('30.00');
        }
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

    var curnum = 6;
    $('#id_prev_year').on('click', function() {
        curnum--;
        if ( curnum < -5 ) {
            curnum = -5;
        }
        month_change();
    });

    $('#id_next_year').on('click', function() {
        curnum++;
        if ( curnum > 6 ) {
            curnum = 6;
        }
        month_change();
    });

    var month_change = function (){
        $('#line-chart').empty();
        var show_info = [];
        var num = 0;
        var loopnum = curnum;
        while (num < 6)
        {
            if(loopnum < 12 & loopnum >= 0) {
                if( month_info[loopnum] !== undefined ) {
                    show_info[show_info.length] = month_info[loopnum];
                }
            }

            loopnum++;
            num++;
        }
        $('#year').text( month_info[loopnum-1].x );
        // LINE CHART
        var line = new Morris.Line({
            element: 'line-chart',
            resize: true,
            data: show_info,
            xkey: 'x',
            ykeys: ['y'],
            labels: ['工资'],
            lineColors: ['#00a6ff'],
            hideHover: true,
            parseTime:false,
            smooth:false,
        });
    }
    month_change();
});
