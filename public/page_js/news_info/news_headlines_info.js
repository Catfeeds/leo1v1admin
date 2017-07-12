$(function(){
    Enum_map.append_option_list("grade_part_ex", $("#id_grade_type"));
    Enum_map.append_option_list("grade_part_ex", $(".add_grade"),true);

    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $("#id_grade_type").val(g_args.grade);
    $("#id_search_info").val(g_args.h_info);

    function load_data(){
        var grade      = $("#id_grade_type").val();
        var start_date = $("#id_start_date").val();
        var end_date   = $("#id_end_date").val();
        var h_info     = $("#id_search_info").val();
 		var url        = "/news_info/news_headlines_info?"+
                "&grade="+grade+
                "&start_date="+start_date+
                "&end_date="+end_date+
                "&h_info="+h_info;
		window.location.href = url;
	}

    //筛选
	$(".opt-change").on("change",function(){
		load_data();
	});
    
    //查找功能
    $("#id_search_button").on("click",function(){
        load_data();
    });
    
    //查找信息回车
    $("#id_search_info").on("keypress",function(e){
        if(e.keyCode == 13){
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
    
    var do_add_or_update=function(id, opt_type, item){
        var html_txt  = dlg_get_html_by_class('dlg_add_news_info');
        var html_node = $("<div></div>").html(html_txt);
        if (opt_type=="update") {
            html_node.find(".add_grade").val(item.grade);
            html_node.find(".add_headlines_tags_info").val(item.tags_info);
            html_node.find(".add_headlines_info").val(item.h_info);
        }

        var title= "";
        if (opt_type=="update"){
            title="修改信息";
        }else{
            title="添加信息";
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
                        var grade  = html_node.find(".add_grade").val();
                        var h_info = html_node.find(".add_headlines_info").val();
                        var tags_info= html_node.find(".add_headlines_tags_info").val();
                        
                        $.ajax({
			                type     : "post",
			                url      : "/news_info/add_headlines_info",
			                dataType : "json",
			                data : {
                                "opt_type"   : opt_type 
                                ,"id"        : id 
                                ,"grade"     : grade
                                ,"h_info"    : h_info
                                ,"tags_info" : tags_info
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
    $(".add_news_data").on("click",function(){
        do_add_or_update(0,"add");
    });
    
    //修改数据
    $(".opt-update-news_info").on("click",function(){
        var id=$(this).parent().data("id");
        $.ajax({
            type     : "post",
			url      : "/news_info/get_headlines_info",
			dataType : "json",
			data     : {"id":id},
            success  : function(result){
                do_add_or_update(id,"update",result.data);
            }
        });
    });
    
    //删除数据
    $(".opt-del-news_info").on("click",function(){
        var id    = $(this).parent().data("id");
        BootstrapDialog.show({
            title: '删除',
            message : "是否删除此信息",
            closable: true, 
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
		            $.ajax({
			            type     :"post",
			            url      :"/news_info/del_headlines_info",
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

    $(".opt-push-news_info").on("click",function(){
        var id     = $(this).parent().data("id");
        var device = $(this).data("device");
        var grade  = $(this).parent().data("grade");
        do_ajax("/news_info/push_news_info",{
            "id"        : id,
            "device"    : device,
            "grade"     : grade,
            "messageid" : 4011
        },function(result){
            BootstrapDialog.alert(result.info);
        });
    });
    
});
