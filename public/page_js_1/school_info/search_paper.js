$(function(){
    
    Enum_map.append_option_list("subject", $("#id_subject"));
    Enum_map.append_option_list("sh_area", $("#id_area_id"));
    Enum_map.td_show_desc("subject", $(".td-subject"));
    Enum_map.td_show_desc("sh_area", $(".td-area_id"));
    $("#id_paper_type").val(g_args.paper_type);
    $("#id_paper_sub_type").val(g_args.paper_sub_type);
    $("#id_area_id").val(g_args.area_id);
    $("#id_subject").val(g_args.subject);
    $("#id_paper_year").val(g_args.paper_year);

    var update_sub_type = function (){
        ajax_set_select_box($("#id_paper_sub_type"),
                               "/school_info/paper_get_sub_type_list",
                               {
                                   "paper_type":$("#id_paper_type").val()
                               },
                            g_args.paper_sub_type
                           );
    };
    update_sub_type();

    var do_add_or_update=function(opt_type,item){
        var html_node=$("<div></div>").html(dlg_get_html_by_class('dlg_add_paper_info'));
        Enum_map.append_option_list("subject", html_node.find(".add_paper_subject"),true);
        Enum_map.append_option_list("sh_area", html_node.find(".add_paper_area") ,true);

        if (opt_type=="update") {
            html_node.find(".add_paper_name").val(item.paper_name);
            html_node.find(".add_paper_url").val(item.paper_url);
            html_node.find(".add_paper_type").val(item.paper_type);
            html_node.find(".add_paper_sub_type").val(item.paper_sub_type);
            html_node.find(".add_paper_subject").val(item.subject);
            html_node.find(".add_paper_area").val(item.area_id);
            html_node.find(".add_paper_year").val(item.paper_year);
        }else{
            html_node.find(".add_paper_type").val(1);
        }

        var do_update_sub_type =function (val){
            ajax_set_select_box(
                html_node.find(".add_paper_sub_type"),
                "/school_info/paper_get_sub_type_list",
                {
                    "paper_type": html_node.find(".add_paper_type").val()
                },
                val ,true
            );
        };

        html_node.find(".add_paper_type").on("change",function(){
            do_update_sub_type(1);
        });

        var title= "";

        if (opt_type=="update"){
            do_update_sub_type( item.paper_sub_type );
            title="修改试卷信息";
        }else{
            do_update_sub_type(1);
            title="添加试卷信息";
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
                        var paperid =0;     
                        if (opt_type=="update") {
		                    paperid        =  item.paperid;
                        }
                        var paper_name     = html_node.find(".add_paper_name").val();
                        var paper_url      = html_node.find(".add_paper_url").val();
                        var paper_type     = html_node.find(".add_paper_type").val();
                        var paper_sub_type = html_node.find(".add_paper_sub_type").val();
                        var paper_subject  = html_node.find(".add_paper_subject").val();
                        var paper_area     = html_node.find(".add_paper_area").val();
                        var paper_year     = html_node.find(".add_paper_year").val();

                        $.ajax({
			                type     : "post",
			                url      : "/school_info/add_paper_info",
			                dataType : "json",
			                data     : {
                                "opt_type"        : opt_type 
                                ,"paperid"        : paperid 
                                ,"paper_name"     : paper_name 
                                ,"paper_url"      : paper_url
                                ,"paper_type"     : paper_type 
                                ,"paper_sub_type" : paper_sub_type
                                ,"paper_subject"  : paper_subject
                                ,"paper_area"     : paper_area
                                ,"paper_year"     : paper_year
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
    
    $("#id_paper_type").on("change",  function(){
        var url = window.location.pathname
            +"?paper_type="+ $("#id_paper_type").val()
            +"&area_id="+ $("#id_area_id").val()
            +"&subject="+ $("#id_subject").val()
            +"&paper_year="+ $("#id_paper_year").val();
        window.location.href=url;
    });

    $(".opt-change").on("change",function(){
        var url = window.location.pathname
            +"?paper_type="+ $("#id_paper_type").val()
            +"&paper_sub_type="+ $("#id_paper_sub_type").val()
            +"&area_id="+ $("#id_area_id").val()
            +"&subject="+ $("#id_subject").val()
            +"&paper_year="+ $("#id_paper_year").val();
        window.location.href=url;
    });

    //添加试卷信息
    $("#id_add_paper_info").on("click",function(){
        do_add_or_update("add");
    });

    //修改试卷信息
    $(".opt-update-paper").on("click",function(){
        var paperid=$(this).parent().data("paperid");
        $.ajax({
            type     :"post",
			url      :"/school_info/get_show_paper_info",
			dataType :"json",
			data     :{"paperid":paperid},
            success: function(result){
                do_add_or_update("update", result.ret_info );
            }
        });
    });

    //删除试卷信息
    $(".opt-del-paper").on("click",function(){
        var paperid = $(this).parent().data("paperid");
        //alert(paperid);
        BootstrapDialog.show({
            title: '删除信息',
            message : "确定删除试卷："+ paperid,
            closable: true, 
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
		            $.ajax({
			            type     :"post",
			            url      :"/school_info/del_paper_info",
			            dataType :"json",
			            data     :{"paperid":paperid},
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










