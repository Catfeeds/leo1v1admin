$(function(){

    Enum_map.append_option_list("school_scores_type",$("#id_school_type"));
    Enum_map.append_option_list("school_scores_type",$(".add_school_type"),true);
    Enum_map.append_option_list("year",$("#id_scores_year"));
    Enum_map.append_option_list("year",$(".add_scores_year"),true);
    Enum_map.append_option_list("sh_area",$("#id_scores_area"));
    Enum_map.append_option_list("sh_area",$(".add_scores_area"),true);

    $("#id_school_type").val(g_args.school_type);
    $("#id_scores_area").val(g_args.scores_area);
    $("#id_scores_year").val(g_args.scores_year);
    $("#id_school_info").val(g_args.school_info);

    var do_add_or_update=function(opt_type,item){
        var html_node=$("<div></div>").html(dlg_get_html_by_class('dlg_add_scores_info'));
        if (opt_type=="update") {
            html_node.find(".add_schoolid").val(item.schoolid);
            html_node.find(".add_schoolid").attr("readonly","readonly");
            html_node.find(".add_school_name").val(item.school_name);
            html_node.find(".add_school_name_high").val(item.school_name_high);
            html_node.find(".add_school_type").val(item.school_type);
            html_node.find(".add_school_major").val(item.school_major);
            html_node.find(".add_scores_school").val(item.scores_school);
            html_node.find(".add_scores_sum").val(item.scores_sum);
            html_node.find(".add_scores_chinese").val(item.scores_chinese);
            html_node.find(".add_scores_math").val(item.scores_math);
            html_node.find(".add_scores_year").val(item.scores_year);
            html_node.find(".add_scores_area").val(item.scores_area);
            html_node.find(".add_school_quota").val(item.school_quota);
        }

        var title= "";
        var scores_area_old="";
        if (opt_type=="update"){
            scores_area_old=item.scores_area;
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
                        var schoolid         = html_node.find(".add_schoolid").val();
                        var school_name      = html_node.find(".add_school_name").val();
                        var school_name_high = html_node.find(".add_school_name_high").val();
                        var school_type      = html_node.find(".add_school_type").val();
                        var school_major     = html_node.find(".add_school_major").val();
                        var scores_school    = html_node.find(".add_scores_school").val();
                        var scores_sum       = html_node.find(".add_scores_sum").val();
                        var scores_chinese   = html_node.find(".add_scores_chinese").val();
                        var scores_math      = html_node.find(".add_scores_math").val();
                        var scores_year      = html_node.find(".add_scores_year").val();
                        var scores_area      = html_node.find(".add_scores_area").val();
                        var school_quota     = html_node.find(".add_school_quota").val();
                        $.ajax({
			                type     : "post",
			                url      : "/school_info/add_scores_info",
			                dataType : "json",
			                data : {
                                "opt_type"          : opt_type 
                                ,"schoolid"         : schoolid 
                                ,"school_name"      : school_name 
                                ,"school_name_high" : school_name_high 
                                ,"school_type"      : school_type
                                ,"school_major"     : school_major
                                ,"scores_school"    : scores_school
                                ,"scores_sum"       : scores_sum
                                ,"scores_chinese"   : scores_chinese
                                ,"scores_math"      : scores_math
                                ,"scores_year"      : scores_year
                                ,"scores_area"      : scores_area
                                ,"school_quota"     : school_quota
                                ,"scores_area_old"  : scores_area_old 
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

    function load_data($school_type,$scores_area,$scores_year,$school_info){
		var url = "/school_info/search_scores?school_type="+$school_type+
                  "&scores_area="+$scores_area+
                  "&scores_year="+$scores_year+
                  "&school_info="+$school_info;
		window.location.href=url;
	}

    //筛选
	$(".will_change").on("change",function(){
		var school_type = $("#id_school_type").val();
		var scores_area = $("#id_scores_area").val();
		var scores_year = $("#id_scores_year").val();
		var school_info = $("#id_school_info").val();
		load_data(school_type,scores_area,scores_year,school_info);
	});
     
    //最低投档页面筛选
	$(".will_change_min").on("change",function(){
		var school_type = $("#id_school_type").val();
		load_data(school_type,-1,-1,'');
	});

    //查找功能
    $("#id_search_school").on("click",function(){
		var school_type = $("#id_school_type").val();
		var scores_area = $("#id_scores_area").val();
		var scores_year = $("#id_scores_year").val();
        var school_info = $("#id_school_info").val();
		load_data(school_type,scores_area,scores_year,school_info);
    });

    //查找信息回车
    $("#id_school_info").on("keypress",function(e){
        if(e.keyCode == 13){
		    var school_type = $("#id_school_type").val();
		    var scores_area = $("#id_scores_area").val();
		    var scores_year = $("#id_scores_year").val();
            var school_info = $("#id_school_info").val();
		    load_data(school_type,scores_area,scores_year,school_info);
        }
    });

    //添加分数新数据
    $("#id_add_scores_info").on("click",function(){
        do_add_or_update("add"); 
    });

    //修改分数
    $(".opt-update-scores").on("click",function(){
        var schoolid= $(this).parent().data("schoolid");
        var school_type= $(this).parent().data("school_type");
        var scores_area= $(this).parent().data("scores_area");
        var scores_year= $(this).parent().data("scores_year");
        $.ajax({
            type     :"post",
			url      :"/school_info/get_show_scores_info",
			dataType :"json",
			data     :{
                "schoolid" : schoolid,
                "school_type" : school_type,
                "scores_year" : scores_year,
                "scores_area" : scores_area
            },
            success: function(result){
                do_add_or_update("update", result.ret_info);
            }
        });
    });
    
    //删除数据
    $(".opt-del-scores_info").on("click",function(){
        var schoolid = $(this).parent().data("schoolid");
        var scores_year= $(this).parent().data("scores_year");
        var scores_area= $(this).parent().data("scores_area");
        
        BootstrapDialog.show({
            title: '删除信息',
            message : "确定删除此信息?"+schoolid,
            closable: true, 
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
		            $.ajax({
			            type     :"post",
			            url      :"/school_info/del_scores_info",
			            dataType :"json",
			            data     :{
                            "schoolid"    : schoolid,
                            "scores_year" : scores_year,
                            "scores_area" : scores_area
                        },
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
