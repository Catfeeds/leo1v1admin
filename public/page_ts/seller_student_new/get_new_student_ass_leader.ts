/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-get_new_student_ass_leader.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			assistantid:	$('#id_assistantid').val()
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
    $.admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });

    $("#id_add").on("click",function(){
        var $phone=$("<input/>");
        var $subject=$("<select/>");
        var $grade=$("<select/>");
        var $origin=$("<input/>");
        var arr=[
            ["电话",  $phone],
            ["年级",  $grade],
            ["科目",  $subject],
            ["渠道",  $origin],
        ];
        Enum_map.append_option_list("grade",$grade, true);
        Enum_map.append_option_list("subject",$subject, true);

        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ss_deal/add_ss",{
                    "phone" : $phone.val() ,
                    "subject" : $subject.val() ,
                    "grade" : $grade.val() ,
                    "origin" : $origin.val()
                });
            }
        });
    });




	$('.opt-change').set_input_change_event(load_data);
});
