$(function(){
    $("#id_type_v").val(g_args.type);
    
    function load_data(){
        var type = $("#id_type_v").val();
	    var url = "/order_course/order_course_list?type="+type;
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
        var html_txt=dlg_get_html_by_class('dlg_add_course_list');
        var html_node =$("<div></div>").html(html_txt);

        if (opt_type=="update") {
            html_node.find(".add_courseid").val(item.courseid);
            html_node.find(".add_course_name").val(item.course_name);
        }

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
		                var courseid    = html_node.find(".add_courseid").val();
                        var course_name = html_node.find(".add_course_name").val();
                        $.ajax({
			                type     : "post",
			                url      : "/order_course/add_order_course_list",
			                dataType : "json",
			                data : {
                                "opt_type"     : opt_type 
                                ,"courseid"    : courseid
                                ,"course_name" : course_name
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
        var courseid=$(this).parent().data("courseid");
        $.ajax({
            type     :"post",
			url      :"/order_course/get_order_course_list",
			dataType :"json",
			data     :{"courseid":courseid},
            success: function(result){
                do_add_or_update("update", result.data);
            }
        });
    });

    //删除数据
    $(".opt-del-course_info").on("click",function(){
        var courseid = $(this).parent().data("courseid");
        BootstrapDialog.show({
            title: '删除信息',
            message : "确定删除信息："+ courseid,
            closable: true, 
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
		            $.ajax({
			            type     :"post",
			            url      :"/order_course/del_course_list",
			            dataType :"json",
			            data     :{"courseid":courseid},
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
