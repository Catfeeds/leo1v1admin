/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_train_list.d.ts" />

$(function(){

    function load_data(){
        $.reload_self_page ( {
            start_date  : $('#id_start_date').val(),
            end_date    : $('#id_end_date').val(),
            train_type  : $('#id_train_type').val(),
            subject     : $('#id_subject').val(),
            status      : $('#id_status').val(),
        });
    }



    Enum_map.append_option_list("train_type",$("#id_train_type"),false,[20,21,22,23]);
    Enum_map.append_option_list("subject",$("#id_subject"),false,[1,2,3,4,5,6,7,8,9,10]);
    Enum_map.append_option_list("train_status",$("#id_status"),false,[1,2,3]);

    $("#id_train_type").val(g_args.train_type);
    $("#id_subject").val(g_args.subject);
    $("#id_status").val(g_args.status);
    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);

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

    $(".opt-play").on("click",function(){
        var opt_data = $(this).get_opt_data();
        $.do_ajax("/ajax_deal2/change_train_status",{
            "id"             : opt_data.id,
            'status'         : 2,
        },function(){
            window.open('http://admin.yb1v1.com/tea_manage/show_lesson_video?lessonid=204d0094wDsOuLV15QU1VW');
        });
    });

    $(".opt-test").on("click",function(){
        var opt_data = $(this).get_opt_data();
        alert(2);
    });

	$('.opt-change').set_input_change_event(load_data);
});
