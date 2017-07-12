/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-graduating_lesson_time.d.ts" />

$(function(){
    function load_data(){
            $.reload_self_page ( {
                date_type_config:	$('#id_date_type_config').val(),
                date_type:	$('#id_date_type').val(),
                opt_date_type:	$('#id_opt_date_type').val(),
                start_time:	$('#id_start_time').val(),
                end_time:	$('#id_end_time').val(),
                residual_flag:	$('#id_residual_flag').val()

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


    $(".opt-plan_lesson_time").on("click",function(){
        var opt_data          = $(this).get_opt_data();
        var $plan_lesson_time = $("<input  />");
        var arr               = [];

        // 取时间段
        $.do_ajax("/user_manage/get_weeks",{
            'start_time'    : g_args.start_time,
             "userid" : opt_data['userid']
        },function(result){
            var arr=[];
            $.each( result.list, function(i,item) {
                var id     = item.id;
                var $input = $("<input/>");
                $input.data("date_id", id);
                $input.val( item.plan_lesson_count);
                arr.push([ item.week_title   , $input  ]);
            }) ;

            $.show_key_value_table("设置同学的计划课时", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var res = [];
                    $.each(arr, function (i ,item ){
                        var $input = item[1];
                        res.push([$input.data("date_id"),$input.val()]);
                    });
                    var res_json =  JSON.stringify(res);
                    $.do_ajax("/user_manage/set_graduating_lesson_time",{
                        "res"         : res_json,
                        "userid"      : opt_data['userid'],
                        'start_time'  : g_args.start_time,
                    },function(result){
                        load_data();
                    });
                },
            });
        });
    });

    Enum_map.append_option_list("boolean",$("#id_residual_flag"));

	  $('#id_residual_flag').val(g_args.residual_flag);

    $('.opt-change').set_input_change_event(load_data);
});
