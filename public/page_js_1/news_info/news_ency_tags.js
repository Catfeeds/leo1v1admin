//SWITCH-TO: ../../../template/news_info/
$(function(){
    Enum_map.append_option_list("ency_type", $("#id_news_type"));
    Enum_map.append_option_list("ency_type", $(".add_news_type"),true);
    Enum_map.append_option_list("grade_part_ex", $("#id_grade"),true);

    $("#id_news_type").val(g_args.news_type);
    $("#id_grade").val(g_args.grade);

    //筛选
	$(".opt-change").on("change",function(){
		load_data();
	});

    function load_data(){
        var news_type = $("#id_news_type").val();
        var grade     = $("#id_grade").val();
		var url       = "/news_info/news_ency_info?news_type="+news_type+"&grade="+grade;
		window.location.href = url;
	}
    
    var update_tags=function(id,item){
        var html_txt  = dlg_get_html_by_class('dlg_update_tags_info');
        var html_node = $("<div></div>").html(html_txt);
        
        html_node.find(".show_month_str").text(item.month_str);
        html_node.find(".show_tags_info").val(item.tags_info);

        BootstrapDialog.show({
            title:"修改标签",
            message : html_node,
            closable: true, 
            closeByBackdrop:false,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var tags_info = html_node.find(".show_tags_info").val();
                        $.ajax({
			                type     : "post",
			                url      : "/news_info/update_tags_info",
			                dataType : "json",
			                data : {
                                "id"         : id 
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

    //修改数据
    $(".opt-update-tag_info").on("click",function(){
        var id=$(this).parent().data("id");
        $.ajax({
            type     : "post",
			url      : "/news_info/get_news_info",
			dataType : "json",
			data     : {
                "id"  : id
                ,"type"  : "tags"
            },
            success  : function(result){
                update_tags(id,result.data);
            }
        });
    });

    
});
