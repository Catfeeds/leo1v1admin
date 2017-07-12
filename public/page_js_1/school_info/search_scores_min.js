// SWITCH-TO: ../../../template/school_info/

$(function(){
    
    var do_add_or_update=function(opt_type,item){
        var html_node=$("<div></div>").html(dlg_get_html_by_class('dlg_update_scores_min'));
        if (opt_type=="update") {
            html_node.find(".update_scores_year").val(item.scores_year);
            html_node.find(".update_scores_zero").val(item.scores_zero);
            html_node.find(".update_scores_first").val(item.scores_first);
            html_node.find(".update_scores_quota").val(item.scores_quota);
            html_node.find(".update_scores_high").val(item.scores_high);
            html_node.find(".update_scores_polytechnic").val(item.scores_polytechnic);
            html_node.find(".update_scores_undergra").val(item.scores_undergra);
        }

        var title= "";
        if (opt_type=="update"){
            title="修改分数信息";
        }else{
            title="添加分数信息";
        }

        BootstrapDialog.show({
            title: title,
            message : html_node,
            closable: true, 
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var scores_year        = html_node.find(".update_scores_year").val();
                        var scores_zero        = html_node.find(".update_scores_zero").val();
                        var scores_first       = html_node.find(".update_scores_first").val();
                        var scores_quota       = html_node.find(".update_scores_quota").val();
                        var scores_high        = html_node.find(".update_scores_high").val();
                        var scores_polytechnic = html_node.find(".update_scores_polytechnic").val();
                        var scores_undergra    = html_node.find(".update_scores_undergra").val();
                        $.ajax({
			                type     : "post",
			                url      : "/school_info/add_scores_min_info",
			                dataType : "json",
			                data : {
                                "opt_type"            : opt_type 
                                ,"scores_year"        : scores_year
                                ,"scores_zero"        : scores_zero
                                ,"scores_first"       : scores_first
                                ,"scores_quota"       : scores_quota
                                ,"scores_high"        : scores_high
                                ,"scores_polytechnic" : scores_polytechnic
                                ,"scores_undergra"    : scores_undergra
                            },
			                success : function(result){
                                //BootstrapDialog.alert(result['info']);
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
    $("#id_add_scores_min").on("click",function(){
        do_add_or_update("add");
    });

    //修改最低投档分数信息
    $(".opt-update-scores_min").on("click",function(){
        var scores_year=$(this).parents("td").siblings(".scores_year").text();
        $.ajax({
            type     :"post",
			url      :"/school_info/get_show_scores_min",
			dataType :"json",
			data     :{"scores_year":scores_year},
            success: function(result){
                do_add_or_update("update", result.ret_info);
            }
        });

    });

    //删除最低投档线信息
    $(".opt-del-scores_min").on("click",function(){
        var scores_year = $(this).parents("td").siblings(".scores_year").text();
        
        BootstrapDialog.show({
            title: '删除信息',
            message : "确定删除信息："+ scores_year,
            closable: true, 
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
		            $.ajax({
			            type     :"post",
			            url      :"/school_info/del_min",
			            dataType :"json",
			            data     :{"scores_year":scores_year},
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

