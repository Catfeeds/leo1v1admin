// JavaScript Document
$(function(){
	//有标题弹窗的关闭按钮
	$('.alert .close a').click(function(){
		$('.shadow').hide();
		$('.alert').hide();
	});
	
	//无标题弹窗的关闭按钮
	$('.closed').click(function(){
		$(this).parent().parent('.mesg_alert').hide();
	});
	
	//编辑资料
	$('.re_editor').click(function(){
		for(i=0;i<$('.edit_b').length;i++){
			$('.edit_b').eq(i).val($('.put_mesag').eq(i).html());
		};
		$('.put_mesag').hide().siblings('.edi').show();
		$('.put_mesag02').hide().siblings('.edi').show();
		$('.put_mesag03').hide().siblings('.edi').show();
		$('.put_mesag04').hide().siblings('.edi').show();
		$('.watch').hide().siblings('.re_change').show();
		$(this).hide().siblings('.load_editor').show();
		
	});
	
	$('.load_editor').click(function(){
		for(i=0;i<$('.edit_b').length;i++){
			$('.put_mesag').eq(i).html($('.edit_b').eq(i).val());
		};
		$('.put_mesag').show().siblings('.edi').hide();
		$('.put_mesag02').show().html($("#tea_sexy").val()).siblings('.edi').hide();
		$('.put_mesag03').show().html($("#tea_job").val()).siblings('.edi').hide();
		$('.put_mesag04').show().html($("#tea_grade").val()).siblings('.edi').hide();
		$('.watch').show().siblings('.re_change').hide();
		$(this).hide().siblings('.re_editor').show();
	});
	
	//教师详情页面
	$('.teacher_list .done_o').click(function(){
		$('.teach_mesg').show().siblings('.teacher_list').hide();
	});
	
	$('.teach_mesg .back').click(function(){
		$('.teach_mesg').hide().siblings('.teacher_list').show();
	});
	
	//角色名更改
	$('.player_right .edit').click(function(){
		var value=$(this).siblings('i').children('b').html();
		$(this).siblings('.player_put').val(value);
		$('.player_right .player_put').show().siblings('i').children('b').hide();
		$(this).hide().siblings('.load_in').show();
	});
	
	$('.player_right .load_in').click(function(){
		var value=$(this).siblings('.player_put').val();
		$(this).siblings('i').children('b').html(value);
		$('.player_right i b').show().parent('i').siblings('.player_put').hide();
		$(this).hide().siblings('.edit').show();
	});
	
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
	
	
	//有阴影弹窗
	function shadow_alert(btnA,alertA){
		$(btnA).click(function(){
			var le_h=$('.left').height();
			var r_h=$('.right').height();
			var t_h=$('.top').height()+40;
			var all_H;
			if(le_h>=r_h){
				all_H=le_h+t_h;
			}else{
				all_H=r_h+t_h;
			};
			$('.shadow').show().css('height',all_H);
			$(alertA).show();
		});
	};

