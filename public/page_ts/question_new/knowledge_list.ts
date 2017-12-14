/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question_new-knowledge_list.d.ts" />

$(function(){
    Enum_map.append_option_list("subject", $("#id_subject"),true,[1,2,3,4,5,6,7,8,9,10,11]);
 
    $("#id_subject").val(g_args.id_subject);
    $('.opt-change').set_input_change_event(load_data);

    function load_data(){

        var data = {
            id_subject : $("#id_subject").val(),
        };

        $.reload_self_page(data);
    }


    //进入知识点列表页面
    $('#question_list').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open('/question_new/question_list');
    });

    //添加根部知识点
    $('#id_add_knowledge').on('click',function(){
        var level = 0;
        var father_id = 0;
        var editType = 1;
        var father_subject = $('#id_subject').val();
        window.open('/question_new/knowledge_edit?level='+level+'&father_id='+father_id+'&editType='+editType+'&father_subject='+father_subject);
    });

    //添加子知识点
    $('.add_son').on('click',function(){
        var opt_data = $(this).get_opt_data();
        var level = parseInt(opt_data.level) + 1;
        var father_id = opt_data.knowledge_id;
        var father_subject = $('#id_subject').val();
        var editType = 1;
        window.open('/question_new/knowledge_edit?level='+level+'&father_id='+father_id+'&editType='+editType+'&father_subject='+father_subject);
    });

    //进入知识点编辑页面
    $('.opt-set').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var knowledge_id = opt_data.knowledge_id;
        var father_subject = $('#id_subject').val();
        var editType = 2;
        window.open('/question_new/knowledge_edit?knowledge_id='+knowledge_id+'&editType='+editType+'&father_subject='+father_subject);
    });

    //删除知识点
    $('.opt-del').on('click',function(){
        var opt_data = $(this).get_opt_data();

        var knowledge_id = opt_data.knowledge_id;
        var title = "你确定删除本知识点,标题为" + opt_data.title + "？";
        var data = {
            'knowledge_id':knowledge_id
        };

        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/question_new/knowledge_dele",data);
            }
        });

    })

})
