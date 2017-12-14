/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question-question_list.d.ts" />


$(function(){
    Enum_map.append_option_list("question_difficulty", $(".question_difficult"),true);
    Enum_map.append_option_list("subject", $("#id_subject"),true,[1,2,3,4,5,6,7,8,9,10,11]);
    Enum_map.append_option_list("boolean", $("#id_open_flag"),true);
    $("#id_subject").val(g_args.id_subject);
    $("#id_open_flag").val(g_args.id_open_flag);
    $('.opt-change').set_input_change_event(load_data);

    function load_data(){

        var data = {
            id_subject : $("#id_subject").val(),
            id_open_flag : $("#id_open_flag").val(),
        };

        $.reload_self_page(data);
    }


    //进入知识点列表页面
    $('#knowledge_list').on('click',function(){
        window.open('/question_new/knowledge_list');
    });

    //添加题目
    $('#id_add_question').on('click',function(){
        var editType = 1;
        var subject = $('#id_subject').val();
        window.open('/question_new/question_edit?editType='+editType+'&subject='+subject);
    });

    //编辑题目
    $('.opt-set').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var question_id = opt_data.question_id;
        var editType = 2;
        var subject = $('#id_subject').val();
        window.open('/question_new/question_edit?editType='+editType+'&question_id='+question_id+'&subject='+subject);
    });

    //编辑对应的答案
    $('.edit_question_know').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var question_id = opt_data.question_id;
        window.open('/question_new/answer_edit?question_id='+question_id);
    })
    
    //编辑对应的知识点
    $('.add_question_know').on('click',function(){

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
                $.do_ajax("/question_new/question_dele",data);
            }
        });

    })
  
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
                $.do_ajax("/question_new/question_know_dele",data);
            }
        });

    })

    //禁用
    $('.lock_question_know').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var question_id = opt_data.question_id;
        var title = "你确定禁用该题目?";
        var data = {
            'question_id':question_id,
            'open_flag':0
        };
        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/question_new/question_flag",data);
            }
        });

    })

    //启用
    $('.unlock_question_know').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var question_id = opt_data.question_id;
        var title = "你确定启用该题目?";
        var data = {
            'question_id':question_id,
            'open_flag':1
        };
        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/question_new/question_flag",data);
            }
        });

    })

})

function remove_knowledge(id){
    var obj = $("#knowledge_"+id);
    obj.remove();
}
