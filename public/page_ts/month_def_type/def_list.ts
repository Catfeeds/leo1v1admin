/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/month_def_type-def_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            month_def_type:	$('#id_month_def_type').val(),
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val()
        });
    }


    $('#id_month_def_type').val(g_args.month_def_type);
    $.enum_multi_select( $('#id_month_def_type'), 'month_def_type', function(){load_data();} )

    $("#id_add").on("click",function(){
        var $month_def_type= $("<select/>" );
        Enum_map.append_option_list("month_def_type", $month_def_type);
        var d_input = $('<input type=text name=def_time value>');
        var s_input = $('<input type=text name=start_time value>');
        var e_input = $('<input type=text name=end_time value>');
        var arr=[
            ["月份定义" ,$month_def_type  ],
            ['定义时间', d_input],
            ['开始时间', s_input],
            ['结束时间', e_input],
        ] ;

        initPicker(d_input);
        initPicker(s_input);
        initPicker(e_input);

        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var $def_time = $('input[name="def_time"]').val();
                var def = $def_time.split('-');
                $def_time = def[0] + '-' + def[1] + '-01'; // 定义时间为每个月的一号
                var $start_time = $('input[name="start_time"]').val();
                var $end_time = $('input[name="end_time"]').val();
                if ($start_time < $end_time) {
                     $.do_ajax("/month_def_type/add_data",{
                        "month_def_type" : $month_def_type.val(),
                        "def_time" : $def_time,
                        "start_time" : $start_time,
                        "end_time" : $end_time,
                    });
                } else {
                    alert('开始时间必须小于结束时间');
                }
            }
        })

    });


    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var $month_def_type= $("<select/>" );
        Enum_map.append_option_list("month_def_type", $month_def_type, true);
        //var d_input = $('<input type=text name=def_time value="'+ opt_data.def_time +'">');
        var s_input = $('<input type=text name=start_time value="'+ opt_data.start_time +'">');
        var e_input = $('<input type=text name=end_time value="'+ opt_data.end_time +'">');
        var arr=[
            ["月份定义" ,$month_def_type],
            //['定义时间', d_input],
            ['开始时间', s_input],
            ['结束时间', e_input],
        ] ;

        //initPicker(d_input);
        initPicker(s_input);
        initPicker(e_input);

        $.show_key_value_table("修改", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                //var $def_time = $('input[name="def_time"]').val();
                var $start_time = $('input[name="start_time"]').val();
                var $end_time = $('input[name="end_time"]').val();
                if ($start_time < $end_time) {
                    $.do_ajax("/month_def_type/update_data",{
                        "id": opt_data.id,
                        "month_def_type" : $month_def_type.val(),
                        //"def_time" : $def_time,
                        "start_time" : $start_time,
                        "end_time" : $end_time,
                    });
                } else {
                    alert('开始时间必须小于结束时间');
                }
                //$.do_ajax("/month_def_type/update_data",{
                //     "id" : opt_data.id,
                //     "month_def_type" : $month_def_type.val(),
                //     "start_time" : $start_time,
                //     "end_time" : $end_time,
                // });
            }
        });

    });

    $(".opt-del").on("click", function(){
        var opt_data = $(this).get_opt_data();
        $.do_ajax("/month_def_type/del_data", {
            "id" : opt_data.id,
        });
    });

    $('.opt-change').set_input_change_event(load_data);

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
