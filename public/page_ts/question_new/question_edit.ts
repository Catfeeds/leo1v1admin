/// <reference path="../common.d.ts" />
/// <reference path="../../page_js/question_edit_new.js" />
/// <reference path="../../js/MathJax/MathJax.js" />
/// <reference path="../g_args.d.ts/question_new-question_edit.d.ts" />

$(function(){
    var id_question_type = $('#id_question_type');      //题型
    var id_mathjax_content = $('#id_mathjax_content');　//题目输入框           
    var MathPreview = $('#MathPreview');　　　　　　　　//题目预选显示框
    var mathId = document.getElementById("MathPreview"); //选择公式识别范围
    var MathBuffer =  $('#MathBuffer');　
    var timer = true;
    Cquestion_editor.init_mathjax(mathId);

    //失去光标事件
    $('#id_mathjax_content').blur(function(){
        var val = $(this).val();
        if (!val.match(/\$/)) {
            //$(this).val(Cquestion_editor.reset_latex_str(val));
            Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathBuffer,MathPreview,mathId)
        }
        timer = false;
    });

    //输入事件
    $('#id_mathjax_content').bind('input propertychange',function(){
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathBuffer,MathPreview,mathId);
    });


    $('#id_mathjax_content').focus(function(){
        timer = true;
        var push_buffer = setInterval(function(){
            Cquestion_editor.push_buffer(mathId);
            if(!timer){//满足某个条件时 清除定时器
                clearInterval(push_buffer);
            }
        },1000)

    });

    //上传图片
    $('#id_mathjax_add_pic').click(function(){
        
    })
})

