// JavaScript Document
$(function(){
	
	//下拉列表选择老师
	$('.teachIcon').click(function(e){
		$(this).siblings('.teacher_list').slideDown().focus();
		e.stopPropagation();//阻止冒泡
			
		$(".teacher_list").click(function(e){
			e.stopPropagation();
		});
		
		$(document).click(function(){
			$(".teacher_list").hide();
		})
		
	});
	
	//点击列表中的老师姓名进入input框中
	/*var name;
	var inputCont;
	var click_num=0;
	$('.teacher_list li').click(function( ){
		click_num+=1;
		if(click_num>3){
			$('.teacherName_put').val('');
			click_num=0;
		}else{
			name=$(this).html();
			inputCont+=name;
			$('.teacherName_put').val(inputCont);
		}
		
	})*/
	
	
	//课件上传按钮点击进入详情
	/*$('.load_cont .done_r').click(function(){
		$('.upload_detail').show().siblings('.upload_list').hide();
	});
	
	$('.load_cont .upload_back').click(function(){
		$('.upload_detail').hide().siblings('.upload_list').show();
	});*/
	
	//作业批改按钮点击进入详情
	$('.work_correct .done_r').click(function(){
		$('.work_detail').show().siblings('.work_list').hide();
	});
	
	$('.work_correct .upload_back').click(function(){
		$('.work_detail').hide().siblings('.work_list').show();
	});
	
	//试卷批改按钮点击进入详情
	$('.text_correct .done_r').click(function(){
		$('.text_detail').show().siblings('.text_list').hide();
	});
	
	$('.text_correct .upload_back').click(function(){
		$('.text_detail').hide().siblings('.text_list').show();
	});
	
	
	//无标题弹窗的关闭按钮
	$('.closed').click(function(){
		$(this).parent().parent('.mesg_alert').hide();
	});
	
})



	/*页面tab栏*/
	function tab(mateName,mateF,tabNum,boxName,zIndex){
		
		$(mateName).eq(zIndex).removeClass('td').siblings(mateF).addClass('td');				//面包屑导航
		$(tabNum).eq(zIndex).addClass('current').siblings('td').removeClass('current');		//tab导航
		$(boxName).eq(zIndex).show();														//主题内容box
		
		$(tabNum).click(function(){
			var td_z=$(this).index();
			$(mateName).eq(td_z).removeClass('td').siblings(mateF).addClass('td');
			$(this).addClass('current').siblings('td').removeClass('current');
			$(boxName).eq(td_z).show().siblings('div').hide();
		});
	};
	
	
	//按钮弹出框
	function btn_s(done_btn,mesag_alert){
		$(done_btn).click(function(){
			$(mesag_alert).show();
		});
	};



	
	
	
	
	
	
	
	