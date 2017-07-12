$(function(){
    $('#id_start_date').val(g_start_date);
    $('#id_end_date').val(g_end_date);
    $('#id_lesson_type').val(g_lesson_type);
    $("#id_teacherid").val(g_teacherid);
    
    $("#id_teacherid").admin_select_user({
        "type"   : "teacher",
        "onChange": function(){
            load_data();
        }
    });
    
	//TODO
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
        var start_date  = $("#id_start_date").val();
        var end_date    = $("#id_end_date").val();
        var lesson_type = $("#id_lesson_type").val();
        var teacherid= $("#id_teacherid").val();
        
	    var url="/tea_manage/lesson_account?start_date="+start_date+"&end_date="+end_date+"&lesson_type="+lesson_type+"&teacherid="+teacherid;
	    window.location.href=url;
	}
    
	$(".opt-change").on("change",function(){
		load_data();
	});
    
    $.each($(".td-info-stu"),function( i,item){
        var lesson_type=$(this).data("lesson_type");
        var lessonid=$(this).data("lessonid");
        var courseid=$(this).data("courseid");
        if (lesson_type >=1000) {
            var link=$("<a href=\"javascript:;\"  >学生列表</a>");
            link.on("click",function(){
                show_ajax_table({
                    "title"      : "学生列表(学生总数："+item["con_stu_all"]+"到达率:"+item["con_stu_login"]+"/"+item["con_stu_all"]+")",
                    "field_list" : [{
                        "name"  : "userid",
                        "title" : "id"
                    },{
                        "name"   : "student_nick",
                        "title"  : "学生",
                        "render" : function(val,item){
                            return "<a href = \"/stu_manage?sid="+item["userid"]+"\" target=_blank >"+val+" </a>" ;
                        }
                    },{
                        "name"  : "user_agent_short",
                        "title" : "客户端版本"
                    },{
                        "name"  : "user_login_time",
                        "title" : "学生登陆次数"
                    }],
                    "request_info" : {
                        "url"  : "/small_class/get_small_class_user_list" ,
                        "data" : {
                            "courseid"    : courseid,
                            "lesson_type" : lesson_type,
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


    
});
