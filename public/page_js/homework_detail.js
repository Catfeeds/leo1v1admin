$(function(){
	$(".done_s").on('click',function(){
		var file_url = $(this).parent().data("url");
		if(file_url != ""){
			$.ajax({
				type     :"post",
				url      :"/upload/get_download_url/",
				dataType :"json",
				data     :{"file_url":file_url},
				success  : function(result){
					if(result.ret == 0){
						window.open(result.download_url); 
					}
				}
			});
		}
	});

	
	function load_data( $stu_nick, $is_checked, $teacherid){
		var url="/tea_manage/homework_detail?stu_nick="+$stu_nick+"&is_checked="+$is_checked+"&teacherid="+$teacherid;
		window.location.href=url;
	}

	$(".hw_change").on("change",function(){
		var stu_nick = $("#id_stu_list").val();
		var teacherid = $("#id_teacherid").data("teacherid");
		var is_checked = $("#id_is_checked").val();
		load_data(stu_nick, is_checked, teacherid);
	});

});
