// SWITCH-TO:   ../../template/question/
$(function(){

    var current_page_num=1;
    BootstrapDialog.configDefaultOptions({ animate: false }); 
    
    var check_opt_type_post = function ()  {
        return  g_args.opt_type=="post";
    };
    if (  check_opt_type_post()  ) {
        var question_editor=$("#id_question_editor" ).admin_question_editor({
            "qiniu_upload_domain_url":g_args.qiniu_upload_domain_url,
            "onSave":function(opt_type){
                if (opt_type=="add" ){
                    reload_data(1);
                }else{
                    reload_data(current_page_num);
                }
                question_editor.set_to_add();
            }
        });
	    //init  input data
        question_editor.initData(g_args);
    

    }else{
        MathJax.Hub.Config({
            showProcessingMessages: false,
            //tex2jax: { inlineMath: [['$','$'],['\\(','\\)']] }
            tex2jax: { inlineMath: [['$','$']] }
        });
    }


    
    //========================================================






    $('#id_table_body').on("click", ".opt-del",function(){
        var questionid=$(this).parent().data("questionid");
        var question_info=$(this).closest("tr").find(".opt-q").text();
        
        BootstrapDialog.show({
            title: '删除题目',
            message : "删除题目:"+ question_info,
            closable: true, 
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog) {
		            $.ajax({
			            type     :"post",
			            url      :"/question/del",
			            dataType :"json",
			            data     :{
                            "id":questionid
                        },
			            success  : function(result){
                            reload_data( $.query.get("page_num"));
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
        return false;
    });


    $( "#id_table_body").on("click", ".opt-show", function(){
        var questionid=$(this).parent().data("questionid");
	    //
        admin_show_question(questionid);
    });

    $("#id_table_body").on("click", ".opt-edit-record", function(){
        var questionid=$(this).find(".btn-group").data("questionid");
        if ( check_opt_type_post() ) {
            question_editor.edit_question(questionid);
        }else{
            admin_show_question_diff(questionid);
        }
        $(this).parent().find("tr").removeClass("warning");
        $(this).addClass("warning");
    });

    var reload_data=function(page_num,url){
        var data=null;

        if (!url){
			url     = "/question/get_question_list_for_js";
            data = {
                "opt_type" :  g_args.opt_type
                ,"page_num"   : page_num
            };
        }

		do_ajax(url, data, function(result){
            var ret_list      = result.data.list;
            var ret_page_info = result.data.page_info;
            var html_str="";
            
            var id_page_info = $("#id_page_info");
            var id_body= $("#id_table_body");

            $.each( ret_list, function(i,item){
                html_str+=' <tr  class="opt-edit-record">	' +
                    '                    <td  style="display:none;"  ></td>'+
                    '                    <td style="display:none;">'+item.id+'</td>'+
                    '                    <td >'+item.add_time+'</td>'+
                    '                    <td >'+item.grade_str+'</td>'+
                    '                    <td >'+item.subject_str+'</td>'+
                    '                    <td class="opt-td-note" data-note_id_list="'+item.note+'" ></td>'+
                    '                    <td >'+item.question_type_str+'</td>'+
                    '                    <td >'+item.difficulty_str+'</td>'+
                    '                    <td class="opt-q" >'+ get_real_decodeURIComponent(item.q)+'</td>'+
                    '                    <td >'+get_real_decodeURIComponent( item.a)+'</td>'+
                    '                    <td >'+item.check_flag_str+'</td>'+
                    '                    <td class=" remove-for-xs  ">'+
                    '                        <div class="btn-group   " data-questionid="'+item.id+'"  >'+
                    '                            <a href="javascript:;" class="btn  fa fa-info td-info "></a>'+
                    '                            <a href="javascript:;" class="btn fa fa-list-alt   opt-show" title="查看"> </a>'+
                    '                            <a href="javascript:;" class="btn fa fa-trash-o opt-del" title="删除"> </a>'+

                    '                        </div>'+
                    '                    </td>'+
                    '                    </tr>';


            });

            id_body.html(html_str);
            
            if (!check_opt_type_post()){
                id_body.find(".opt-show").hide();
                id_body.find(".opt-del").hide();
            }

            bind_td_info();
            current_page_num=ret_page_info.current_page; 
            html_str=get_page_node(ret_page_info, function(url){
                reload_data(0,url);
            });
            id_page_info.html(html_str);


            $.each ( $('.opt-td-note' ) ,function(){
                var note_name_list_str = get_note_name_list(""+ $(this).data("note_id_list"));
                $(this).text(note_name_list_str );
            });

		});

    };

    reload_data(1);
});
