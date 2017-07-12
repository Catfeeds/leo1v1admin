// SWITCH-TO:   ../../template/student/lesson_custom.html

$(function(){
	
	$("#id_add_custom").on("click",function(){

        var html_node=dlg_need_html_by_id( "id_dlg_add_custom_lesson") ;
	    //日期插件
	    html_node.find('#id_date').datetimepicker({
		    yearOffset:0,
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d',
		    formatDate:'Y-m-d'
	    });

	    //时间插件
	    html_node.find('#id_time_start').datetimepicker({
		    datepicker:false,
		    timepicker:true,
		    format:'H:i',
		    step:30
	    });
	    
        html_node.find('#id_time_end').datetimepicker({
		    datepicker:false,
		    timepicker:true,
		    format:'H:i',
		    step:30
	    });

        

        BootstrapDialog.show({
            title: '增加自定义课程',
            message :    html_node,
            closable: false, 
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
		            //检查时间

		            var opt_date	   = dlg_get_val_by_id("id_date");
		            var opt_time_start = opt_date+" "+  dlg_get_val_by_id ("id_time_start");
		            var opt_time_end   = opt_date+" "+ dlg_get_val_by_id ("id_time_end");
		            var courseid       = dlg_get_val_by_id ("id_courseid");
		            var opt_type       = dlg_get_val_by_id ("id_opt_type");
		            if (!(courseid >0) ){
			            alert("请选择课程id");
			            return;
		            }

                    //测评
		            if(opt_type == 0){
                        $.ajax({
                            url: '/stu_manage/lesson_custom_add',
                            type: 'POST',
                            data: {
				                'sid': g_sid, 
				                'start_time': opt_time_start ,
				                'end_time':opt_time_end,
				                'courseid':courseid
				            },
                            dataType: 'json',
                            success: function(data) {
                                if (data['ret'] == 0) {
					                window.location.reload();
                                }else{
					                alert(data["info"]);
				                }
                            }
                        });//返回可排课程列表调取新数据over
                        //增加试听课程    
                    }else if(opt_type == 1){
                        $.ajax({
                            url: '/stu_manage/lesson_audition_add',
                            type: 'POST',
                            data: {
				                'sid': g_sid, 
				                'start_time': opt_time_start ,
				                'end_time':opt_time_end,
				                'courseid':courseid
				            },
                            dataType: 'json',
                            success: function(data) {
                                if (data['ret'] == 0) {
					                window.location.reload();
                                }else{
					                alert(data["info"]);
				                }
                            }
                        });
                    }

                }
            }]
        });

        

	    

	});
	
	
	
	$(".opt-delete").on("click",function(){
		var quizid  = $(this).data("quizid");
		var deltype = $(this).data("deltype");
        
        BootstrapDialog.show({
            title    : '取消课程',
            message  : "id :["+quizid+"] "
                +"<br/>时间：" +$(this).data("work_start")+"-" +$(this).data("work_end")
                +"<br/>类型：" +$(this).data("course_type_str")
                +"<br/>要取消课程吗?!　" ,
            closable : false, 
            buttons : [{
                label  : '返回',
                action : function(dialog) {
                    dialog.close();
                }
            }, {
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    if( deltype == 0){
		                $.ajax({
                            url  : '/stu_manage/lesson_custom_del',
                            type : 'POST',
                            data : {
				                'sid'    : g_sid, 
				                'quizid' : quizid
				            },
                            dataType     : 'json',
                            success      : function(data) {
                                if (data['ret'] == 0) {
					                window.location.reload();
                                }else{
					                alert(data["info"]);
				                }
                            }
                        });//返回可排课程列表调取新数据over

                    }else{
                        $.ajax({
                            url: '/stu_manage/lesson_audition_del',
                            type : 'POST',
                            data : {
				                'lessonid' : quizid
				            },
                            dataType       : 'json',
                            success        : function(data) {
                                if (data['ret'] == 0) {
					                window.location.reload();
                                }else{
					                alert(data["info"]);
				                }
                            }
                        });//返回可排课程列表调取新数据over
                    }
                }
            }]
        });
	});
    
});


