/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-no_lesson_call_end_time_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            adminid:    $('#id_adminid').val(),
            phone:    $('#id_phone').val()
        });
    }


    $('#id_adminid').val(g_args.adminid);
    $('#id_phone').val(g_args.phone);


    $('.opt-change').set_input_change_event(load_data);
});
