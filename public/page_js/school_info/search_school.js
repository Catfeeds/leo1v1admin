$(function(){
    Enum_map.append_option_list("grade_part_ex", $("#id_school_type"));
    Enum_map.append_option_list("sh_area", $("#id_school_area"));
    $("#id_school_type").val(g_args.school_type);
    $("#id_school_sub_type").val(g_args.school_sub_type);
    $("#id_school_area").val(g_args.school_area);
    $("#id_school_info").val(g_args.school_info);
    
    var update_sub_type = function (){
        ajax_set_select_box($("#id_school_sub_type"),
                               "/school_info/school_get_sub_type_list",
                               {
                                   "school_type":$("#id_school_type").val()
                               },
                            g_args.school_sub_type
                           );
    };
    update_sub_type();

    function load_data($school_type,$school_sub_type,$school_area,$school_info){
		var url = "/school_info/search_school?school_sub_type="+$school_sub_type+
                  "&school_type="+$school_type+
                  "&school_area="+$school_area;
		window.location.href = url;
	}

	$("#id_school_type").on("change",function(){
		var school_type = $("#id_school_type").val();
		var school_area = $("#id_school_area").val();
		load_data(school_type,-1,school_area);
	});

    //筛选
	$(".will_change").on("change",function(){
		var school_type     = $("#id_school_type").val();
		var school_sub_type = $("#id_school_sub_type").val();
		var school_area     = $("#id_school_area").val();
		load_data(school_type,school_sub_type,school_area);
	});
    
    //查找功能
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

    //添加或更新学校信息
    var do_add_or_update=function( id, opt_type, item ){
        var html_txt=dlg_get_html_by_class('dlg_add_school_info');
        html_txt=html_txt.
            replace(/\"editor_school_info\"/, "\"editor_school_info_"+id+"\"" ).
            replace(/\"id_upload_add\"/, "\"id_upload_add_tmp\"" ).
            replace(/\"id_container_add\"/, "\"id_container_add_tmp\"" ).
            replace(/\"id_upload_add_min\"/, "\"id_upload_add_min_tmp\"" ).
            replace(/"id_container_add_min"/, "\"id_container_add_min_tmp\"" )
        ;
        var html_node =$("<div></div>").html(html_txt);
        Enum_map.append_option_list("grade_part_ex", html_node.find(".add_school_type"),true);
        Enum_map.append_option_list("sh_area", html_node.find(".add_school_area") ,true);

        var pic_url="";
        var pic_url_min="";
        var pic_img="";
        var pic_img_min="";
        //lll
        if (opt_type=="update") {
            pic_url_min=item.school_img_min;
            pic_url=item.school_img;
            pic_img_min="<img width=80 src=\""+pic_url_min+"\" />";
            pic_img="<img width=100 src=\""+pic_url+"\" />";
            html_node.find(".add_schoolid").val(item.schoolid);
            html_node.find(".add_schoolid").attr("readonly","readonly");
            html_node.find(".add_school_name").val(item.school_name);
            html_node.find(".add_header_img_min").html(pic_img_min);
            //html_node.find(".add_header_img_min_url").html(pic_url_min);
            html_node.find(".add_header_img").html(pic_img);
            //html_node.find(".add_header_img_url").html(pic_url);
            html_node.find(".add_school_sub_type").val(item.school_sub_type);
            html_node.find(".add_school_sub_type2").val(item.school_sub_type2);
            html_node.find(".add_school_type").val(item.school_type);
            html_node.find(".add_school_area").val(item.school_area);
            html_node.find(".add_school_intro").val(item.school_intro);
            html_node.find(".add_school_intro2").val(item.school_intro2);
            html_node.find(".add_school_charac").val(item.school_charac);
            html_node.find(".add_school_contact").val(item.school_contact);
            html_node.find(".add_school_address").val(item.school_address);
            html_node.find(".add_school_web").val(item.school_web);
            html_node.find(".add_school_recruit").val(item.school_recruit);
            html_node.find(".add_school_study_cost").val(item.school_study_cost);
            html_node.find(".add_school_live_cost").val(item.school_live_cost);
            html_node.find(".add_school_live_info").val(item.school_live_info);
            html_node.find(".add_school_pre").val(item.school_pre);
        }else{
            html_node.find(".add_school_type").val(1);
        }

        var do_update_sub_type =function (val){
            ajax_set_select_box(
                html_node.find(".add_school_sub_type"),
                "/school_info/school_get_sub_type_list",
                {
                    "school_type": html_node.find(".add_school_type").val()
                },
                val ,true
            );
        };

        html_node.find(".add_school_type").on("change",function(){
            var school_type = html_node.find(".add_school_type").val();
            if(school_type==3){
                do_update_sub_type(11);
            }else{
                do_update_sub_type(1);
            }
        });
        var title= "";
        if (opt_type=="update"){
            do_update_sub_type( item.school_sub_type );
            title="修改学校信息";
        }else{
            do_update_sub_type(1);
            title="添加学校信息";
        }

        BootstrapDialog.show({
            title: title,
            message : html_node,
            closable: true, 
            closeByBackdrop:false,
            onshown:function(dialog){
                UM.getEditor("editor_school_info_"+id);
                
                custom_qiniu_upload ("id_upload_add_tmp","id_container_add_tmp",
                                     g_args.qiniu_upload_domain_url , true,
                                     function (up, info, file){
                                         var res = $.parseJSON(info);
                                         pic_url= g_args.qiniu_upload_domain_url + res.key;
                                         pic_img="<img width=100 src=\""+pic_url+"\" />";
                                         html_node.find(".add_header_img").html(pic_img);
                                         //html_node.find(".add_header_img_url").html(pic_url);
                                     });

                custom_qiniu_upload ("id_upload_add_min_tmp","id_container_add_min_tmp",
                                     g_args.qiniu_upload_domain_url, true,
                                     function (up, info, file){
                                         var res = $.parseJSON(info);
                                         pic_url_min= g_args.qiniu_upload_domain_url + res.key;
                                         pic_img_min="<img width=80 src=\""+pic_url_min+"\" />";
                                         html_node.find(".add_header_img_min").html(pic_img_min);
                                         //html_node.find(".add_header_img_min_url").html(pic_url_min);
                                     },'35kb');
            },
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var schoolid = 0;
                        if (opt_type=="update") {
		                    schoolid = item.schoolid;
                        }else{
		                    schoolid = html_node.find(".add_schoolid").val();
                        }
                        var school_name       = html_node.find(".add_school_name").val();
                        var school_sub_type   = html_node.find(".add_school_sub_type").val();
                        var school_sub_type2  = html_node.find(".add_school_sub_type2").val();
                        var school_type       = html_node.find(".add_school_type").val();
                        var school_area       = html_node.find(".add_school_area").val();
                        var school_img        = pic_url;  
                        var school_img_min    = pic_url_min;  
                        var school_intro      = html_node.find(".add_school_intro").val();
                        var school_intro2     = html_node.find(".add_school_intro2").val();
                        var school_charac     = html_node.find(".add_school_charac").val();
                        var school_contact    = html_node.find(".add_school_contact").val();
                        var school_address    = html_node.find(".add_school_address").val();
                        var school_web        = html_node.find(".add_school_web").val();
                        var school_recruit    = html_node.find(".add_school_recruit").val();
                        var school_study_cost = html_node.find(".add_school_study_cost").val();
                        var school_live_cost  = html_node.find(".add_school_live_cost").val();
                        var school_live_info  = html_node.find(".add_school_live_info").val();
                        var school_pre        = html_node.find(".add_school_pre").val();

                        $.ajax({
			                type     : "post",
			                url      : "/school_info/add_school_info",
			                dataType : "json",
			                data : {
                                "opt_type"           : opt_type 
                                ,"schoolid"          : schoolid
                                ,"school_name"       : school_name
                                ,"school_sub_type"   : school_sub_type
                                ,"school_sub_type2"  : school_sub_type2
                                ,"school_type"       : school_type
                                ,"school_area"       : school_area
                                ,"school_img"        : school_img
                                ,"school_img_min"    : school_img_min
                                ,"school_intro"      : school_intro
                                ,"school_intro2"     : school_intro2
                                ,"school_charac"     : school_charac
                                ,"school_contact"    : school_contact
                                ,"school_address"    : school_address
                                ,"school_web"        : school_web
                                ,"school_recruit"    : school_recruit
                                ,"school_study_cost" : school_study_cost
                                ,"school_live_cost"  : school_live_cost
                                ,"school_live_info"  : school_live_info
                                ,"school_pre"        : school_pre
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

    $(".add_school_info").on("click",function(){
        do_add_or_update(0,"add");
    });


    $(".opt-update-school_info").on("click",function(){
        var schoolid=$(this).get_opt_data( "schoolid" );
        $.ajax({
            type     :"post",
			url      :"/school_info/get_show_school_info",
			dataType :"json",
			data     :{"schoolid":schoolid},
            success: function(result){
                do_add_or_update(schoolid,"update", result.ret_info );
            }
        });
    });

    $(".opt-browse-school_info").on("click",function(){
        var schoolid=$(this).get_opt_data( "schoolid" );
        $.ajax({
            type     :"post",
			url      :"/school_info/get_show_school_info",
			dataType :"json",
			data     :{"schoolid":schoolid},
            success: function(result){
                var html_txt=dlg_get_html_by_class('dlg_browse_school_info');
                var html_node=$("<div></div>").html(html_txt);
                html_node.find(".show_schoolid").val(result.ret_info.schoolid);
                html_node.find(".show_school_name").val(result.ret_info.school_name);
                html_node.find(".show_school_intro").html(result.ret_info.school_intro);
                html_node.find(".show_school_charac").html(result.ret_info.school_charac);
                html_node.find(".show_school_study_cost").html(result.ret_info.school_study_cost);
                html_node.find(".show_school_live_cost").html(result.ret_info.school_live_cost);
                html_node.find(".show_school_live_info").html(result.ret_info.school_live_info);
                html_node.find(".show_school_contact").html(result.ret_info.school_contact);
                html_node.find(".show_school_address").html(result.ret_info.school_address);
                html_node.find(".show_school_web").html(result.ret_info.school_web);
                html_node.find(".show_school_recruit").html(result.ret_info.school_recruit);
                html_node.find(".show_school_pre").html(result.ret_info.school_pre);
                BootstrapDialog.show({
                    title: '学校信息',
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
    
    $(".opt-del-school_info").on("click",function(){
        var schoolid = $(this).parents("td").siblings(".schoolid").text();
        BootstrapDialog.show({
            title: '删除信息',
            message : "确定删除信息："+ schoolid,
            closable: true, 
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
		            $.ajax({
			            type     :"post",
			            url      :"/school_info/del_school_info",
			            dataType :"json",
			            data     :{"schoolid":schoolid},
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
