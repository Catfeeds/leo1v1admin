/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-seller_edit_log_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:    $('#id_date_type_config').val(),
            date_type:    $('#id_date_type').val(),
            opt_date_type:    $('#id_opt_date_type').val(),
            start_time:    $('#id_start_time').val(),
            end_time:    $('#id_end_time').val(),
            adminid:    $('#id_adminid').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
    $('#id_adminid').val(g_args.adminid);

    var jump_url_1="/tq/get_list_by_phone";
    $(".opt-return-back-list").on("click",function(){
        var opt_data= $(this).get_row_opt_data();
        $.wopen(jump_url_1+"?"
                +"phone="+opt_data.phone
               );
    });

    $('.opt-change').set_input_change_event(load_data);
});
