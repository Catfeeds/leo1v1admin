
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

	  $('.opt-change').set_input_change_event(load_data);

    // LINE CHART
    var line = new Morris.Line({
        element: 'line-chart',
        resize: true,
        data: [
            {x: '2017-01', y: 2666},
            {x: '2017-02', y: 2778},
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
        lineColors: ['#00c0ef'],
        hideHover: 'auto'
    });

});


