$(function(){
    Enum_map.append_checkbox_list("lesson_error",$(".add_error_info"),"error_info");
    $('#id_start_date').val(g_start_date);
    $('#id_end_date').val(g_end_date);
    $('.search_lessonid').val(g_args.lessonid);
    
    $.each($(".td-info-stu"),function(i,item){
        var lesson_type = $(this).data("lesson_type");
        var lessonid    = $(this).siblings(".lessonid").text();
        var courseid    = $(this).data("courseid");
        var id          = $(this).data("id");
        if (lesson_type >=1000) {
            var link=$("<a href=\"javascript:;\">学生列表</a>");
            link.on("click",function(){
                    show_ajax_table({
                        "title"      : "",
                        "field_list" : [{
                            "name"  : "userid",
                            "title" : "id",
                            "class"  : "userid"
                        },{
                            "name"   : "student_nick",
                            "title"  : "学生",
                            "class"  : "stu_nick",
                            "render" : function(val,item){
                                return "<a  href = \"/stu_manage?sid="+item["userid"]+"\" target=_blank >"+val+" </a>" ;
                            }
                        },{
                            "name"  : "user_agent_short",
                            "title" : "客户端版本",
                            "class"  : "stu_agent"
                        },{
                            "name"  : "user_login_time",
                            "title" : "学生登陆次数"
                        },{
                            "title" : "添加错误学生信息",
                            "class"  : "remove-for-xs",
                            "render" : function(val,item){
                                return "<a href=\"javascript:;\" class=\"btn fa fa-plus-square opt-add_stu_err\""
                                    +" data-id=\""+id+"\" title=\"添加错误学生信息\"></a>";
                            }
                        }],
                        "request_info" : {
                            "url"  : "/small_class/get_small_class_user_list" ,
                            "data" : {
                                "courseid" : courseid ,
                                "lesson_type" : lesson_type ,
                                "lessonid"    : lessonid
                            }
                        }
                        ,bind:function($id_body,dlg,result){
                            var con_stu_all=result.data.con_stu_all;
                            var con_stu_login=result.data.con_stu_login;
                            dlg.setTitle( "学生列表(学生总数："+con_stu_all+"到达率:"+con_stu_login+"/"+con_stu_all+") " );
                        }
                    });
            });
            $(this).html(link);
        }
    });

 	$("body").on("click",".opt-add_stu_err",function(){
        var id        = $(this).data("id");
        var stu_nick  = $(this).parents("td").siblings(".stu_nick").text();
        var stu_agent = $(this).parents("td").siblings(".stu_agent").text();
        $.ajax({
            type     :"post",
			url      :"/lesson_manage/add_stu_err",
			dataType :"json",
			data     :{
                "id"        : id,
                "stu_nick"  : stu_nick,
                "stu_agent" : stu_agent
            },
            success: function(result){
		    	window.location.reload();
            }
        });
    });
    
    $(".search_lessonid").on("keypress", function(e){
		if (e.keyCode==13){
		    var id_lesson = $(".search_lessonid").val();
	    	if( id_lesson == ""){
		    	alert("请输入课程ID");
		    }else{
			    var url = "/lesson_manage/error_info?lessonid="+id_lesson;
		    	window.location.href = url;
		    }
		}
	});
    
	$("#id_search_lessonid").on("click", function(){
		var id_lesson = $(".search_lessonid").val();
		if( id_lesson == ""){
			alert("请输入课程ID");
		}else{
			var url = "/lesson_manage/error_info?lessonid="+id_lesson;
			window.location.href = url;
		}
	});

    var update_error_info=function(item){
        var html_node=$("<div></div>").html(dlg_get_html_by_class('dlg_add_error_info'));
        html_node.find(".add_id").val(item.id);
        html_node.find(".add_lessonid").val(item.lessonid);
        html_node.find(".add_lesson_type").val(item.lesson_type);
        html_node.find(".add_tea_nick").val(item.tea_nick);
        html_node.find(".add_tea_area").val(item.tea_area);
        html_node.find(".add_tea_agent").val(item.tea_agent);
        html_node.find(".add_stu_nick_err").val(item.stu_nick_err);
        html_node.find(".add_stu_agent").val(item.stu_agent);
        html_node.find(".add_stu_area").val(item.stu_area);
        html_node.find(".add_error_info_other").val(item.error_info_other);
        html_node.find(".add_error_reason").val(item.error_reason);
        html_node.find(".add_error_solve").val(item.error_solve);
        html_node.find(".add_error_solve_tem").val(item.error_solve_tem);
        html_node.find(".add_server_change").val(item.server_str);
        html_node.find(".add_error_return").val(item.error_return);
        html_node.find(".add_note").val(item.note);
        
        $.each(item.error_info_check,function(n,v){
            html_node.find(".error_info").each(function(){
            var val=$(this).val();
                if(v==val){
                    $(this).attr("checked","checked");
                }
            });
        });
        
        BootstrapDialog.show({
            title: '修改课程错误报告',
            message : html_node,
            closable: true, 
            closeByBackdrop:false,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var id           = html_node.find(".add_id").val();
                        var tea_nick     = html_node.find(".add_tea_nick").val();
                        var tea_area     = html_node.find(".add_tea_area").val();
                        var tea_agent    = html_node.find(".add_tea_agent").val();
                        var stu_nick_err = html_node.find(".add_stu_nick_err").val();
                        var stu_agent    = html_node.find(".add_stu_agent").val();
                        var stu_area     = html_node.find(".add_stu_area").val();
                        var error_info   = "";
                        $("[name='error_info']:checked").each(function() {
			                error_info += $(this).val() + ",";
		                });
                        var error_info_other  = html_node.find(".add_error_info_other").val();
                        var error_reason      = html_node.find(".add_error_reason").val();
                        var error_solve       = html_node.find(".add_error_solve").val();
                        var error_solve_tem   = html_node.find(".add_error_solve_tem").val();
                        var error_return      = html_node.find(".add_error_return").val();
                        var note              = html_node.find(".add_note").val();
                        $.ajax({
			                type     : "post",
			                url      : "/lesson_manage/add_error_info",
			                dataType : "json",
			                data : {
                                "id"                : id
                                ,"tea_nick"         : tea_nick
                                ,"tea_area"         : tea_area 
                                ,"tea_agent"        : tea_agent
                                ,"stu_nick_err"     : stu_nick_err
                                ,"stu_agent"        : stu_agent
                                ,"stu_area"         : stu_area 
                                ,"error_info"       : error_info
                                ,"error_info_other" : error_info_other
                                ,"error_reason"     : error_reason
                                ,"error_solve"      : error_solve
                                ,"error_solve_tem"  : error_solve_tem
                                ,"error_return"     : error_return
                                ,"note"             : note
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

    $(".opt-update-error_info").on("click",function(){
        var id = $(this).parent().data("id");
        $.ajax({
            type     :"post",
			url      :"/lesson_manage/get_error_info",
			dataType :"json",
			data     :{"id":id},
            success: function(result){
                update_error_info(result.list);
            }
        });
    });

    $(".opt-del-error_info").on("click",function(){
        var id = $(this).parent().data("id");
        do_ajax( "/lesson_manage/del_error",{
            "id"  : id 
        }, function(result){
            window.location.reload();
        });
    });
    
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
	function load_data( ){
        var start_date=$("#id_start_date").val();
        var end_date=$("#id_end_date").val();

	    var url="/lesson_manage/error_info?start_date="+start_date+"&end_date="+end_date;
	    window.location.href=url;
	}
    
	$(".opt-change").on("change",function(){
		load_data();
	});
    
	$("#show_error_bar").on("click",function(){
        var start_date=$("#id_start_date").val();
        var end_date=$("#id_end_date").val();
        window.open("/lesson_manage/error_barchart?start_date="+start_date+"&end_date="+end_date,"_blank"); 
    });
    
	$("#show_course_count").on("click",function(){
        var start_date=$("#id_start_date").val();
        var end_date=$("#id_end_date").val();
        window.open("/lesson_manage/course_count?start_date="+start_date+"&end_date="+end_date,"_blank"); 
    });
});
