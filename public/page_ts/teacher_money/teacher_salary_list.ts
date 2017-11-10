/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_money-teacher_salary_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		    date_type_config : $('#id_date_type_config').val(),
		    date_type        : $('#id_date_type').val(),
		    opt_date_type    : $('#id_opt_date_type').val(),
		    start_time       : $('#id_start_time').val(),
		    end_time         : $('#id_end_time').val(),
		    reference        : $('#id_reference').val(),
    });
}

$(function(){
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

    $("#id_reference").val(g_args.reference);
    $.admin_select_user($("#id_reference"),"teacher",load_data);

	  $('.opt-change').set_input_change_event(load_data);

    $('.opt-edit').on('click', function() {
        var opt_data=$(this).get_opt_data();
        alert('id ' + opt_data.id);
        var s_input = $('<input type=text name=pay_time value="'+ opt_data.pay_time +'">');
        var arr=[
            ['工资结算开始时间', s_input]
        ] ;

        initPicker(s_input);

        $.show_key_value_table("修改", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var $pay_time = $('input[name="pay_time"]').val();
                    $.do_ajax("/teacher_money/update_pay_time",{
                        "id": opt_data.id,
                        "pay_time" : $pay_time,
                    });

            }
        });
    });


    function initPicker(obj)
    {
      obj.datetimepicker({
            lang       : 'ch',
            datepicker : true,
            timepicker : false,
            format     : 'Y-m-d',
            step       : 30,
            onChangeDateTime :function(){
              $(this).hide();
            }
        });
    }
});
