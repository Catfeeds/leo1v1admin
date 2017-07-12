$(function(){
    Enum_map.append_option_list("grade_part", $("#id_grade"),true);
    Enum_map.append_option_list("subject", $("#id_subject"),true);
    
    $("#id_grade").val(g_args.grade);
    $("#id_subject").val(g_args.subject);

    $("#id_grade, #id_subject").on("change",function(){
        load_note_list();
    });
    
    var id_all='';
    var lessonid=g_args.lessonid;

    //获取知识点下的问题列表
    var get_homework_list=function(noteid){
        $("#id_question_list").empty();
        do_ajax("/question/get_homework_list",
                {
                    "noteid":noteid
                },function(result){
                    var html_str = "";
                    $.each(result.data,function(i,item){
                        html_str+="<div class=\"opt-checkbox-power\""
                            +"style=\"font-size:16px;\""
                            +" data-questionid=\""+item.id+"\"><b>"
                            +(i+1)+"</b>.&nbsp;&nbsp;&nbsp;"+item.q+"&nbsp;("+item.difficulty_str+")"
                            +"<br/>"
                            +item.a
                            +"</div>"
                            +"<hr/>";
                    });
                    $("#id_question_list").append(html_str);
                    get_homework_lesson(lessonid,'click');
                });
    };

    //获取课堂的作业列表
    var get_homework_lesson = function(lessonid,type){
        do_ajax("/tea_manage/get_homework_lesson",
                {
                    "lessonid":lessonid
                },function(result){
                    if(type=='click'){
                        id_all     = cookie_homework('get');
                        var id_arr = id_all.split(",");
                        $.each(id_arr,function(i,item){
                            $("#id_question_list").find(".opt-checkbox-power").each(function(){
                                var qid = $(this).data("questionid");
                                if(item == qid){
                                    $(this).addClass("danger");
                                }
                            });
                        });
                    }else if(type == 'load'){
                        var list          = result.data;
                        var homework_list = list.split(",");
                        id_all = list;
                        cookie_homework('set',id_all);
                    }
                });
    };

    var load_note_list = function(){
		do_ajax("/question/get_note_list",
                {
                    "grade"   : $("#id_grade").val(),
                    "subject" : $("#id_subject").val()
                },
                function(result){
                    var main_list      = result.data;
                    var note_html_node = $('            <div  id="id_sidebar_menu" >'+
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
                            +"<span>"+main_name+"</span>"
                            +"<i class=\"fa fa-angle-left pull-right\"></i>"
                            +"</a>"
                            +"<ul class=\"treeview-menu\">";
                        $.each( item.list ,function (i_note, item_note ){
                            main_node_html+="<li class=\"treeview\">"
                                +"<a href=\"#\">"
                                +" <i class=\"fa fa-angle-double-right\"></i></i>"
                                +"<span>"+item_note.name+"</span>"
                                +"<i class=\"fa fa-angle-left pull-right\"></i>"
                                +"</a>"
                                +"<ul class=\"treeview-menu treeview-menu-list\">";
                            $.each(item_note.list,function(i_note_2,item_note_2){
                                main_node_html+= "<li><a style=\"cursor: pointer;\"  class=\"opt-note-item\" data-note_id=\""+item_note_2[0] +"\" ><i class=\"fa fa-tag \"></i>"+item_note_2[1]+"</a></li>";
                            });
                            main_node_html +="</ul></li>";
                        });
                        main_node_html +="</ul></li>";
                    });
                    
                    note_html_node.find(".sidebar-menu").html(main_node_html);
                    note_html_node.find(".treeview > a").on("click",function(e) {
                        var btn=$(this);
                        var isActive =btn.data("isActive");
                        e.preventDefault();
                        if (isActive){
                            btn.data("isActive",false);
                            btn.children(".fa-angle-down").first().removeClass("fa-angle-down").addClass("fa-angle-left");
                            btn.parent("li").find(">ul ").hide();
                        }else{
                            isActive = true;
                            btn.data("isActive",true);
                            btn.children(".fa-angle-left").first().removeClass("fa-angle-left").addClass("fa-angle-down");
                            btn.parent("li").find(">ul").show();
                        }
                    });

                    note_html_node.find(".treeview > ul a").on("click",function(e) {
                        note_html_node.find(".treeview > ul a").removeClass("menu-active-item");
                        var note_id = $(this).data("note_id");
                        if (note_id){
                            $(this).addClass("menu-active-item");
                        }
                        
                        if(note_id){
                            get_homework_list(note_id);
                        }
                    });

                    //知识点中选中的题目列表
                    $("#id_question_list").on("click",".opt-checkbox-power",function(){
                        id_all=cookie_homework('get');
                        var id = $(this).data('questionid')+',';
                        if($(this).hasClass("danger")){
                            $(this).removeClass("danger");
                            id_all=id_all.replace(id,'');
                        }else{
                            $(this).addClass("danger");
                            id_all+=id;
                        }
                        cookie_homework('set',id_all);
                    });
                    
                    get_homework_lesson(lessonid,'load');
                    $("#id_note_list_div").html(note_html_node);
                });
    };

    //新增作业（存储与cookie中）
    $("#id_add_homework_info").on("click",function(){
        add_homework_list();
    });
    
    $("#id_show_homework_info").on("click",function(){
        show_homework_list();
    });
    
    $("body").on("click",".opt-del-question",function(){
        $(this).parents("tr").remove();
        set_new_cookie();
    });
    
    
    $("body").on("click",".opt-up-question",function(){
        up(this);
        set_new_cookie();
    });
    
    $("body").on("click",".opt-down-question",function(){
        down(this);
        set_new_cookie();
    });

    //获取已选作业题目的顺序及条目
    var set_new_cookie=function(){
        var id_change='';
        $(".l_questionid").each(function(){
            id_change += $(this).data("questionid")+",";
        });
        cookie_homework('set',id_change);
    };
    
    //作业cookie
    var cookie_homework = function(type,value){
        var res = true;
        if(type=='set'){
            setCookie('homework',value);
        }else if(type=='get'){
            res= unescape(getCookie('homework'));
        }else if(type=='unset'){
            delCookie('homework');
        }
        return res;
    };

    //新增作业
    var add_homework_list = function(){
        var question_str=cookie_homework('get');
        do_ajax("/teacher_info/set_homework_lesson",
                {
                    "lessonid"     : lessonid,
                    "question_str" : question_str
                },function(result){
                    get_homework_lesson(lessonid,'load');
                });
    };
    
    //查看已选作业
    var show_homework_list = function(){
        var question_str=cookie_homework('get');
        do_ajax("/tea_manage/show_homework_lesson",
                {
                    "question_str" : question_str
                },function(result){
                    var list = result.data;
                    var html_node = "<table  class=\"table table-bordered table-striped question_list\">"
                        +"<tbody>";
                    $.each(list,function(i,item){
                        html_node += "<tr>"
                            +"<td>"+item.q+"</td>"
                            +"<td class=\"remove-for-xs l_questionid\" data-questionid=\""+item.id+"\">"
                            +"<a href=\"javascript:;\" title=\"上移\" class=\"btn fa fa-arrow-up opt-up-question\"></a>"
                            +"<a href=\"javascript:;\" title=\"下移\" class=\"btn fa fa-arrow-down opt-down-question\"></a>"
                            +"<a href=\"javascript:;\" title=\"删除\" class=\"btn fa fa-times opt-del-question\"></a>"
                            +"</td>"
                            +"</tr>";
                    });
                    html_node +="</tbody></table>";
                    BootstrapDialog.show({
                        title: '已选作业列表',
                        message : html_node,
                        closable: true, 
                        closeByBackdrop:false,
                        buttons: [{
                            label: '取消',
                            cssClass: 'btn',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }]
                    });
                });
    };

    $("#del_cookie").on("click",function(){
        cookie_homework('set','');
    });
    //加载数据
    load_note_list();
});
