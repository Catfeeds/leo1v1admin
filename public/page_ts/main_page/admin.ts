/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-admin.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
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

    // alert(g_adminid);


	$('.opt-change').set_input_change_event(load_data);
    //@desn:修改系统分配例子配额
    $('#id_edit_system_allocates_num').on('click',function(){
        var system_allocates_num = $("<input/>");
        system_allocates_num.val($(this).parent().find("span").text() );
        var arr=[
            ["新例子需要" , system_allocates_num ],
        ];
        $.show_key_value_table("配额编辑", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/main_page/edit_system_allocates_num",{
                    "system_allocates_num" : system_allocates_num.val()
                });
            }
        });

    })
});


