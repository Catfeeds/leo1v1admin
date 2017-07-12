//SWITCH-TO: ../../template/question/
$(function(){
    Enum_map.append_option_list("grade_part", $("#id_grade"));
    Enum_map.append_option_list("subject", $("#id_subject"));
    
    Enum_map.td_show_desc("question_check_flag", $(".opt-td-check_flag")  );
    $("#id_grade").val(g_args.grade);
    if (g_args.question_id != -1) {
        $("#id_question_id").val(g_args.question_id);
    }
    $("#id_opt_type").val(g_args.opt_type);
	$("#id_subject").val(g_args.subject);
    $('#id_start_date').val(g_args.start_time);
    $('#id_end_date').val(g_args.end_time);
    $("#id_note_list_info").data("note_id_list",g_args.note_id);
    $("#id_note_list_info").text(get_note_name_list(g_args.note_id));
    
    set_input_enter_event($("#id_question_id"), function(){
        reload_page(1);
    });

    var reload_page=function( page_num){
        if (!page_num){
            page_num=1;
        }
        var note_id = -1;
        note_id = $("#id_note_list_info").data("note_id_list");
        var question_id=$("#id_question_id").val();
        if (!question_id) {
            question_id=-1;
            
        }
        //reload
	    var url = window.location.pathname +"?" + 
                "grade="+$("#id_grade").val()+
                "&subject="+$("#id_subject").val()+
                "&start_time="+$("#id_start_date").val()+
                "&end_time="+$("#id_end_date").val()+
                "&opt_type="+$("#id_opt_type").val()+
                "&question_id="+question_id+
                "&note_id="+note_id+
                "&page_num="+page_num;
	    window.location.href=url;
    };
    
    MathJax.Hub.Config({
        showProcessingMessages: false,
        //tex2jax: { inlineMath: [['$','$'],['\\(','\\)']] }
        tex2jax: { inlineMath: [['$','$']] }
    });

    $(".c_sel").on("change",function(){
        reload_page(1);
    });

    $.each($('.opt-td-note'),function(){
        var note_name_list_str = get_note_name_list(""+ $(this).data("note_id_list"));
        $(this).text(note_name_list_str );
    });

    $(".opt-show").on("click",function(){
        var questionid=$(this).parent().data("questionid");
        admin_show_question(questionid);
    });
    
    $("#id_question_publish").on("click",function(){
        var start_time = $("#id_start_date").val();
        var end_time   = $("#id_end_date").val();
        var grade      = $("#id_grade").val();
        var subject    = $("#id_subject").val();
        $.ajax({
            url: '/question/question_publish',
            type: 'POST',
            data: {
                'start_time' : start_time,
                'end_time'   : end_time,
                'grade'      : grade,
                'subject'    : subject
			},
            dataType: 'json',
            success: function(data) {
                BootstrapDialog.alert(data['info']);
                //window.location.reload();
            }
        });
    });

    
	//时间控件
	$('#id_start_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
	    onChangeDateTime :function(){
            reload_page(1);
        }
	});
	$('#id_end_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		onChangeDateTime :function(){
            reload_page(1);
        }
	});
	//时间控件-over

    $("#id_note_info").on("click",function(){
		$.ajax({
			type     : "post",
			url      : "/question/get_note_list",
			dataType : "json",
			data     : {
                "grade"   : $("#id_grade").val(),
                "subject" : $("#id_subject").val()
            },
			success :  show_note_dlg_info
		});
    });
    
    var show_note_dlg_info = function( result){
        var col       = 4 ;
        var main_list = result.data;
        var all_count = main_list.length;
        var per_count = Math.ceil(all_count/col);
        var cur_page  = 0;
        
        var note_html_node=$(
            '            <div  id="id_sidebar_menu" >'+
                '                <div class="row">'+
                '                    <div class="col-xs-6 col-md-3">'+
                '                        <section  class="sidebar" >'+
                '                            <ul class="sidebar-menu">'+
                '                            </ul>'+
                '                        </section>'+
                ''+
                ''+
                '                    </div>'+
                '                    <div class="col-xs-6 col-md-3">'+
                '                        <section  class="sidebar" >'+
                '                            <ul class="sidebar-menu">'+
                '                            </ul>'+
                '                        </section>'+
                ''+
                ''+
                '                    </div>'+
                '                    <div class="col-xs-6 col-md-3">'+
                '                        <section  class="sidebar" >'+
                '                            <ul class="sidebar-menu">'+
                '                            </ul>'+
                '                        </section>'+
                ''+
                ''+
                ''+
                '                    </div>'+
                '                    <div class="col-xs-6 col-md-3" >'+
                '                        <section  class="sidebar" >'+
                '                            <ul class="sidebar-menu">'+
                '                            </ul>'+
                '                        </section>'+
                '                    </div>'+
                '                </div>'+
                ''+
                '                <div class="row "  >'+
                '                    <div class="col-xs-12 col-md-12 selected-list" style="text-align:center;" >'+
                ''+
                '                    </div>'+
                '                </div>'+
                '            </div>');
        var col_list=note_html_node.find( ".sidebar-menu" );
        var selected_list_node =note_html_node.find(".selected-list" );

        $.each(main_list,function(i,item){
            cur_page = Math.floor(i/per_count);
            
            var main_name= item.name;
            var main_node_html="<li class=\"treeview\">"
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
            $(col_list[cur_page]).append(main_node_html);
        });

        var add_note=function( note_id,note_name ){
            var btn=$("<button  data-note_id=\""+note_id+"\"   style=\"margin-left:10px;\" class=\"btn btn-warning note_"+note_id+"\">"+note_name+"<i class=\"fa   fa-times \"></i></button>");
            btn.on("click", function(){
                $(this).remove();
            });
            selected_list_node.append( btn);
        };

        var note_id_arr=$("#id_note_list_info").data("note_id_list").split(",");
        $.each(note_id_arr,function(i,item){
            var note_id=$.trim(item);
            if (note_id!=""){
                add_note ( note_id ,g_note_name_map[note_id] );
            }
        });

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
            var note_id=$(this).data("note_id" );
            var note_name= $(this).text();
            if (note_id){
                if(selected_list_node.find( ".note_"+note_id ).length ==0 ){
                    add_note ( note_id,note_name );
                };
            }
        });

        var dlg=BootstrapDialog.show({
            title: '知识点筛选',
            message :  note_html_node,
            closable: true, 
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var note_id_list_str=",";
                    var note_name_list_str="";
                    $.each(selected_list_node.find("button"), function(i,item){
                        note_id_list_str+=$.trim($(item).data("note_id"))+",";
                    });
                    $("#id_note_list_info").data("note_id_list",note_id_list_str);
                    $("#id_note_list_info").text(note_id_list_str);
                    
                    show_note_name_list_info(note_html_node);
                    reload_page(1);
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
        dlg.getModalDialog().css("width","1100px");
        dlg.getModalDialog().css("margin-top","10px");
        dlg.getModalDialog().find(".modal-header").hide();
        dlg.getModalDialog().find(".modal-body").css("padding","8px");
        dlg.getModalDialog().find(".modal-footer").css("padding","8px");
        dlg.getModalDialog().find(".modal-footer").css("margin-top","0px");
        dlg.getModalDialog().find(".btn").css("margin-right","30px");
    };

    var get_note_name_list_info=function(note_id_arr_str ){
        if (!note_id_arr_str ){
            note_id_arr_str ="";
        }
        var note_id_arr= note_id_arr_str.split(",");
        var note_name_list_str= "";
        $.each(note_id_arr,function(i,item){
            var note_id=$.trim(item);
            if (note_id!=""){
                note_name_list_str+= g_note_name_map[note_id]+"," ;
            }
        });
        return note_name_list_str;
    };

    var show_note_name_list_info = function(){
        var note_name_list_str = get_note_name_list_info($("#id_note_list_info").data("note_id_list"));
        $("#id_note_list_info").text(note_name_list_str);
    };
    /*  */
});
