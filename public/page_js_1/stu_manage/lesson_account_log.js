// SWITCH-TO:   ../../template/student/
$(function(){
    $("#id_lesson_account_id").val( g_args.lesson_account_id );
    $("#id_lesson_account_id").on("change",function(){
        window.location.href=window.location.pathname+"?sid="+g_sid+"&lesson_account_id=" + $(this).val() ;
    });
}); 







