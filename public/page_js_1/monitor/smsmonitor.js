
$(function(){
    $("#id_start").val(g_args.start );
    $("#id_end").val(g_args.end );
    $("#id_phone").val(g_args.phone);
    $("#id_is_succ").val(g_args.is_succ);
    
    $(".opt-change").on("change",function(){
	    //
        reload_self_page({
            "start" : $("#id_start").val(),
            "end" : $("#id_end").val(),
            "phone" : $("#id_phone").val(),
            "is_succ" : $("#id_is_succ").val()
        });
	    
    });


    
});
