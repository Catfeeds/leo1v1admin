/// <reference path="../g_args.d.ts/question_new-knowledge_get.d.ts" />

$(function(){
    $("#text_book_knowledge").click(function(){
        var subject = $("#subject").val();
        window.open('/question_new/knowledge_list?id_subject='+subject);
    })
})

