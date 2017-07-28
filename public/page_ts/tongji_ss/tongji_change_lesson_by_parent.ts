/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-tongji_change_lesson_by_parent.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
      date_type_config:	$('#id_date_type_config').val(),
      date_type:	$('#id_date_type').val(),
      opt_date_type:	$('#id_opt_date_type').val(),
      start_time:	$('#id_start_time').val(),
      end_time:	$('#id_end_time').val(),
      lesson_cancel_reason_type :	$('#id_lesson_cancel_reason_type ').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    Enum_map.append_option_list('lesson_cancel_reason_type',$('#id_lesson_cancel_reason_type'),false,[1,11]);
    $('#id_lesson_cancel_reason_type ').val(g_args.lesson_cancel_reason_type );

    $('.show_detail').on("click",function(){
        var userid     = $(this).attr('date-userid');
        var lesson_cancel_reason_type = $('#id_lesson_cancel_reason_type option:selected').val();
        var start_time = $('#id_start_time').val();
        var end_time   = $('#id_end_time').val();
        var html_node  = $.obj_copy_node("#id_assign_log");

        BootstrapDialog.show({
            title: "详情列表",
            message: html_node,
            closable: true
        });

        $.do_ajax('/ss_deal2/show_change_lesson_by_parent',{
            'userid'    : userid,
            'start_time':start_time,
            'end_time'  : end_time,
            'lesson_cancel_reason_type':lesson_cancel_reason_type
        },function(result){
            var data     = result['data'];
            var html_str = "";
            $.each(data, function (i, item) {
                var cls = "success";

                html_str += "<tr class=\"" + cls + "\" > <td>" + item.nick + "<td>" + item.lesson_type_str + "<td>" + item.lesson_start+'-'+item.lesson_end + "<td>" + item.grade_str+ "<td>"+item.subject_str +"<td>"+item.teacher_nick  +"<td>"+item.ass_nick+ "<td>" +item.lesson_count+ "<td>" + item.lesson_cancel_reason_type_str+ "</tr>";
            });

            html_node.find(".data-body").html(html_str);

        });

    });

    $('.opt-change').set_input_change_event(load_data);
});
