
/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_teacher_money_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }

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

	  $('.opt-change').set_input_change_event(load_data);

    var curnum = 6;
    $('.left').on('click', function() {
        curnum--;
        if ( curnum < 0 ) {
            curnum = 0;
        }
        month_change();
    });

    $('.right').on('click', function() {
        curnum++;
        if ( curnum > 12 ) {
            curnnum = 12;
        }
        month_change();
    });

    var month_change = function (){
        $('#line-chart').empty();
        $('#year').text( month_info[curnum].x );
        console.log(curnum)
        var show_info = [];
        var num = 0;
        var loopnum = curnum;
        while (num < 6)
        {
            if(loopnum < 12) {
                show_info[num] = month_info[loopnum];
            }
            loopnum++;
            num++;
        }
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


