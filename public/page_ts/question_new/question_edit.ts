/// <reference path="../common.d.ts" />
/// <reference path="../../page_js/question_edit_new.js" />
/// <reference path="../../js/MathJax/MathJax.js" />
/// <reference path="../g_args.d.ts/question_new-question_edit.d.ts" />

$(function(){
    var id_question_type = $('#id_question_type');      //题型
    var id_mathjax_content = $('#id_mathjax_content');　//题目输入框           
    var MathPreview = $('#MathPreview');　　　　　　　　//题目预选显示框
    var mathId = document.getElementById("MathPreview"); //选择公式识别范围

    Cquestion_editor.init_mathjax(mathId);

    //失去光标事件
    $('#id_mathjax_content').blur(function(){
        var val = $(this).val();
        if (!val.match(/\$/)) {
            //$(this).val(Cquestion_editor.reset_latex_str(val));
        }
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    });

    //输入事件
    $('#id_mathjax_content').bind('input propertychange',function(){
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    });

    //光标事件
    $('#id_mathjax_content').focus(function(){

    });

    //上传图片
    var domain = 'http://7u2f5q.com2.z0.glb.qiniucdn.com/';
    Cquestion_editor.custom_upload( $('#id_mathjax_add_pic')[0],$('#id_mathjax_add_pic_div')[0],domain,id_question_type,id_mathjax_content,MathPreview,mathId); 
    //点击传符号
    $('.dropdown-menu li button').click(function(){
        var navbar = '$'+$(this).find('script[type="math/tex"]').html()+'$';
        id_mathjax_content.insertAtCaret(navbar);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    })

    //插入括号
    $('#id_mathjax_add_kuo_hao').click(function(){
        var kuohao = '(  )';
        id_mathjax_content.insertAtCaret(kuohao);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    })

    $('#id_mathjax_add_under_line').click(function(){
        var kuohao = '____';
        id_mathjax_content.insertAtCaret(kuohao);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);

    })
})

