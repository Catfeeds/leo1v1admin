// SWITCH-TO:   ../../template/question/
$(function(){
    var current_page_num=1;
    $("#datetime_start").val(g_args.start_time );
    $("#datetime_end").val(g_args.end_time );
    
    
	$('#datetime_start,#datetime_end').datetimepicker({
		lang:'ch',
		timepicker:false,
		onChangeDateTime :function(){
            reload_page(1);
		},
		format:'Y-m-d'
	});

    /* adcc */
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
    
    var reload_data=function( grade,subject,check_userid, start_time,end_time ,note_id, page_num,url){
        var data = null;
        if (!url){
			url  = "/question/question_list_check2_for_js";
            data = {
                "grade"         : grade
                ,"subject"      : subject 
                ,"check_userid"      : check_userid
                ,"start_time"   : start_time 
                ,"end_time"     : end_time 
                ,"note_id"      : note_id 
                ,"page_num"     : page_num
                ,"check_userid2" : g_args.check_userid2 
                ,"opt_type"     : g_args.opt_type 
            };
        }

		do_ajax(url, data, function(result){
            var ret_list      = result.data.list;
            var ret_page_info = result.data.page_info;
            var html_str      = "";
            var id_page_info  = $("#id_page_info");
            var id_body       = $("#id_table_body");

            $.each( ret_list, function(i,item){
                html_str+=' <tr  class="opt-edit-record">	' +
                    '                    <td style="display:none;" ></td>'+
                    '                    <td style="display:none;" >'+item.id+'</td>'+
                    '                    <td  style="display:none;" >'+item.account+'</td>'+
                    '                    <td style="display:none;" >'+item.add_time+'</td>'+
                    '                    <td  >'+item.check_user+'</td>'+
                    '                    <td style="display:none;" >'+item.check_time+'</td>'+
                    '                    <td >'+item.check2_user+'</td>'+
                    '                    <td style="display:none;" >'+item.check2_time+'</td>'+

                    '                    <td >'+item.grade_str+'</td>'+
                    '                    <td >'+item.subject_str+'</td>'+
                    '                    <td class="opt-td-note" data-note_id_list="'+item.note+'" ></td>'+
                    '                    <td >'+item.question_type_str+'</td>'+
                    '                    <td >'+item.difficulty_str+'</td>'+
                    '                    <td class="opt-q" >'+     item.q+'</td>'+
                    '                    <td >'+ item.a+'</td>'+
                    '                    <td >'+ Enum_map.get_desc("question_check_flag", item.check_flag  )   +'</td>'+
                    '                    <td >'+ Enum_map.get_desc("question_check_flag", item.check2_flag  )   +'</td>'+
                    '                    <td class=" remove-for-xs  ">'+
                    '                        <div class="btn-group   " data-questionid="'+item.id+'"  >'+
                    '                            <a href="javascript:;" class="btn  fa fa-info td-info "></a>'+
                    '                            <a href="javascript:;" class="btn fa fa-list-alt opt-show " title="详情"> </a>'+
                    '                        </div>'+
                    '                    </td>'+
                    '                    </tr>';


            });

            id_body.html(html_str);

            bind_td_info();
            
            var html_node=get_page_node(ret_page_info  ,function( url ){
                reload_data(0,0,0,1,"","","",url);
            });
            current_page_num=ret_page_info.current_page;
            id_page_info.html(html_node);

            $($(".opt-edit-record")[0]).click();

            $.each ( $('.opt-td-note'),function(){
                var note_name_list_str = get_note_name_list(""+ $(this).data("note_id_list"));
                $(this).text(note_name_list_str );
            });
		});
    };
    
    var auto_reformat_flag=false;
    var path_name=window.location.pathname;
    if (  path_name == "/question/question_list_check2"  ) {
        auto_reformat_flag=true;
    }

    var question_editor=$("#id_question_editor" ).admin_question_editor({
        "qiniu_upload_domain_url":g_args.qiniu_upload_domain_url,
        "can_add_question_flag":false,
        "auto_reformat_flag": auto_reformat_flag,
        "check_type"  :  "check2",
        "onSave":function(opt_type) {
            if (opt_type=="add" ){
            }else{
                reload_data(g_args.grade, g_args.subject, g_args.check_userid, g_args.start_time, g_args.end_time ,g_args.note_id,   current_page_num );
            }
        }
    });

    var reload_page=function( page_num){
        if (!page_num){
            page_num=1;
        }
        var note_id = -1;
        note_id = $("#id_note_list_info").data("note_id_list");
        
        //reload
	    var url = window.location.pathname + "?" + 
                "grade="+$("#id_grade").val()+
                "&subject="+$("#id_subject").val()+
                "&check_userid="+$("#id_check_userid").val()+
                "&start_time="+$("#datetime_start").val()+
                "&end_time="+$("#datetime_end").val()+
                "&note_id="+note_id+
                "&check_userid2="+g_args.check_userid2+
                "&page_num="+page_num;
	    window.location.href=url;
    };
    
    Enum_map.append_option_list("grade_part", $("#id_grade"));
    Enum_map.append_option_list("subject", $("#id_subject"));

	//init input data
	$("#id_grade").val(g_args.grade);
	$("#id_subject").val(g_args.subject);
	$("#id_check_userid").val(g_args.check_userid);
    $("#id_note_list_info").data("note_id_list",g_args.note_id);
    $("#id_note_list_info").text(get_note_name_list(g_args.note_id));
    
    $(".c_sel"  ).on("change",function(){
        reload_page(1);
    });
    
    $("#id_table_body").on("click", ".opt-show",function(){
        var questionid=$(this).parent().data("questionid");
        admin_show_question_diff (questionid,2);
    });

    $( "#id_table_body").on("click", ".opt-edit-record",function(){
        var questionid=$(this).find(".btn-group").data("questionid");
        question_editor.edit_question(questionid);
        $(this).parent().find("tr").removeClass("warning");
        $(this).addClass("warning");
    });

    //选中第一条
    reload_data(g_args.grade, g_args.subject, g_args.check_userid, g_args.start_time, g_args.end_time ,g_args.note_id, g_args.page_num );

    //ipad 审核通知重新加载
    var noti_url=window.location.hostname;
    if (  noti_url=="question.yb1v1.com") {
         noti_url="admin.yb1v1.com";
    }

    var ws = $.websocket("ws://" +noti_url+":9501/", {
        "open" :function(){
            ws.send( "reload_question_page_bind" , {
                "username" :g_account
            });
        },
        events: {
            "check_question": function(data) {
                var questionid=data.questionid;
                var check_flag=data.check_flag;
                if (check_flag>=2 && check_flag<=5  ) {
                    question_editor.opt_question(check_flag,questionid);
                }else{
                    alert("check_flag err:"+ check_flag);
                }
            }
        }
    });

    
});
