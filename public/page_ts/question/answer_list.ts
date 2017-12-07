/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question-knowledge_list.d.ts" />

$(function(){
    Enum_map.append_option_list("subject", $("#id_subject"));

    //进入知识点列表页面
    $('#question_list').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open('/question/question_list');
    });

    //添加答案
    $('#id_add_answer').on('click',function(){
        //默认步骤
        var default_step = $('#next_step').val();
        var id_step = $("<input/>只能填数字");
        var id_difficult = $("<select/>");
        var id_detail = $("<textarea></textarea>");
        id_step.val(default_step);
        Enum_map.append_option_list("question_difficulty",id_difficult,true);

         var arr=[
            ["知识点标题", id_title ],
            ["知识点科目", id_subject ],
            ["知识点详情解读", id_detail ],
        ];

        $.show_key_value_table("添加知识点", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var title = id_title.val();
                var subject = id_subject.val();
                if( title == '' || subject ==''){
                    BootstrapDialog.alert('标题或者科目必填');
                    return false;
                }
 
                var data = {
                    'title': title,
                    'subject':subject,
                    'detail':id_detail.val(),
                }
              
                $.ajax({
                    type     :"post",
                    url      :"/question/knowledge_add",
                    dataType :"json",
                    data     :data,
                    success : function(result){
                        BootstrapDialog.alert(result['msg']);
                        if(result['status'] == 200){
                            window.location.reload();
                        }
                    }
                });
            }
        })

    },null,false,800);

    //编辑答案
    $('.opt-set').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var id_title = $("<input/>");
        var id_subject = $("<select/>");
        var id_detail = $("<textarea></textarea>");

        Enum_map.append_option_list("subject",id_subject,true);
        id_title.val(opt_data.title);
        id_subject.val(opt_data.subject);
        id_detail.val(opt_data.detail);
        var arr=[
            ["知识点标题", id_title ],
            ["知识点科目", id_subject ],
            ["知识点详情解读", id_detail ],
        ];

        $.show_key_value_table("编辑知识点", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var title = id_title.val();
                var subject = id_subject.val();
                if( title == '' || subject ==''){
                    BootstrapDialog.alert('标题或者科目必填');
                    return false;
                }
 
                var data = {
                    'knowledge_id':opt_data.knowledge_id,
                    'title': title,
                    'subject':subject,
                    'detail':id_detail.val(),
                }
          
                $.ajax({
                    type     :"post",
                    url      :"/question/knowledge_edit",
                    dataType :"json",
                    data     :data,
                    success : function(result){
                        BootstrapDialog.alert(result['msg']);
                        if(result['status'] == 200){
                            window.location.reload();
                        }
                    }
                });
            }
        })

    },null,false,800);

     var choose_knowledge = function(){
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/question/question_know_get",
            //"url"      : "/seller_student2/get_all_activity",
            //其他参数
            "args_ex" : {
                subject  :  subject
            },
            select_primary_field   : "id",   //要拿出来的值
            select_display         : "id",
            width:800,
            //字段列表
            'field_list' :[
                {
                title:"id",
                field_name:"id"
            },
                {
                title:"知识点标题",
                width:400,
                render:function(val,item) {
                    return item.title;
                }
            },
                {
                title:"所属科目",
                width:80,
                render:function(val,item) {
                    return item.subject_str;
                }
            }
            ] ,
             //查询列表
            filter_list:[
                [
                {
                    size_class : "col-md-12" ,
                    title      : "标题",
                    'arg_name' : "title",
                    type       : "input"
                }

            ]
            ],
            "auto_close"       : true,
            //选择
            "onChange"         : function(id,data){
                var title = $('.bootstrap-dialog-body').find('tr.warning td:eq(1)').text();
                var html = "<div class='each_knowledge' id='knowledge_"+id+"'>";
                var difficult = $('.difficult_box:hidden').clone().html();

                html += "<input type='text' readonly value='"+title+"'>";
                html += "<input type='hidden' readonly value='"+id+"'>";
                html += difficult;
                html += "<button title='移除知识点' class='btn btn-warning fa remove_knowledge' onclick='remove_knowledge("+id+")'>移除知识点</button>";
                html += "</div>";
                $('#id_knowledge').append(html);
            },
            //加载数据后，其它的设置
            "onLoadData"       : function(id,data){

            }
        });
    };

    //删除答案
    $('.opt-del').on('click',function(){
        var opt_data = $(this).get_opt_data();

        var knowledge_id = opt_data.knowledge_id;
        var title = "你确定删除本知识点,标题为" + opt_data.title + "？";
        var data = {
            'knowledge_id':knowledge_id
        };

        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/question/knowledge_dele",data);
            }
        });

    })

})
