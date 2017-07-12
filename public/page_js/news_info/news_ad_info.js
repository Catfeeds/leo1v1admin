$(function(){
    Enum_map.append_option_list("ad_status", $("#id_ad_status"));
    Enum_map.append_option_list("ad_status", $(".add_ad_status"),true);
    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $("#id_ad_info").val(g_args.ad_info);
    $("#id_ad_status").val(g_args.status);

    function load_data(){
        var start_date = $("#id_start_date").val();
        var end_date   = $("#id_end_date").val();
        var ad_info  = $("#id_ad_info").val();
        var status= $("#id_ad_status").val();
        var url = "/news_info/news_ad_info?start_date="+start_date+
                "&end_date="+end_date+
                "&ad_status="+status+
                "&ad_info="+ad_info;
		window.location.href = url;
	}

    //筛选
	$(".opt-change").on("change",function(){
		load_data();
	});
    
    //查找功能
    $("#id_search_ad").on("click",function(){
        load_data();
    });
    
    //查找信息回车
    $("#id_ad_info").on("keypress",function(e){
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
        var html_txt  = dlg_get_html_by_class('dlg_add_ad_info');
        html_txt=html_txt.
            replace(/\"id_upload_add\"/, "\"id_upload_add_tmp\"" ).
            replace(/\"id_container_add\"/, "\"id_container_add_tmp\"" ).
            replace(/\"id_upload_add_img\"/, "\"id_upload_add_img_tmp\"" ).
            replace(/\"id_container_add_img\"/, "\"id_container_add_img_tmp\"" );
        var html_node = $("<div></div>").html(html_txt);
        var ad_url  = "";
        var img_url = "";
        var ad_pic  = "";
        var img_pic = "";
        if (opt_type == "update") {
            ad_url  = item.ad_url;
            img_url = item.img_url;
            ad_pic= "<img width=100 src = \""+ad_url+"\" />";
            img_pic= "<img width=50 src = \""+img_url+"\" />";
            html_node.find(".add_ad_url").html(ad_pic);
            html_node.find(".add_img_url").html(img_pic);
            html_node.find(".add_ad_start_date").val(item.start_time_str);
            html_node.find(".add_ad_end_date").val(item.end_time_str);
            html_node.find(".add_ad_status").val(item.status);
            html_node.find(".add_ad_title").html(item.title);
            html_node.find(".add_ad_info").html(item.info);
        }

        var title= "";
        if (opt_type=="update"){
            title="修改信息";
        }else{
            title="添加信息";
        }
        
	    html_node.find('.add_ad_start_date').datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
	    });
        
	    html_node.find('.add_ad_end_date').datetimepicker({
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
                                         ad_url= g_args.qiniu_upload_domain_url + res.key;
                                         ad_pic="<img width=100 src=\""+ad_url+"\" />";
                                         html_node.find(".add_ad_url").html(ad_pic);
                                     });
                custom_qiniu_upload ("id_upload_add_img_tmp","id_container_add_img_tmp",
                                     g_args.qiniu_upload_domain_url , true,
                                     function (up, info, file){
                                         var res = $.parseJSON(info);
                                         img_url= g_args.qiniu_upload_domain_url + res.key;
                                         img_pic="<img width=80 src=\""+img_url+"\" />";
                                         html_node.find(".add_img_url").html(img_pic);
                                     });
            },
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var start_date = html_node.find(".add_ad_start_date").val();
                        var end_date   = html_node.find(".add_ad_end_date").val();
                        var status     = html_node.find(".add_ad_status").val();
                        var url        = html_node.find(".add_url").val();
                        var title      = html_node.find(".add_ad_title").val();
                        var info       = html_node.find(".add_ad_info").val();
                        
                        $.ajax({
			                type     : "post",
			                url      : "/news_info/add_ad_info",
			                dataType : "json",
			                data : {
                                "opt_type"    : opt_type 
                                ,"id"         : id 
                                ,"start_date" : start_date
                                ,"end_date"   : end_date
                                ,"status"     : status
                                ,"url"        : url
                                ,"title"      : title
                                ,"info"       : info 
                                ,"ad_url"     : ad_url
                                ,"img_url"    : img_url
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
    $(".add_new_ad_info").on("click",function(){
        do_add_or_update(0,"add");
    });

    //修改数据
    $(".opt-update-ad_info").on("click",function(){
        var id=$(this).parent().data("id");
        $.ajax({
            type     : "post",
			url      : "/news_info/get_ad_info",
			dataType : "json",
			data     : {
                "id":id
            },
            success  : function(result){
                do_add_or_update(id,"update",result.data);
            }
        });
    });

    //删除数据
    $(".opt-del").on("click",function(){
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
			            type     : "post",
			            url      : "/news_info/del_ad_info",
			            dataType : "json",
			            data     : {"id":id},
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

    $("body").on("change",".add_ad_status",function(){
        var status = $(this).val();
        if(status==0){
            $("body").find(".share_s").hide();
        }else{
            $("body").find(".share_s").show();
        }
    });
});
