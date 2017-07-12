/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-teacher_trial_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
		    identity       : $('#id_identity').val(),
		    subject        : $('#id_subject').val(),
			date_type      : $('#id_date_type').val(),
			opt_date_type  : $('#id_opt_date_type').val(),
			start_time     : $('#id_start_time').val(),
			end_time       : $('#id_end_time').val(),
		    is_new_teacher : $('#id_is_new_teacher').val(),
		    count_type     : $('#id_count_type').val(),
        });
    }

    Enum_map.append_option_list("identity", $("#id_identity") );
    Enum_map.append_option_list("subject", $("#id_subject") );

	$('#id_identity').val(g_args.identity);
	$('#id_subject').val(g_args.subject);
	$('#id_is_new_teacher').val(g_args.is_new_teacher);
	$('#id_count_type').val(g_args.count_type);

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse(g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    $("#id_submit").on("click",function(){
        load_data();
    });

    $("#id_rate").on("click",function(){
        $.each($("tr"),function(i,item){
            if(i>0){
                var teacherid = $(this).find(".opt-div").data("teacherid");
                $.do_ajax("/tongji_ss/get_teacher_trial_rate",{
                    "teacherid"  : teacherid,
                    "start_time" : g_args.start_time,
                    "end_time"   : g_args.end_time,
                    "count_type" : $("#id_count_type").val(),
                },function(result){
                    var data = result.data;

                    if(data.ret<0){
                        data.lesson_time  = 0;
                        data.have_order   = 0;
                        data.order_number = 0;
                        data.order_per    = 0;
                    }

                    $(this).find(".lesson_time").text(data.lesson_time);
                    $(this).find(".have_order").text(data.have_order);
                    $(this).find(".order_number").text(data.order_number);
                    $(this).find(".order_per").text(data.order_per);
                    
                });
            }
        });
    });
});
