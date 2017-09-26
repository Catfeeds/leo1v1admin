/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-grab_test_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			subject : $('#id_subject').val()
        });
    }
    $("#id_select_all").on("click", function() {
        $(".opt-select-item").iCheck("check");
    });
    $("#id_select_other").on("click", function() {
        $(".opt-select-item").each(function() {
            var $item = $(this);
            if ($item.iCheckValue()) {
                $item.iCheck("uncheck");
            } else {
                $item.iCheck("check");
            }
        });
    });
	$('#id_subject').val(g_args.subject);
    $.enum_multi_select($("#id_subject"),"subject",function(){load_data();});

    $("#id_opt_grab_trial_user_info").on("click",function(){
        var id           = "";
        var requireid    = "";
        var grade        = "";
        var subject      = "";
        var textbook     = "";
        var lesson_time  = "";
        var lesson_info  = "";
        var textbook_str = "";
        var select_num   = 0;

        $(".opt-select-item").each(function(){
            if($(this).is(":checked")){
                requireid = $(this).data("requireid");
                grade=$(this).data("grade");
                subject=$(this).data("subject");
                textbook=$(this).data("textbook");
                lesson_time=$(this).data("lesson_time");
                if(id==""){
                    id=requireid;
                }else{
                    id+=(","+requireid);
                }
                if(textbook!="未设置"){
                    textbook_str=",教材:"+textbook;
                }else{
                    textbook_str="";
                }
                lesson_info+=grade+subject+textbook_str+",期待上课时间:"+lesson_time+"<br/>";
                select_num++;
            }
        });

        if(select_num==0){
            BootstrapDialog.alert("未选择试听申请!");
        }else{
            $.do_ajax("/grab_lesson/add_requireids",{
                "requireids" : id,
            },function(result){
                select_time_limit(result,lesson_info);
            })


            // $.do_ajax("/common/base64",{
            //     "text" : id,
            //     "type" : "encode"
            // },function(result){
            //     select_time_limit(result,lesson_info);
            // })
        }
    });

    var select_time_limit = function(result,lesson_info){
        var id_time=$("<input />");
        var arr = [
            ["填写时间(单位:分钟)",id_time],
        ];
        $.show_key_value_table("填写链接有效时长",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                dialog.close();
                var now  = Date.parse(new Date())/1000;
                var time = id_time.val();
                now = now+time*60;
                var url  = "http://www.leo1v1.com/teacher_info/grab_trial_lesson_list?text="+result.data+"&time="+now;
                var alert_info = url+"<br/>"+lesson_info;

                $.ajax({
                    type     :'post',
                    url      : '/grab_lesson/update_lesson_link',
                    dataType : 'json',
                    data     : {
                        'url'       : url,
                        'text'      : result.data,
                        'live_time' : time
                    },
                    success :function(ret){
                        if (ret.ret == 0) {
                            BootstrapDialog.alert(alert_info);
                        } else {
                            alert(ret.info);
                        }
                    }
                });

                // BootstrapDialog.alert(alert_info);
            }
        },function(){
            id_time.val("60");
        });
    }

	$('.opt-change').set_input_change_event(load_data);
});
