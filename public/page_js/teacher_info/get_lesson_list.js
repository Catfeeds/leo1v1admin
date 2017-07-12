/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
      teacherid   : g_args.teacherid	,
      start_date  : $('#id_start_date').val(),
      end_date    : $('#id_end_date').val(),
      lesson_type : $('#id_lesson_type').val()
        });
    }

  $('#id_start_date').val(g_args.start_date);
  $('#id_end_date').val(g_args.end_date);
  $('#id_lesson_type').val(g_args.lesson_type);
    $.enum_multi_select( $('#id_lesson_type'), 'contract_type', function(){load_data();} );

  //时间插件
  $("#id_start_date").datetimepicker({
        lang:'ch',
    datepicker:true,
    timepicker:false,
    format:'Y-m-d',
    step:30,
      onChangeDateTime :function(){
            load_data();
        }
  });

  $("#id_end_date").datetimepicker({
        lang:'ch',
    datepicker:true,
    timepicker:false,
    format:'Y-m-d',
    step:30,
      onChangeDateTime :function(){
            load_data();
        }
  });

  $('.opt-change').set_input_change_event(load_data);


    $(".opt-lesson-all").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/supervisor/lesson_all_info?lessonid="+ opt_data.lessonid);
    });



});
