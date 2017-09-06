
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

    // LINE CHART
    var line = new Morris.Line({
        element: 'line-chart',
        resize: true,
        data: [
            {x: '2017-03', y: 4912},
            {x: '2017-04', y: 3767},
            {x: '2017-05', y: 6810},
            {x: '2017-06', y: 5670},
            {x: '2017-07', y: 7100},
            {x: '2017-08', y: 5920},
            {x: '2017-03', y: 4912},
            {x: '2017-04', y: 3767},
            {x: '2017-05', y: 6810},
            {x: '2017-06', y: 5670},
            {x: '2017-07', y: 7100},
            {x: '2017-08', y: 5920},


        ],
        xkey: 'x',
        ykeys: ['y'],
        labels: ['工资'],
        lineColors: ['#00a6ff'],
        hideHover: 'auto'
    });

    var month_change = function (month){
        var year = $('#year').text();
        var htmlcode = $('#line-chart').html();

    };
    $('.left').on('click', function() {
        var month = $('#month').text() - 1;
        if ( month < 1 ) {
            month = 1;
        }
        month_change(month);
    });
    $('.right').on('click', function() {
        var month = $('#month').text() + 1;
        if ( month > 12 ) {
            month = 12;
        }
    });
    var pic_code = $('#line-chart').html();
    console.log(pic_code)

});


