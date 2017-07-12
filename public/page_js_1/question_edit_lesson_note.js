// SWITCH-TO:   ../../template/question/
$(function(){
    
    Enum_map.append_option_list("grade_part", $("#id_grade"),true);
    Enum_map.append_option_list("subject", $("#id_subject"),true);
    
    $("#id_grade").val(g_args.grade);
    $("#id_subject").val(g_args.subject);
    $(".form-control").on("change",function(){
        load_note_list();
    });
    var mathjax_editor= $("#id_edit_content").admin_mathjax_editor({
        "qiniu_upload_domain_url":g_args.qiniu_upload_domain_url
    });
    mathjax_editor.type("text");
    
    var cur_edit_note_id   = -1;
    var current_page_num   = 1;
    var cur_edit_note_name = "";
    var edit_note_id=function( note_id,note_name ){
        do_ajax("/question/get_lesson_note_info",{
            "note_id":note_id
        },function(result){
            if (result.ret==0) {
                $("#id_note_title").val(result.note_title);
                mathjax_editor.val(result.note_info);
                cur_edit_note_id=note_id;
                cur_edit_note_name=note_name;
            }else{
                cur_edit_note_id=note_id;
            }
        });
    };

    //adcc 单独的课程列表
    var lesson_note_list = function(note_id,page_num,url){
        var data = null;
        if (!note_id) {
            return;
        }
        if(!url){
            url="/question/get_lesson_note_list";
            data = {
                    "note_id"  : note_id,
                    "page_num" : page_num 
            };
        }
        var html_str = 
                "<thead>"+
                "<tr>"+
                "<td style=\"display:none;\">序号</td>"+
                "<td style=\"width:50px;display:none;\">科目</td>"+
                "<td style=\"width:120px;\">标题</td>"+
                "<td style=\"width:120px;\">知识点</td>"+
                "<td style=\"width:100px;\" class=\" remove-for-xs\" >操作</td>"+
                "</tr>"+
                "</thead>"+
                "<tbody id=\"id_table_body\">";
        
        do_ajax(url,data,function(result){
            //lesson_note_list(result,note_id);
            
            var tongji_info= result.tongji_info;
            $("#id_question_all").text(tongji_info.add_count*1);
            $("#id_post").text(tongji_info.post*1);
            $("#id_pass").text(tongji_info.pass*1);
            $("#id_pass_1").text(tongji_info.nopass_1*1);
            $("#id_pass_5").text(tongji_info.nopass_5*1);
            $("#id_pass_10").text(tongji_info.nopass_10*1);
            $("#id_pass_del").text(tongji_info.nopass_del*1);

            var id_page_info          = $("#id_page_info");
            var lesson_note_list_info = result.data.list;
            var lesson_note_list_page = result.data.page_info;
            $.each(lesson_note_list_info,function(i,item){
                html_str+=
				    "<tr class=\"opt-edit-note_list\">"+
                    "<td style=\"display:none;\">"+item.note_id+"</td>"+
                    "<td style=\"display:none;\">"+item.note_sub_id+"</td>"+
                    "<td class=\"opt-note_title\">"+item.note_title+"</td>"+
                    "<td class=\"opt-note_info\">"+item.note_info+"</td>"+
                    "<td class=\"remove-for-xs\">"+
                    "<div class=\"opt-operate\" data-id=\""+item.id+"\">"+
                    "<a href=\"javascript:;\" class=\"btn fa fa-list-alt opt-show\" title=\"查看\"> </a>"+
                    "<a href=\"javascript:;\" class=\"btn fa fa-trash-o opt-del\" title=\"删除\"> </a>"+
                    "</div>"+
                    "</td>"+
				    "</tr>";
            });
            
            html_str+="</tbody>";
            $("#id_lesson_note_list").html(html_str);
            //删除功能
            $(".opt-del").on("click",function(){
                var id = $(this).parent().data("id");
                del_note_list(id,note_id);
                return false;
            });

            //获取选取的id的内容
            $(".opt-edit-note_list").on("click",function(){
                var id = $(this).find(".opt-operate").data("id");
                $(this).parent().find("tr").removeClass("warning");
                $(this).addClass("warning");
                load_note_info(id);
            });

            //查看选取ID的信息
            $(".opt-show").on("click",function(){
                var id = $(this).parent().data("id");
                load_note_info(id,'show');
            });
            
            //获取页码
            current_page_num=lesson_note_list_page.current_page;
            html_str=get_page_node(lesson_note_list_page,function(url){
                lesson_note_list(note_id,0,url);
            });
            id_page_info.html(html_str);
        });
    };

    var del_note_list=function(id,note_id){
        do_ajax("/question/del_note_list",
                {
                    "id":id,
                    "note_id":note_id
                },function(result){
                    //lesson_note_list(result,note_id);
                    lesson_note_list(note_id);
                });
    };
    
    var load_note_info=function(id,type){
        do_ajax("/question/get_lesson_note_info",
                {
                    "id":id
                },
                function(result){
                    if (result.ret==0) {
                        mathjax_editor.val(result.note_info);
                        $("#id_note_title").val(result.note_title);
                    }else{
                        alert(result.info);
                    }
                    if(type=='show'){
                        var note_info=result.note_info;
                        var title = result.note_title;
                        var html_node="<h1>知识点</h1>";
                        html_node+="<p>"+note_info+"</p>";
                        var dlg=BootstrapDialog.show({
                            title:title, 
                            message :  html_node   ,
                            closable: true, 
                            buttons: [{
                                label: '返回',
                                cssClass: 'btn',
                                action: function(dialog) {
                                    dialog.close();
                                }
                            }]
                        });

                    }
                });
    };

    var load_note_list=function(){
		do_ajax("/question/get_note_list",
                {
                    "grade"   : $("#id_grade").val(),
                    "subject" : $("#id_subject").val(),
                    "audit"   : $("#id_audit").val()
                },
                function(result){
                    var main_list = result.data;
                    var count     = result.count;
                    $(".note_count").html(count);
                    
                    var note_html_node=$('            <div  id="id_sidebar_menu" >'+
                                         '                        <section  class="sidebar" >'+
                                         '                            <ul class="sidebar-menu">'+
                                         '                            </ul>'+
                                         '                        </section>'+
                                         '            </div>');
                    var main_node_html="";

                    $.each(main_list,function(i,item){
                        var main_name= item.name;
                        main_node_html+="<li class=\"treeview\">"
                            +"<a href=\"#\">"
                            +"<i class=\"fa fa-bar-chart-o\"></i>"
                            +"<span>  "+main_name +"</span>"
                            +"<i class=\"fa fa-angle-left pull-right\"></i>"
                            +"</a>"
                            +"<ul class=\"treeview-menu\">";
                        $.each( item.list ,function (i_note, item_note ){

                            main_node_html+="<li class=\"treeview\">"
                                +"<a href=\"#\">"
                                +" <i class=\"fa fa-angle-double-right\"></i></i>"
                                +"<span>  "+item_note.name+"</span>"
                                +"<i class=\"fa fa-angle-left pull-right\"></i>"
                                +"</a>"
                                +"<ul class=\"treeview-menu\">";

                            $.each(  item_note.list ,function (i_note_2, item_note_2 ){
                                main_node_html+= "<li><a style=\"cursor: pointer;\"  class=\"opt-note-item\" data-note_id=\""+item_note_2[0] +"\" ><i class=\"fa fa-angle-double-right \"></i><i class=\"fa fa-tag \"></i>"+item_note_2[1]+"</a></li>";
                            });
                            main_node_html +="</ul>" +"</li>";
                            
                        });

                        main_node_html +="</ul>" +"</li>";

                        
                    });
                    note_html_node.find(".sidebar-menu").html(main_node_html);

                    note_html_node.find( ".treeview > a").on("click",function(e) {
                        var btn=$(this);
                        var isActive =btn.data("isActive");
                        e.preventDefault();
                        if (isActive) {
                            btn.data("isActive",false);
                            btn.children(".fa-angle-down").first().removeClass("fa-angle-down").addClass("fa-angle-left");
                            //btn.parent("li").removeClass("active");
                            btn.parent("li").find(">ul ").hide();
                        } else {
                            //Slide down to open menu
                            isActive = true;
                            btn.data("isActive",true);
                            btn.children(".fa-angle-left").first().removeClass("fa-angle-left").addClass("fa-angle-down");
                            //btn.parent("li").addClass("active");
                            btn.parent("li").find(">ul ").show();
                        }
                    });

                    note_html_node.find( ".treeview    > ul  a").on("click",function(e) {
                        note_html_node.find( ".treeview    > ul  a").removeClass( "menu-active-item");
                        var note_id=$(this).data("note_id");
                        var note_name= $(this).text();
                        if (note_id){
                            $(this).addClass("menu-active-item");
                            edit_note_id(note_id);
                        }
                        //adcc
                        lesson_note_list(note_id);
                    });
                    $("#id_note_list_div").html(note_html_node);
                });
    };
    
    //adcc
    var do_add_or_update_title_info=function(type){
        var note_title = $("#id_note_title").val();
        var note_id    = cur_edit_note_id;
        var sub_id     = $("#id_subject").val();
        var id         = $("#id_table_body").find(".warning").find(".opt-operate").data("id");

        if (cur_edit_note_id != -1 ) {
		    do_ajax("/question/set_lesson_note_info",
                    {
                        "id"          : id,
                        "type"        : type,
                        "note_id"     : note_id,
                        "note_info"   : mathjax_editor.val(),
                        "note_title"  : note_title,
                        "note_sub_id" : sub_id
                    },
                    function(result){
                        if (result.ret==0){
                            alert("更新成功");
                        }else{
                            alert(result.info);
                        }
                        lesson_note_list(note_id);
                    });
        }
    };

    $("#id_update_title_info").on("click",function(){
        var type='update';
        do_add_or_update_title_info(type);
        
    });
    
    $("#id_add_title_info").on("click",function(){
        var type='add';
        do_add_or_update_title_info(type);
    });
    
    //加载数据
    load_note_list();
});
