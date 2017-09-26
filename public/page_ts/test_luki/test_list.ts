/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_luki-test_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            grade:	$('#id_grade').val(),
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            msg:	$('#id_msg').val()
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
    $('#id_grade').val(g_args.grade);
    $.enum_multi_select( $('#id_grade'), 'grade', function(){load_data();} )
    $('#id_msg').val(g_args.msg);


    $('.opt-change').set_input_change_event(load_data);

    $("#id_add").on("click",function(){
        var $grade= $("<select/>" );
        Enum_map.append_option_list("grade", $grade);
        var arr=[
            ["年级" ,$grade  ],
        ] ;

        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/test_luki/test_add",{
                    "grade" : $grade.val()
                });
            }
        });

    });


    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();


        var $grade= $("<select/>" );
        Enum_map.append_option_list("grade", $grade,true);
        var arr=[
            ["年级" ,$grade  ],
        ] ;
        $grade.val(opt_data.grade );

        $.show_key_value_table("xx", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/test_luki/test_set2",{
                    "id" : opt_data.id,
                    "grade" : $grade.val()
                });
            }
        });


    });

});
