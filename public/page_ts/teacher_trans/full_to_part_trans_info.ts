

$(function(){
function load_data(){
    $.reload_self_page ( {
		    date_type_config:	$('#id_date_type_config').val(),
		    date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val(),
    });
}

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery          : function() {
            load_data();
        }
    });


    $('.opt-change').set_input_change_event(load_data);

    $('.opt-accept').on('click', function() {
        var id = $(this).parent().attr('data_id');
        var teacherid = $(this).parent().attr('data_teacherid');
        var teacher_money_type = $(this).parent().attr('data_money_type');
        var accept_status = $("<select><option value='0'>未审核</option><option value='1'>未通过</option><option value='2'>已通过</option></select>");
        var accept_info = $('<textarea></textarea>');
        var arr = [
            ['审核', accept_status],
            ['未通过原因', accept_info]
        ];

        $.show_key_value_table("审核",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(diolog) {
                console.log(teacherid);
                $.do_ajax("/teacher_trans/update_accept_status", {
                    'id':id,
                    'teacherid':teacherid,
                    'accept_status': accept_status.val(),
                    'accept_info': accept_info.val(),
                    'teacher_money_type' : teacher_money_type
                });
            }
        });

    });

});
