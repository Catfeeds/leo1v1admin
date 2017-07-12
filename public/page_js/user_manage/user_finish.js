$(function(){

	 //init data
	 if ( g_phone != "" ){
		 $("#id_phone").val( g_phone);
		 $("#id_phone_title").hide();
		 $("#id_phone").show();
	 }

     if( g_nick  != "" ){
	     $("#id_nick").val( g_nick);
	     $("#id_nick_title").hide();
	     $("#id_nick").show();
	 }

     $("#id_grade").val(g_grade);

	//点击进入个人主页
	$('.opt-user').on('click',function(){
		var userid=$(this).parent().data("userid");
		var stu_nick=$(this).parent().data("stu_nick");
		wopen('/stu_manage?sid='+userid+'&nick='+stu_nick+"&source=audition");
	});


    $(".done_a").on('click',function(){
        window.location.href = "/stu_manage?sid="+$(this).parent().data('userid')+"&nick="+$(this).parent().data('nick');
    });

    $(".done_b").on("click", function(){
        $(".blue_btn").data("userid", $(this).parent().data("userid"));
        $(".mesg_alert03").show();
    });
    
    $(".blue_btn").on("click", function(){
        $.ajax({
			type     :"post",
			url      :"/revisit/add_revisit_record",
			dataType :"json",
			data     :{"userid":$(this).data('userid'),"revisit_person":$("#id_revisit_person").val(),"operator_note":$("#id_revisit_note").val()},
			success  : function(result){
				if(result.ret !== 0){
					alert("添加回访记录失败");
				}else{
					$(".mesg_alert03").hide();
				}
			}
		});
    });

    $(".will_search").on("click", function(){
        var nick = $("#id_nick").val();
        var phone = $("#id_phone").val();
        window.location.href = "/user_manage/user_finish/?nick="+nick+"&phone="+phone;
    });

    $("#id_grade").on("change", function(){
        var grade = $(this).val();
        window.location.href = "/user_manage/user_finish/?grade="+grade;
    });

});
