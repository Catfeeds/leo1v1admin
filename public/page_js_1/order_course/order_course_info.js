// SWITCH-TO: ../../../template/order_course/
$(function(){
    $("#id_type_v").val(g_args.type);
    $('#id_start_date').val(g_start_date);
    $('#id_end_date').val(g_end_date);

	//时间控件
	$('#id_start_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
	    onChangeDateTime :function(){
		    load_data();
        }
	});
	$('#id_end_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		onChangeDateTime :function(){
		    load_data();
        }
	});
	//时间控件-over
    
    function load_data(){
        var type       = $("#id_type_v").val();
        var start_date = $("#id_start_date").val();
        var end_date   = $("#id_end_date").val();
	    var url = "/order_course/order_course_list?type="+type+"&start_date="+start_date+"&end_date="+end_date;
	    window.location.href = url;
	 }

	$(".will_change").on("change",function(){
	    load_data();
	});
    
    /* 
     $("#id_search_school").on("click",function(){
     var school_info = $.trim( $("#id_school_info").val());
     if(school_info == ""){
     alert("请输入所需查找学校信息！");
     }else{
	 var url = "/school_info/search_school?school_info="+school_info;
	 window.location.href = url;
     }
     });
     //查找信息回车
     $("#id_school_info").on("keypress",function(e){
     if(e.keyCode == 13){
     var school_info = $("#id_school_info").val();
     if(school_info == ""){
     alert("请输入所需查找学校信息！");
     }else{
	 var url="/school_info/search_school?school_info="+school_info;
	 window.location.href = url;
     }
     }
     });

     */
    

    //添加或更新学校信息
    var do_add_or_update=function( opt_type, item ){
        var html_txt=dlg_get_html_by_class('dlg_add_course_info');
        var html_node =$("<div></div>").html(html_txt);

        if (opt_type=="update") {
            html_node.find(".add_id").val(item.id);
            html_node.find(".add_courseid").val(item.courseid);
            html_node.find(".add_course_start").val(item.start_time);
            html_node.find(".add_course_end").val(item.end_time);
            html_node.find(".add_course_people").val(item.course_people);
            html_node.find(".add_course_people_current").val(item.course_people_current);
        }
        
	    //时间控件
	    html_node.find('.add_course_start').datetimepicker({
		    lang:'ch',
		    timepicker:true,
            step: 10,
		    format:'Y-m-d H:i'
	    });
	    html_node.find('.add_course_end').datetimepicker({
		    lang:'ch',
		    timepicker:true,
            step: 10,
		    format:'Y-m-d H:i'
	    });
	    //时间控件-over

        var title= "";
        if (opt_type=="update"){
            title="修改课程信息";
        }else{
            title="添加课程信息";
        }

        BootstrapDialog.show({
            title: title,
            message : html_node,
            closable: true, 
            closeByBackdrop:false,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
		                var id                    = html_node.find(".add_id").val();
                        var courseid              = html_node.find(".add_courseid").val();
                        var course_start          = html_node.find(".add_course_start").val();
                        var course_end            = html_node.find(".add_course_end").val();
                        var course_people         = html_node.find(".add_course_people").val();
                        var course_people_current = html_node.find(".add_course_people_current").val();
                        $.ajax({
			                type     : "post",
			                url      : "/order_course/add_order_course_info",
			                dataType : "json",
			                data : {
                                "opt_type"               : opt_type 
                                ,"id"                    : id
                                ,"courseid"              : courseid
                                ,"course_start"          : course_start
                                ,"course_end"            : course_end
                                ,"course_people"         : course_people
                                ,"course_people_current" : course_people_current
                            },
			                success : function(result){
                                window.location.reload();
			                }
                        });
                        dialog.close();
                    }
                },
                {
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });
    };

    //添加新数据
    $(".add_course_info").on("click",function(){
        do_add_or_update("add");
    });

    //修改数据
    $(".opt-update-course_info").on("click",function(){
        var id=$(this).parent().data("id");
        $.ajax({
            type     :"post",
			url      :"/order_course/get_order_course_info",
			dataType :"json",
			data     :{"id":id},
            success: function(result){
                do_add_or_update("update", result.data);
            }
        });
    });

    //删除数据
    $(".opt-del-course_info").on("click",function(){
        var id = $(this).parents().data("id");
        BootstrapDialog.show({
            title: '删除信息',
            message : "确定删除信息："+ id,
            closable: true, 
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
		            $.ajax({
			            type     :"post",
			            url      :"/order_course/del_course_info",
			            dataType :"json",
			            data     :{"id":id},
			            success  : function(result){
                            window.location.reload();
                        }
		            });
                    dialog.close();
                }
            }, {
                label: '取消',
                cssClass: 'btn',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    });
    
});
