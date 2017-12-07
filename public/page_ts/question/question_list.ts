/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question-question_list.d.ts" />


$(function(){
    Enum_map.append_option_list("question_difficulty", $(".question_difficult"),true);
    Enum_map.append_option_list("subject", $("#id_subject"));

    //进入知识点列表页面
    $('#knowledge_list').on('click',function(){
        window.open('/question/knowledge_list');
    });

    //添加题目
    $('#id_add_question').on('click',function(){
        var id_title = $("<input style='width:100%'/>");
        var id_subject = $("<select/>");
        var id_score = $("<input />");
        var id_detail = $("<textarea style='width:100%;height:300px'></textarea>");

        Enum_map.append_option_list("subject",id_subject,true,[1,2,3,4,5,6,7,8,9,10,11]);

        var arr=[
            ["题目标题", id_title ],
            ["题目所属科目", id_subject ],
            ["题目分值", id_score ],
            ["题目详情", id_detail ],
        ];

        $.show_key_value_table("添加题目", arr ,{
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
                    'score':id_score.val(),
                    'detail':id_detail.val(),
                }

                $.ajax({
                    type     :"post",
                    url      :"/question/question_add",
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
        },null,false,800)

    });

    //编辑题目
    $('.opt-set').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var id_title = $("<input style='width:100%'/>");
        var id_subject = $("<select/>");
        var id_score = $("<input />");
        var id_detail = $("<textarea style='width:100%;height:300px'></textarea>");

        Enum_map.append_option_list("subject",id_subject,true,[1,2,3,4,5,6,7,8,9,10,11]);

        id_title.val(opt_data.title);
        id_subject.val(opt_data.subject);
        id_score.val(opt_data.score);
        id_detail.val(opt_data.detail);

        var arr=[
            ["题目标题", id_title ],
            ["题目所属科目", id_subject ],
            ["题目分值", id_score ],
            ["题目详情", id_detail ],
        ];

        $.show_key_value_table("编辑题目", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            width:1000,
            action : function(dialog) {
                var title = id_title.val();
                var subject = id_subject.val();
                if( title == '' || subject ==''){
                    BootstrapDialog.alert('标题或者科目必填');
                    return false;
                }

                var data = {
                    'question_id':opt_data.question_id,
                    'title': title,
                    'subject':subject,
                    'score':id_score.val(),
                    'detail':id_detail.val(),
                }

                $.ajax({
                    type     :"post",
                    url      :"/question/question_edit",
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
        },null,false,800)

    });

    //删除题目
    $('.opt-del').on('click',function(){
        var opt_data = $(this).get_opt_data();

        var question_id = opt_data.question_id;
        var title = "当删除本题时，本题目对应的知识点和解题步骤将全部删除，你确定删除？";
        var data = {
            'question_id':question_id
        };

        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/question/question_dele",data);
            }
        });

    })
    var subject = 0;
    //编辑对应的知识点
    $('.opt-stu-origin').on('click',function(){

        var opt_data=$(this).get_opt_data();
        subject = opt_data.subject;
        var title = opt_data.title;

        var id_knowledge = $("<div id='id_knowledge'></div>");
        var add_knowledge = $('<button title="添加知识点" class="btn btn-primary fa">添加知识点</button>');

        

        add_knowledge.on("click", choose_knowledge);
        id_knowledge.append(add_knowledge);

        var arr=[
            ["题目标题", title ],
            ["知识点", id_knowledge ],
        ];

        $.show_key_value_table("编辑知识点", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var strArr = {};
                $('#id_knowledge .each_knowledge').each(function(){
                    var know_id = $(this).find('input:hidden').val();
                    var difficult = $(this).find('select').val();
                    strArr[know_id] = difficult;
                });
                console.log(strArr);
                //strArr = JSON.stringify(strArr);
                var data = {
                    'question_id': opt_data.question_id,
                    'strArr':strArr,
                }

                $.ajax({
                    type     :"post",
                    url      :"/question/question_know_add",
                    dataType :"json",
                    data     :data,
                    success : function(result){
                        BootstrapDialog.alert(result['msg']);
                        if( result['status'] == 200){
                            window.location.reload();
                        }
                    }
                });
            }
        },null,false,800)

    });

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

    //获取知识点详情
    $('.get_knowledge_detail').click(function(){
        var detail = $(this).parent().find('.question_knowledge_detail').text();
        var arr=[
            ["知识点详情", detail ],
        ];
        $.show_key_value_table("知识点详情", arr,null);
    })

    //删除题目对应的知识点
    $('.del_knowledge').click(function(){
        var id = $(this).parent().find('input:hidden').val();
        var title = "确定删除?";
        var data = {
            'id':id
        };
        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/question/question_know_dele",data);
            }
        });

    })

    //编辑对应的答案
    $('.fa-comments').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var question_id = opt_data.question_id;
        window.open('/question/answer_list?question_id='+question_id);
    })
 
})


function remove_knowledge(id){
    var obj = $("#knowledge_"+id);
    obj.remove();
}
