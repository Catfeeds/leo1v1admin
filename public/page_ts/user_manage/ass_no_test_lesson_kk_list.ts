/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-ass_no_test_lesson_kk_list.d.ts" />

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
    
    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
      
        var id_hand_kk_num = $("<input />") ;
        id_hand_kk_num.val(opt_data.hand_kk_num);
        var arr=[
            ["扩课数",id_hand_kk_num],           
        ];
        
        $.show_key_value_table("编辑", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
               
                $.do_ajax("/user_deal/set_ass_hand_kk_num", {
                    "adminid"             : opt_data.adminid,
                    "month"                    : opt_data.month,
                    "kpi_type"                      : opt_data.kpi_type,
                    "hand_kk_num"                   : id_hand_kk_num.val(),
                });
            }
        });

    });


	$('.opt-change').set_input_change_event(load_data);
});

