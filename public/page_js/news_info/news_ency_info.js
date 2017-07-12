$(function(){
    Enum_map.append_option_list("ency_type", $("#id_news_type"));
    Enum_map.append_option_list("ency_type", $(".add_news_type"),true);
    Enum_map.append_option_list("grade_part_ex", $("#id_grade"));
    Enum_map.append_option_list("grade_part_ex", $(".add_grade"),true);
    Enum_map.append_option_list("is_top", $("#id_is_top"));
    Enum_map.append_option_list("is_top", $(".add_is_top"),true);

    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $("#id_news_type").val(g_args.news_type);
    $("#id_grade").val(g_args.grade);
    $("#id_is_top").val(g_args.is_top);
    $("#id_news_info").val(g_args.news_info);

    function load_data(){
        var news_type  = $("#id_news_type").val();
        var grade      = $("#id_grade").val();
        var is_top     = $("#id_is_top").val();
        var start_date = $("#id_start_date").val();
        var end_date   = $("#id_end_date").val();
        var news_info  = $("#id_news_info").val();
        var url = '';
        if(news_type==99){
		    url = "/news_info/news_ency_info?news_type="+news_type+"&grade=1";
        }else{
 		    url = "/news_info/news_ency_info?news_type="+news_type+
                "&grade="+grade+
                "&is_top="+is_top+
                "&start_date="+start_date+
                "&end_date="+end_date+
                "&news_info="+news_info;
        }
		window.location.href = url;
	}

	$(".opt-change").on("change",function(){
		load_data();
	});
    
    $("#id_search_news").on("click",function(){
        load_data();
    });
    
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

    $(".opt-push-news_info").on("click",function(){
        var id    = $(this).parent().data("id");
        var device= $(this).data("device");
        var grade = $(this).parent().data("grade");
        do_ajax("/news_info/push_news_info",
                {
                    "id"        : id,
                    "device"    : device,
                    "grade"     : grade,
                    "messageid" : 4012
                },
                function(result){
                    BootstrapDialog.alert(result.info);
                });
    });

    var do_add_or_update=function(id, opt_type, item){
        var html_txt  = dlg_get_html_by_class('dlg_add_news_info');
        
        html_txt=html_txt.
            replace(/\"editor_news_info\"/, "\"editor_news_info_"+id+"\"" ).
            replace(/\"id_upload_add\"/, "\"id_upload_add_tmp\"" ).
            replace(/\"id_container_add\"/, "\"id_container_add_tmp\"" );
        
        var html_node = $("<div></div>").html(html_txt);
        var pic_url="";
        var pic_img="";
        if (opt_type=="update") {
            pic_url = item.news_img;
            pic_img = "<img width=100 src=\""+pic_url+"\" />";
            html_node.find(".add_header_img").html(pic_img);
            html_node.find(".add_news_type").val(item.news_type);
            html_node.find(".add_grade").val(item.grade);
            html_node.find(".add_news_title").val(item.news_title);
            html_node.find(".add_news_info").val(item.news_info);
            html_node.find(".add_news_intro").val(item.news_intro);
            html_node.find(".add_is_top").val(item.is_top);
        }
        
        var title= "";
        if (opt_type=="update"){
            title="修改信息";
        }else{
            title="添加信息";
        }

        BootstrapDialog.show({
            title           : title,
            message         : html_node,
            closable        : true, 
            closeByBackdrop : false,
            onshown         : function() {
                UM.getEditor("editor_news_info_"+id);

                custom_qiniu_upload ("id_upload_add_tmp","id_container_add_tmp",
                                     g_args.qiniu_upload_domain_url , true,
                                     function (up, info, file){
                                         var res = $.parseJSON(info);
                                         pic_url= g_args.qiniu_upload_domain_url + res.key;
                                         pic_img="<img width=100 src=\""+pic_url+"\" />";
                                         html_node.find(".add_header_img").html(pic_img);
                                     });
            },
            buttons : [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var news_type  = html_node.find(".add_news_type").val();
                        var grade      = html_node.find(".add_grade").val();
                        var news_title = html_node.find(".add_news_title").val();
                        var news_img   = pic_url;  
                        var news_info  = html_node.find(".add_news_info").val();
                        var news_intro = html_node.find(".add_news_intro").val();
                        var is_top     = html_node.find(".add_is_top").val();
                        
                        $.ajax({
			                type     : "post",
			                url      : "/news_info/add_news_info",
			                dataType : "json",
			                data : {
                                "opt_type"    : opt_type 
                                ,"id"         : id 
                                ,"news_type"  : news_type
                                ,"grade"      : grade
                                ,"news_title" : news_title
                                ,"news_img"   : news_img
                                ,"news_info"  : news_info
                                ,"news_intro" : news_intro
                                ,"is_top"     : is_top 
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


    $(".add_news_data").on("click",function(){
        do_add_or_update(0,"add");
    });

    $(".opt-update-news_info").on("click",function(){
        var id=$(this).parent().data("id");
        $.ajax({
            type     : "post",
			url      : "/news_info/get_news_info",
			dataType : "json",
			data     : {"id":id},
            success  : function(result){
                do_add_or_update(id,"update",result.data);
            }
        });
    });

    $(".opt-browse-news_info").on("click",function(){
        var id=$(this).get_opt_data("id");
        
        $.ajax({
            type     :"post",
			url      :"/news_info/get_news_info",
			dataType :"json",
			data     :{"id":id},
            success: function(result){
                var list = result.data;
                var html_txt=dlg_get_html_by_class('dlg_browse_news_info');
                var html_node =$("<div></div>").html(html_txt);
                html_node.find(".show_news_title").html(list.news_title);
                html_node.find(".show_news_info").html(list.news_info);
                html_node.find(".show_news_intro").html(list.news_intro);
                BootstrapDialog.show({
                    title: '专题信息内容',
                    message : html_node,
                    closable: true, 
                    closeByBackdrop:false,
                    buttons: [
                        {
                            label: '取消',
                            cssClass: 'btn',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }]
                });
            }
        });
    });
    
    $(".opt-del-news_info").on("click",function(){
        var id    = $(this).parent().data("id");
        var title = $(this).parents("td").siblings(".news_title").text();
        BootstrapDialog.show({
            title: '删除',
            message : "确定删除信息："+ title,
            closable: true, 
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
		            $.ajax({
			            type     :"post",
			            url      :"/news_info/del_news_info",
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
