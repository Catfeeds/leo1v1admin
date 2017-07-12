$(function(){
    
    $(".remind_type").on('click', function(){
        var auth = $(this).data('auth');
        var uid = $(this).data('userid');
        $(this).siblings().removeClass('current');
        $(this).addClass('current');
        $.ajax({
			type     :"post",
			url      :"/todo/get_remind_for_type",
			dataType :"json",
			data     :{'auth':auth,'uid':uid, 'type':$(this).data('remind_type')},
			success  : function(result){
                if(result['ret'] == 0){
                    $("#id_todo_list").html("");
                    $("#id_todo_list").html(result['todo_list']);
                }
			}
		});
    }); 

    var first_remind = $(".remind_type:first");
    //click事件必须在定义了相关click后调用
    first_remind.click();

    $(".jump_to").live("click", function(){
        var url = $(this).data('url');
        $.ajax({
			type     :"post",
			url      :"/todo/set_remind_to_doing",
			dataType :"json",
			data     :{'remind_id':$(this).data('remind_id')},
			success  : function(result){
                if(result['ret'] == 0){
                    window.location.href = url;
                }
			}
		});

    });

});
