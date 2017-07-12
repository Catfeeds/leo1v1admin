$(function(){
    
    $("body").on("click",".change_button",function(){
        change_editor(this);
    });

    var change_editor = function(obj){
        var status = $(obj).siblings(".editor_text").data("status");
        var str    = '';
        if(status==1){
            str = $(obj).siblings(".editor_text").val();
            $(obj).siblings(".editor").show();
            $(obj).siblings(".editor_text").hide();
            $(obj).siblings(".editor").find(".myEditor").html(str);
            $(obj).siblings(".editor_text").data("status",0);
        }else{
            str = $(obj).siblings(".editor").find(".myEditor").html();
            $(obj).siblings(".editor").hide();
            $(obj).siblings(".editor_text").show();
            $(obj).siblings(".editor_text").val(str);
            $(obj).siblings(".editor_text").data("status",1);
        }
    };

});
