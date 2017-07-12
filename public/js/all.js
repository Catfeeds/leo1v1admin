// JavaScript Document
$(function(){
	var screen_h=$(document).height();
	var left_h=screen_h-150;
	$('.left').height(left_h);
	
	//左侧导航
	$('.nav_icon .navLi_icon').click(function(){
		var x=$(this).index();
		if($('.nav_cont .nav_one').eq(x).is(':hidden')){
			$('.pointer').stop().animate({top:50+115*x},300);//箭头
			$('.navCont_box').stop().show();
			setTimeout(function(){
				$('.navCont_box').stop().animate({left:105,opacity:1},300);
			},5);
			$('.nav_cont .nav_one').eq(x).addClass('dis_on').siblings().removeClass('dis_on');
		}else{
			$('.pointer').stop().animate({top:50},300);//箭头
			$('.navCont_box').stop().animate({left:75,opacity:0},300);
			setTimeout(function(){
				$('.navCont_box').stop().hide();
			},300);
			$('.nav_cont .nav_one').removeClass('dis_on');
		};
		$(document).one('click',function(){
			$('.pointer').stop().animate({top:50},300);//箭头
			$('.navCont_box').stop().animate({left:75,opacity:0},300);
			setTimeout(function(){
				$('.navCont_box').stop().hide();
			},300);
			$('.nav_cont .nav_one').removeClass('dis_on');
		});
		return false;
	});
	
	$('.navCont_box').click(function(){
		return false;
	});
	
	
	
	
	/*$('.alert .alert_btn a').click(function(){
		$('.shadow').hide();
		$('.alert').hide();
	});
	
	$('.add_btn').click(function(){
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
		$('.alert02').show();
	});*/
	
	
	//input
	$('.click_no').click(function(){
		$(this).hide().next('.click_on').show().css('color','#333').val('').focus();
	});
	
	$('.click_no').focus(function(){
		$(this).hide().next('.click_on').show().css('color','#333').val('').focus();
		
	});
	
	$('.click_on').blur(function(){
		if($(this).val()==''){
			$(this).hide().prev('.click_no').show();
		};
	});
	
	//个人主页弹窗开关
	$('.editor01').click(function(){
		$('.mesg_alert01').show();
	});
	
	
	

	//编辑资料
	$('.re_editor').click(function(){
		for(i=0;i<$('.edit_b').length;i++){
			$('.edit_b').eq(i).val($('.myself').eq(i).html());
		};
		$('.myself').hide().siblings('.edi').show();
		$('.myself03').hide().siblings('.edi').show();
		$('.myself04').hide().siblings('.edi').show();
		$('.myself05').hide().siblings('.edi').show();
		$('.myself06').hide().siblings('.edi').show();
		$(this).hide().siblings('.load_editor').show();
	});
	
	$('.load_editor').click(function(){
		for(i=0;i<$('.edit_b').length;i++){
			$('.myself').eq(i).html($('.edit_b').eq(i).val());
		};
		
		$('.myself').show().siblings('.edi').hide();
		$('.myself04').show().html($("#relationship  option:selected").text()).siblings('.edi').hide();
		$('.myself03').show().html($("#sexy  option:selected").text()).siblings('.edi').hide();
		$('.myself05').show().html($("#textbook  option:selected").text()).siblings('.edi').hide();
		$('.myself06').show().html($("#gradeId  option:selected").text()).siblings('.edi').hide();
		$(this).hide().siblings('.re_editor').show();
	});
	

	$('.editor03').click(function(){
		$('.edit_b03').val($('.myself02').html());
		$('.myself02').hide().siblings('.edi').show();
		$(this).hide().siblings('.ss').show();
	});
	
	$('.ss').click(function(){
		$('.myself02').html($('.edit_b03').val());
		$('.myself02').show().siblings('.edi').hide();
		$(this).hide().siblings('.editor03').show();
	});
	
	
	
	//回访内容展开
	$('.down_arrow').click(function(){
		if($(this).siblings('div').hasClass('td_norap')){
			$(this).siblings('div').removeClass('td_norap');
			$(this).addClass('up_arrow');
		}else{
			$(this).siblings('div').addClass('td_norap');
			$(this).removeClass('up_arrow');
		}
		
	});
	
	
	
	/*测评成绩图标切换*/
	$('.see_tab').click(function(){
		$(this).hide().siblings('.see_chart').show();
		$(this).parent('p').siblings('#myChart').hide().siblings('.exam_tab').show();
	});
	
	$('.see_chart').click(function(){
		$(this).hide().siblings('.see_tab').show();
		$(this).parent('p').siblings('#myChart').show().siblings('.exam_tab').hide();
	});
	
	/*意向用户上传最近测评*/
	$('.will_user h4').click(function(){
		if($(this).next('ul').is(':hidden')){
			$(this).addClass('current');
			$(this).next('ul').slideDown();
		}else{
			$(this).removeClass('current');
			$(this).next('ul').slideUp();
		}
	});
	
	//弹出框内的排课情况
	/*$('.clcOn').click(function(){
		$(this).hide().siblings('img').show();
		$(this).parent('td').parent('tr').next('.class_plan').show();
	});
	
	$('.clcNo').click(function(){
		$(this).hide().siblings('img').show();
		$(this).parent('td').parent('tr').next('tr').hide();
	});*/
		
	//退出登录按钮	
	$('#id_system_logout').on('click', function(){
		$.ajax({
			'url': '/login/logout',
			'type': 'POST',
			'data': {},
			'dataType': 'jsonp',
			success: function(data) {
				if (data['ret'] == 0) {
					window.location.href="/";
				} else {
					console.log(data);
				}
			}
		})

    })
	
	//课时计划知识点编辑
	$('.knowlege_edi').click(function(){
		$(this).parent().parent().siblings().find('input').show().siblings('span').hide();
		for(var i=0;i<$('.knownlege input').length;i++){
			$('.knownlege input').eq(i).val($('.knownlege span').eq(i).html());
		};
		$(this).addClass('td').siblings().removeClass('td');
		$(this).parent().siblings().children('.send').addClass('td').siblings().removeClass('td');
	});
	
	$('.knowlege_load').click(function(){
		$(this).parent().parent().siblings().find('input').hide().siblings('span').show();
		
		for(var i=0;i<$('.knownlege input').length;i++){
			$('.knownlege span').eq(i).html($('.knownlege input').eq(i).val());
		};
		
		$(this).addClass('td').siblings().removeClass('td');
		$(this).parent().siblings().children('.clean').addClass('td').siblings().removeClass('td');
	});
	
	//摸底分析界面打开
	$('.done_k').click(function(){
		$('.will_list').addClass('td').siblings('.will_text').removeClass('td');
	});
	
	$('.will_text .back').click(function(){
		$('.will_list').removeClass('td').siblings('.will_text').addClass('td');
	});
	
	//潜在用户回访信息展开
	$('.done_i').click(function(){
		var nexr_tr=$(this).parent().parent().parent().parent().next('.return_tr')
		if(nexr_tr.is(':hidden')){
			nexr_tr.show();
		}else{
			nexr_tr.hide();
		}
	});
	
	//潜在注册用户的备注修改
	$('.remark_see button').click(function(){
		var remarkP=$(this).siblings('p').html();
		$(this).parent().hide().siblings('.remark_write').show();
		if(remarkP=='暂无备注'){
			$('.remark_write textarea').html('');
		}else{
			$('.remark_write textarea').html(remarkP);
		}
		
	});
	
	$('.remark_write button').click(function(){
		var remarkText=$(this).siblings('textarea').val();
		$(this).parent().hide().siblings('.remark_see').show();
		if(remarkText==''){
			$('.remark_see p').html('暂无备注');
		}else{
			$('.remark_see p').html(remarkText);
		}
		
	});
	
	//潜在注册用户的备注信息展开
	$('.done_h').click(function(){
		var nexr_tr=$(this).parent().parent().parent().parent().next().next('.remark_tr')
		if(nexr_tr.is(':hidden')){
			nexr_tr.show();
		}else{
			nexr_tr.hide();
		}
	});
	
	//合同详情查看
	/*$('.done_o').click(function(){
		$('.contract_cont').show().siblings('.contract_list').hide();
	});
	
	$('.opera_state .back').click(function(){
		$('.contract_cont').hide().siblings('.contract_list').show();
	});*/
	
	//合同查出记录后显示合同详情弹窗
	/*$('.mesg_alert08 .contr_btn').click(function(){
		if($('.stu_contra').has('ul').length){
			$('.mesg_alert08').hide();
			$('.mesg_alert09').show();
			
		}else{
			$('.mesg_alert08').hide();
		}
	})*/
	
	//修改弹出框的实退金额
	$('.alert_backMony').click(function(){
		$('.mesg_alert06').show();
	});
	
	$('.mesg_alert06 .editor03').click(function(){
		var has_mony=$(this).siblings('span').html();
		$('.input_mony').show().val(has_mony).siblings('span').hide();
	});
	
	$('.mesg_alert06 .ss').click(function(){
		var has_mony=$(this).siblings('.input_mony').val();
		$('.input_mony').hide().siblings('span').show().html(has_mony);
	});
		
	//退费详情
	$('.done_r').click(function(){
		var nexr_tr=$(this).parent().parent().parent().parent().next('.backMony_all')
		if(nexr_tr.is(':hidden')){
			nexr_tr.show();
		}else{
			nexr_tr.hide();
		}
	});	
	
	
	
	
	
	
	
	//弹窗确认关闭按钮
	/*$('.blue_btn').click(function(){
		$('.mesg_alert').hide();
	});*/
	
	
	
	//有阴影弹框
	shadow_alert('.top a','.alert01');
	shadow_alert('.alert .alert_btn a','.alert02');
	
	//有标题弹窗的关闭按钮
	$('.alert .close a').click(function(){
		$('.shadow').hide();
		$('.alert').hide();
	});
	
	//无标题弹窗的关闭按钮
	$('.closed').click(function(){
		$(this).parent().parent('.mesg_alert').hide();
	});
	
	
	//滚动屏幕，分析报告吸顶
	$(window).scroll(function(){
		if($(window).scrollTop()>290){
			$('.analysis').css({'position':'fixed','top':'0','right':'50px'})
		}else{
			$('.analysis').css({'position':'absolute','top':'290px'})
		}
	})
	
	
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

	function shadow_alert(btnA,alertA){
		//有阴影弹窗
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

	














