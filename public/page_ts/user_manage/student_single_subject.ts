/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-student_single_subject.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			assistantid:	$('#id_assistantid').val(),
			teacherid:	$('#id_teacherid').val(),
			studentid:	$('#id_studentid').val(),
			num:	$('#id_num').val()
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
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_studentid').val(g_args.studentid);
	$('#id_num').val(g_args.num);

	$.admin_select_user( $('#id_assistantid'), "assistant", load_data );
	$.admin_select_user( $('#id_teacherid'), "teacher", load_data );
	$.admin_select_user( $('#id_studentid'), "student", load_data );


	$('.show_detail').on("click",function(){
		var data            = $(this).get_opt_data();
        var teacherid = $(this).attr('date-teacherid');
        var subject = $(this).attr('date-subject');
        var studentid   = $(this).attr('date-studentid');
        var start_time = $('#id_start_time').val();
        var end_time   = $('#id_end_time').val();
        var html_node    = $.obj_copy_node("#id_assign_log");

        BootstrapDialog.show({
            title: "详情列表",
            message: html_node,
            closable: true
        });

        $.do_ajax('/ajax_deal2/show_student_single_subject',{
            'teacherid' : teacherid,
            'assistantid':assistantid,
            'studentid' :studentid,
            'start_time':start_time,
            'end_time'  : end_time,
        },function(result){
            var data     = result['data'];
            var html_str = "";
            $.each(data, function (i, item) {
                var cls = "success";

                html_str += "<tr class=\"" + cls + "\" > <td>" 
                		 + item.lesson_start+'-'+item.lesson_end 
                		 + "<td>" + item.count + "<td>" 
                		 + item.teacher_nick +"<td>"
                		 +item.assistant_nick
                		 + "</tr>";
            });

            html_node.find(".data-body").html(html_str);

        });

    });
	$('.opt-change').set_input_change_event(load_data);
});

