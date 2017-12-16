/// <reference path="../common.d.ts" />
/// <reference path="../../page_js/question_edit_new.js" />
/// <reference path="../../js/MathJax/MathJax.js" />
/// <reference path="../g_args.d.ts/question_new-knowledge_get.d.ts" />
/// <reference path="../../js/MathJax/MathJax.js" />
/// <reference path="../g_args.d.ts/question_new-knowledge_edit.d.ts" />

$(function(){

    var mathId = document.getElementById('knowledge_pic');
    Cquestion_editor.init_mathjax(mathId);

    $("#text_book_knowledge").click(function(){
        var subject = $("#subject").val();
        window.open('/question_new/knowledge_list?id_subject='+subject);
    })
})

