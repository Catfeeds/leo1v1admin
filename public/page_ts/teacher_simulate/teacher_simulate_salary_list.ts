/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_simulate-teacher_simulate_salary_list.d.ts" />

$(function(){
    function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		    date_type_config : $('#id_date_type_config').val(),
		    date_type        : $('#id_date_type').val(),
		    opt_date_type    : $('#id_opt_date_type').val(),
		    start_time       : $('#id_start_time').val(),
		    end_time         : $('#id_end_time').val(),
		    teacher          : $('#id_teacher').val(),
        teacherid        : $('#id_teacherid').val(),
        teacher_type     : $('#id_teacher_type').val(),
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

    $("#id_teacher").val(g_args.teacher);
    $("#id_teacher_type").val(g_args.teacher_type);

    $.admin_select_user($("#id_teacher"),"teacher",load_data);

	  $('.opt-change').set_input_change_event(load_data);

    // 明细
    $('.opt-show').on('click',function(){
        var data=$(this).get_opt_data();
        var teacherid=data.teacherid;
        var start_time=data.add_time;
        window.open("/teacher_simulate/tea_simulate_wages_info?teacherid="+teacherid+"&start_time="+start_time+"&opt_date_type=3");
    })

    $("#id_get_lesson_price").on("click",function(){
        var start_time = $('#id_start_time').val();

        BootstrapDialog.alert("开始拉取课程收入！请稍后...");
        $.do_ajax("/user_manage_new/get_lesson_price",{
            "start_time" : start_time
        },function(result){
            console.log(result);
            $("#id_lesson_price").html(result.lesson_price);
        });

    });

    if(g_account_role===13 || g_account=="adrian" || g_account=="jim"){
        $(".show_lesson_price").show();
    }else{
        $(".show_lesson_price").hide();
    }

    if(g_adminid==780 || g_adminid==895){
        download_show();
    }

});
