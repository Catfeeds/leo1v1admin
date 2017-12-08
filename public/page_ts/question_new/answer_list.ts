/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question-knowledge_list.d.ts" />

$(function(){
    Enum_map.append_option_list("subject", $("#id_subject"));

    //进入知识点列表页面
    $('#question_list').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open('/question_new/question_list');
    });

    //添加答案
    $('#id_add_answer').on('click',function(){
        //默认步骤
        var default_step = $('#next_step').val();
        var id_step = $("<input/>只能填数字");
        id_step.val(default_step);

        var id_difficult = $("<select/>");
        Enum_map.append_option_list("question_difficulty",id_difficult,true);

        var id_knowledge = $("<input id='knowledge_title'/><input id='knowledge_id' type='hidden'/>");
        id_knowledge.on("click", choose_knowledge);

        var id_score = $("<input />");

        var id_detail = $("<textarea style='width:100%;height:240px'></textarea>");

        var question_id = $('#question_id').val();

        var arr=[
            ["解题步骤", id_step ],
            ["步骤难度", id_difficult ],
            ["步骤分值", id_score ],
            ["涉及知识点", id_knowledge ],
            ["具体过程", id_detail ],
        ];

        $.show_key_value_table("添加知识点", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var step = id_step.val();
                var difficult = id_difficult.val();
                var knowledge_id = $('#knowledge_id').val();
                var detail = id_detail.val();
                var score = id_score.val();

                if( step == '' || difficult ==''){
                    BootstrapDialog.alert('解题步骤和具体过程必填');
                    return false;
                }
 
                var data = {
                    'question_id':question_id,
                    'step': step,
                    'difficult':difficult,
                    'knowledge_id':knowledge_id,
                    'detail':detail,
                    'score':score
                }
              
                $.ajax({
                    type     :"post",
                    url      :"/question_new/answer_add",
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

    //编辑答案
    $('.opt-set').on('click',function(){
        var opt_data = $(this).get_opt_data();

        //默认步骤
        var id_step = $("<input/>只能填数字");
        id_step.val(opt_data['step']);

        var id_difficult = $("<select/>");
        Enum_map.append_option_list("question_difficulty",id_difficult,true);
        id_difficult.val(opt_data['difficult']);

        var knowledge_title = opt_data['title'];
        var knowledge_id = opt_data['knowledge_id'];
        if( knowledge_id == 0){
            knowledge_id = '';
            knowledge_title = '';
        }
        var id_knowledge = $("<input id='knowledge_title' value='"+knowledge_title+"'/><input id='knowledge_id' value='"+knowledge_id+"' type='hidden'/>");
        id_knowledge.on("click", choose_knowledge);
      
        var id_detail = $("<textarea style='width:100%;height:240px'></textarea>");
        id_detail.val(opt_data['detail']);

        var question_id = $('#question_id').val();

        var id_score = $("<input />");
        id_score.val(opt_data['score']);

        var arr=[
            ["解题步骤", id_step ],
            ["步骤难度", id_difficult ],
            ["步骤分值", id_score ],
            ["涉及知识点", id_knowledge ],
            ["具体过程", id_detail ],
        ];


        $.show_key_value_table("编辑答案", arr,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var step = id_step.val();
                var difficult = id_difficult.val();
                var knowledge_id = $('#knowledge_id').val();
                var detail = id_detail.val();
                var score = id_score.val();

                if( step == '' || difficult ==''){
                    BootstrapDialog.alert('解题步骤和具体过程必填');
                    return false;
                }
                
                var data = {
                    'answer_id':opt_data['answer_id'],
                    'question_id':question_id,
                    'step': step,
                    'difficult':difficult,
                    'knowledge_id':knowledge_id,
                    'detail':detail,
                    'score':score
                }
                
                $.ajax({
                    type     :"post",
                    url      :"/question_new/answer_edit",
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

     var choose_knowledge = function(){
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/question_new/question_know_get",
            //其他参数
            "args_ex" : {
                subject  :  $('#subject').val()
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
                var question_id = $('.bootstrap-dialog-body').find('tr.warning td:eq(0)').text();
                var title = $('.bootstrap-dialog-body').find('tr.warning td:eq(1)').text();
                $('#knowledge_id').val(question_id);
                $('#knowledge_title').val(title);
            },
            //加载数据后，其它的设置
            "onLoadData"       : function(id,data){

            }
        });
    };

    //删除答案
    $('.opt-del').on('click',function(){
        var opt_data = $(this).get_opt_data();

        var answer_id = opt_data['answer_id'];
        var title = "你确定删除本知识点,标题为" + opt_data.title + "？";
        var data = {
            'answer_id':answer_id
        };
        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/question_new/answer_dele",data);
            }
        });

    })

})
