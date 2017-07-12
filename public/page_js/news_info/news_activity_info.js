$(function(){
    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $("#id_news_info").val(g_args.news_info);

    function load_data(){
        var start_date = $("#id_start_date").val();
        var end_date   = $("#id_end_date").val();
        var news_info  = $("#id_news_info").val();
        var url = "/news_info/news_activity_info?start_date="+start_date+
                "&end_date="+end_date+
                "&news_info="+news_info;
		window.location.href = url;
	}

    //筛选
	$(".opt-change").on("change",function(){
		load_data();
	});
    
    //查找功能
    $("#id_search_news").on("click",function(){
        load_data();
    });
    
    //查找信息回车
    $("#id_news_info").on("keypress",function(e){
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
        var html_txt  = dlg_get_html_by_class('dlg_add_activity_info');
        html_txt=html_txt.
            replace(/\"id_upload_add\"/, "\"id_upload_add_tmp\"" ).
            replace(/\"id_container_add\"/, "\"id_container_add_tmp\"" ).
            replace(/\"id_upload_add_min\"/, "\"id_upload_add_min_tmp\"" ).
            replace(/\"id_container_add_min\"/, "\"id_container_add_min_tmp\"" );
        var html_node = $("<div></div>").html(html_txt);
        var pic_url="";
        var pic_url_min="";
        var pic_img="";
        var pic_img_min="";
        if (opt_type=="update") {
            pic_url = item.img;
            pic_url_min = item.img_min;
            pic_img = "<img width=100 src=\""+pic_url+"\" />";
            pic_img_min = "<img width=50 src=\""+pic_url_min+"\" />";
            html_node.find(".add_header_img").html(pic_img);
            html_node.find(".add_header_img_min").html(pic_img_min);
            html_node.find(".add_activity_name").val(item.name);
            html_node.find(".add_activity_start_date").val(item.start_time_str);
            html_node.find(".add_activity_end_date").val(item.end_time_str);
            html_node.find(".add_activity_url").val(item.url);
            html_node.find(".add_activity_info").val(item.info);
            html_node.find(".add_activity_title").val(item.title);
        }

        var title= "";
        if (opt_type=="update"){
            title="修改信息";
        }else{
            title="添加信息";
        }
        
	    html_node.find('.add_activity_start_date').datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
	    });
        
	    html_node.find('.add_activity_end_date').datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
	    });
        
        BootstrapDialog.show({
            title           : title,
            message         : html_node,
            closable        : true, 
            closeByBackdrop : false,
            onshown         : function() {
                custom_qiniu_upload ("id_upload_add_tmp","id_container_add_tmp",
                                     g_args.qiniu_upload_domain_url , true,
                                     function (up, info, file){
                                         var res = $.parseJSON(info);
                                         pic_url= g_args.qiniu_upload_domain_url + res.key;
                                         pic_img="<img width=100 src=\""+pic_url+"\" />";
                                         html_node.find(".add_header_img").html(pic_img);
                                     });
                custom_qiniu_upload ("id_upload_add_min_tmp","id_container_add_min_tmp",
                                     g_args.qiniu_upload_domain_url , true,
                                     function (up, info, file){
                                         var res = $.parseJSON(info);
                                         pic_url_min= g_args.qiniu_upload_domain_url + res.key;
                                         pic_img_min="<img width=80 src=\""+pic_url_min+"\" />";
                                         html_node.find(".add_header_img_min").html(pic_img_min);
                                     });
            },
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var activity_name = html_node.find(".add_activity_name").val();
                        var start_date    = html_node.find(".add_activity_start_date").val();
                        var end_date      = html_node.find(".add_activity_end_date").val();
                        var url           = html_node.find(".add_activity_url").val();
                        var info          = html_node.find(".add_activity_info").val();
                        var title         = html_node.find(".add_activity_title").val();
                        var img           = pic_url;  
                        var img_min       = pic_url_min;  
                        
                        $.ajax({
			                type     : "post",
			                url      : "/news_info/add_activity_info",
			                dataType : "json",
			                data : {
                                "opt_type"       : opt_type 
                                ,"id"            : id 
                                ,"activity_name" : activity_name 
                                ,"start_date"    : start_date
                                ,"end_date"      : end_date
                                ,"url"           : url
                                ,"info"          : info 
                                ,"title"         : title
                                ,"img"           : img
                                ,"img_min"       : img_min
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
			url      : "/news_info/get_activity_info",
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
            message : "确定删除信息?",
            closable: true, 
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
		            $.ajax({
			            type     :"post",
			            url      :"/news_info/del_activity_info",
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
