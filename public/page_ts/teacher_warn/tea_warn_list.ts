/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_warn-tea_warn_list.d.ts" />

$(function(){
function load_data(){
    $.reload_self_page ( {
		    date_type_config:	$('#id_date_type_config').val(),
		    date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val(),
        teacher: $('#id_teacher').val(),
    });
}

    $('#id_teacher').val(g_args.teacher);
    $.admin_select_user( $("#id_teacher"), "teacher",load_data);

	  $('.opt-change').set_input_change_event(load_data);

    $('.opt-detail').on('click', function() {
        var teacherid = $(this).attr('data_teacher');
        $.do_ajax('/teacher_warn/get_teacher_detail', {
            'teacherid':teacherid
        }, function(res) {
            var arr;
            if (res.data) {
                data = res.data
                arr = [
                    ['老师ID', data.teacherid],
                    ['老师呢称', data.nick],
                ];
            } else {
                arr = [
                    ['', '此老师不存在']
                ]
            }
            $.show_key_value_table("老师详细信息",arr);
        });
    });
});
