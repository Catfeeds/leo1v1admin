/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page2-market.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
      date_type_config:	$('#id_date_type_config').val(),
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


  $('.opt-change').set_input_change_event(load_data);

    $("#id_edit_seller_diff_money_def").on("click",function(){
        var $seller_diff_money_def = $("<input/>");
        $seller_diff_money_def.val($(this).parent().find("span").text() );
        var arr=[
            ["配额" , $seller_diff_money_def ],
        ];
        $.show_key_value_table("配额编辑", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal2/config_date_set",{
                    "config_date_type" :1,
                    "opt_time" :g_args.start_time,
                    "value" : $seller_diff_money_def.val()
                });
            }
        });


    });

});
