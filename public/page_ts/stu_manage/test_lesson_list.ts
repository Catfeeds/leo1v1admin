/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-test_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			sid:	$('#id_sid').val()
        });
    }

	$('#id_sid').val(g_args.sid);

	$('.opt-change').set_input_change_event(load_data);
    setInterval(function(){
        $(".common-table") .removeClass("table-striped");
    },1000);


    $("#tbody .row-data").each(function(){
        var opt_data=$(this).get_self_opt_data();
        if (opt_data.teacherid) {
            if(opt_data.lesson_del_flag ==1  ) {
                $(this).closest("tr"). addClass("danger");
            }
        }

        
    });
    $(".opt-out-link").on("click",function(){
        var lessonid = $(this).get_opt_data("lessonid");
        $.do_ajax( "/common/encode_text",{
            "text" : lessonid
        }, function(ret){
            BootstrapDialog.alert("对外链接 : http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text  );
        });
    });


});
