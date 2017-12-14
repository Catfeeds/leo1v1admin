/// <reference path="../common.d.ts" />
/// <reference path="../../page_js/question_edit_new.js" />
/// <reference path="../../js/MathJax/MathJax.js" />
/// <reference path="../g_args.d.ts/question_new-knowledge_edit.d.ts" />

$(function(){

    var id_question_type = null;
    var domain = 'http://7u2f5q.com2.z0.glb.qiniucdn.com/';


    //初始化每个公式显示框
    $('.MathPreview').each(function(){
        var mathId = $(this).attr('id');
        var id_index = get_content_id(mathId);
        var id_mathjax_content = $('#id_mathjax_content_'+id_index);
        var MathPreview = $('#'+mathId);
        mathId = document.getElementById(mathId);
        Cquestion_editor.init_mathjax(mathId);
        //上传图片
        Cquestion_editor.custom_upload( $('#id_mathjax_add_pic_'+id_index)[0],$('#id_mathjax_add_pic_div_'+id_index)[0],domain,null,id_mathjax_content,MathPreview,mathId);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);

        var answer_type = $(this).parents('.answer_step').find('.answer_type').attr('id');
        Enum_map.append_option_list("answer_type",$("#"+answer_type),true);
        if( answer_type != 'answer_type_0'){
            var answer_type_value = $(this).parents('.answer_step').find('.answer_type_value').val();
            //console.log(answer_type_value);
            $("#"+answer_type).val(answer_type_value);
        } 
    });

    // 为每一个textarea绑定事件使其高度自适应
    $.each($("textarea"), function(i, n){
        Cquestion_editor.autoTextarea($(n)[0]);
    });

    //失去光标事件
    $('.id_mathjax_content').blur(function(){
        var id_mathjax = $(this).attr('id');
        var id_mathjax_content = $('#'+id_mathjax);
        var id_index = get_content_id(id_mathjax);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);
        var val = $(this).val();
        if (!val.match(/\$/)) {
            //$(this).val(Cquestion_editor.reset_latex_str(val));
        }
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    });

    //输入事件
    $('.id_mathjax_content').bind('input propertychange',function(){
        var id_mathjax = $(this).attr('id');
        var id_mathjax_content = $('#'+id_mathjax);
        var id_index = get_content_id(id_mathjax);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    }); 

    //点击传符号
    $('.dropdown-menu li button').click(function(){
        var navbar = '$'+$(this).find('script[type="math/tex"]').html()+'$';
        var id = $(this).parents('.navbar-nav').attr('id');
        var id_index = get_content_id(id);
        var id_mathjax_content = $('#id_mathjax_content_'+id_index);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);

        id_mathjax_content.insertAtCaret(navbar);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    })

    //插入括号
    $('.add_kuo_hao').click(function(){
        var kuohao = '(  )';
        var id = $(this).attr('id');
        var id_index = get_content_id(id);
        var id_mathjax_content = $('#id_mathjax_content_'+id_index);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);
        id_mathjax_content.insertAtCaret(kuohao);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    })

    //添加横线
    $('.add_under_line').click(function(){
        var kuohao = '____';
        var id = $(this).attr('id');
        var id_index = get_content_id(id);
        var id_mathjax_content = $('#id_mathjax_content_'+id_index);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);

        id_mathjax_content.insertAtCaret(kuohao);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);

    })

    //保存答案
    $(".answer-save").click(function(){
        var obj = $(this).parents('.answer_step');
        var data = {
            'editType':obj.find('.editType').val(),
            'question_id':$('#question_id').val(),
            'answer_id':obj.find('.answer_id').val(),
            'step':obj.find('.step').val(),
            'answer_type':obj.find('.answer_type').val(),
            'detail':obj.find('.id_mathjax_content').val(),
            'score':obj.find('.answer_score').val(),
        };
        // console.log(data);
        // return false;
        $.ajax({
            type : "post",
            url : "/question_new/answer_add",
            dataType : "json",
            data:data,
            success : function(res){
                BootstrapDialog.alert(res.msg);
                if( res.status = 200 ){
                    window.location.reload();
                }
            },
            error:function(){
                BootstrapDialog.alert('取出错误');
            }
        });

    })

    //删除答案
    $('.answer-dele').click(function(){
        var obj = $(this).parents('.answer_step');
        var data = {
            'answer_id':obj.find('.answer_id').val(),
        };
        var title = "你确定删除本步骤";
       
        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/question_new/answer_dele",data);
            }
        });

    })
})

function get_content_id(mathId){
    var mathId_index = mathId.lastIndexOf('_')+1;
	  return mathId.substr(mathId_index);
}
