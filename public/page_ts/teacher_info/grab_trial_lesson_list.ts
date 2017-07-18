/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-grab_trial_lesson_list.ts" />
$(function(){
    $(".opt-grab_trial_lesson").on("click",function(){
        var data=$(this).get_opt_data();
        BootstrapDialog.show({
	        title: "接受确认",
	        message : "是否确认接受此课程?<br/><b><font color='red'>点击 确认按钮 代表老师您已经接课成功并同意理优的奖惩制度！<br/>若对课程有任何疑问可直接联系对应排课老师！</font></b>",
	        buttons: [{
		        label: "返回",
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: "确认",
		        cssClass: "btn-warning",
		        action: function(dialog) {
                    dialog.close();
                    $.do_ajax("/teacher_info/course_set_new",{
                        "require_id"   : data.require_id,
                        "lesson_start" : data.stu_request_test_lesson_time,
                        "grade"        : data.grade,
                    },function(result){
                        if(result.ret==0){
                            BootstrapDialog.alert("抢课成功！请在课程列表中查看该课程！");
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                    })

		        }
	        }]
        });
    });


});
