// JavaScript Document
	
$(function(){
	
	//初始化，下拉列表都恢复初始选项
	for(var i=0;i<$(".stu_tab select").length;i++){					
		$(".stu_tab select").eq(i).children('option').eq(0).attr('selected', 'true');
		$('.put_phone').val('');
		$('.put_name').val('');
	};
	
	//第一次获取学生列表
	function page_ajax(page_num,inde,grade,status,gift_sent,lesson_left,user_names,phones){
		$.ajax({
			url: 'http://adminapi.weiyi.com/stu_manage/get_stu_list',
			type: 'POST',
			data: {'page_num': page_num,'grade':grade,'status':status,'gift_sent':gift_sent,'lesson_left':lesson_left,'user_name':user_names,'phone':phones},
			dataType: 'jsonp',
			success: function(data) {
				if (data['ret'] == 0) {
					var user_source = [];
					if (data['user_list'].length != 0) {
						var user_num=data['user_list'].length;
						for (var i = 0; i < data['user_list'].length; i++ ) {
							var list_data = new Object();
							list_data.s_name   = data['user_list'][i].nick;			//姓名
							list_data.userid   = data['user_list'][i].userid;		//学生id
							list_data.p_name   = data['user_list'][i].parent_name;	//家长姓名
							list_data.phone    = data['user_list'][i].phone;  		//联系电话
							list_data.revisit  = data['user_list'][i].revisit_cnt;	//回访次数
							//list_data.lessons  = data['user_list'][i].gift_sent;  //签约课时
							//list_data.leave    = data['user_list'][i].revisit_cnt;//剩余课时
								
							if(data['user_list'][i].gift_sent==0){				//寄送礼包
								list_data.gift = '是';
							}else  if(data['user_list'][i].gift_sent==1){
								list_data.gift = '否';
							};
							
							if(data['user_list'][i].parent_type==1){			//关系
								list_data.relation ='父亲';
							}else if(data['user_list'][i].parent_type==2){
								list_data.relation ='母亲';
							}else if(data['user_list'][i].parent_type==3){
								list_data.relation ='爷爷';
							}else if(data['user_list'][i].parent_type==4){
								list_data.relation ='奶奶';
							}else if(data['user_list'][i].parent_type==5){
								list_data.relation ='外公';
							}else if(data['user_list'][i].parent_type==6){
								list_data.relation ='外婆';
							}else if(data['user_list'][i].parent_type==7){
								list_data.relation ='其他';
							};
							
							if(data['user_list'][i].grade==101){				//年级
								list_data.grade ='小一';
							}else if(data['user_list'][i].grade==102){
								list_data.grade ='小二';
							}else if(data['user_list'][i].grade==103){
								list_data.grade ='小三';
							}else if(data['user_list'][i].grade==104){
								list_data.grade ='小四';
							}else if(data['user_list'][i].grade==105){
								list_data.grade ='小五';
							}else if(data['user_list'][i].grade==106){
								list_data.grade ='小六';
							}else if(data['user_list'][i].grade==201){
								list_data.grade ='初一';
							}else if(data['user_list'][i].grade==202){
								list_data.grade ='初二';
							}else if(data['user_list'][i].grade==203){
								list_data.grade ='初三';
							}else if(data['user_list'][i].grade==301){
								list_data.grade ='高一';
							}else if(data['user_list'][i].grade==302){
								list_data.grade ='高二';
							}else if(data['user_list'][i].grade==303){
								list_data.grade ='高三';
							};
							
							if(data['user_list'][i].status==0){					//学程状态
								list_data.status ='待付款';
							}else if(data['user_list'][i].status==1){
								list_data.status ='待分配老师';
							}else if(data['user_list'][i].status==2){
								list_data.status ='正常上课';
							}else if(data['user_list'][i].status==3){
								list_data.status ='正常结课';
							}else if(data['user_list'][i].status==4){
								list_data.status ='退费成功';
							}else if(data['user_list'][i].status==5){
								list_data.status ='申请退费';
							};
							
							var operate_num=data['user_list'][i].operate;		//操作
							
							var operate_strs=new Array();
							var title_arr=['个人主页','录入回访','安排老师','安排学管师','处理退费申请','寄送礼包','排课']
							operate_strs = operate_num.split(",");
							list_data.operate='';
							$.each(operate_strs,function(i,n){
								list_data.operate+="<a class='done_"+n+"' title='"+title_arr[i]+"'></a>";
							});
							
							user_source.push(list_data);
						}
	
					};
					console.log(data['page_cnt']);
					var page_num=data['page_cnt'];
				}
				console.log(user_source);
				console.log("work02 ....");
				//动态生成表格
				var user_table=$('.stu_tab02');
				user_table.children('tbody').empty();
				for(var d=0;d<user_num;d++){
					var tr=$("<tr></tr>");
					tr.appendTo(user_table);
					for(var e=0;e<11;e++){
						var td=$("<td></td>");
						td.appendTo(tr);
					}
				};
				
				//数据填进表格
				for(var f=0;f<user_num;f++){
					user_table.children('tbody').children('tr').eq(f).children('td').eq(0).html(user_source[f].s_name);
					user_table.children('tbody').children('tr').eq(f).children('td').eq(1).html(user_source[f].p_name);
					user_table.children('tbody').children('tr').eq(f).children('td').eq(2).html(user_source[f].relation);
					user_table.children('tbody').children('tr').eq(f).children('td').eq(3).html(user_source[f].phone);
					user_table.children('tbody').children('tr').eq(f).children('td').eq(4).html(user_source[f].grade);
					user_table.children('tbody').children('tr').eq(f).children('td').eq(5).html(user_source[f].status);
					user_table.children('tbody').children('tr').eq(f).children('td').eq(6).html(user_source[f].revisit);
					user_table.children('tbody').children('tr').eq(f).children('td').eq(7).html(0);
					user_table.children('tbody').children('tr').eq(f).children('td').eq(8).html(0);
					user_table.children('tbody').children('tr').eq(f).children('td').eq(9).html(user_source[f].gift);
					user_table.children('tbody').children('tr').eq(f).children('td').eq(10).addClass('done_icon').html("<div class='kuan'><img src='images/done_left.png' /><span>"+user_source[f].operate+"</span><img src='images/done_right.png' /><input type='hidden' value='"+data['user_list'][f].userid+"'/></div>");
				};
				
				//分页
				$('.de_page').empty();
				var de_page=$('.de_page')
				$("<a href='javascript:;' class='pre'>上一页</a>").appendTo(de_page);
				for(var d=1;d<page_num+1;d++){
					var page_a=$("<a class='page_click' href='javascript:;'>"+d+"</a>");
					page_a.appendTo(de_page);
				};
				$("<a href='javascript:;' class='next'>下一页</a>").appendTo(de_page);
				
				//当前页码current
				$('.page_click').eq(inde).addClass('current').siblings('.page_click').removeClass('current');
				
				//上一页、下一页按钮不可点击样式
				if(inde==0){
					$('.pre').addClass('current');
				}else if(inde==$('.page_click').length-1){
					$('.next').addClass('current');
				}
				
			}
		});
		
		$(".stu_tab select").change( function() {
			var stu_select=[];
			var select_data = new Object();
				
				if($(".stu_tab select").get(0).selectedIndex==0){				//年级
					select_data.grade='';
				}else if($(".stu_tab select").get(0).selectedIndex==1){			
					select_data.grade=101;
				}else if($(".stu_tab select").get(0).selectedIndex==2){
					select_data.grade=102;
				}else if($(".stu_tab select").get(0).selectedIndex==3){
					select_data.grade=103;
				}else if($(".stu_tab select").get(0).selectedIndex==4){
					select_data.grade=104;
				}else if($(".stu_tab select").get(0).selectedIndex==5){
					select_data.grade=105;
				}else if($(".stu_tab select").get(0).selectedIndex==6){
					select_data.grade=106;
				}else if($(".stu_tab select").get(0).selectedIndex==7){
					select_data.grade=201;
				}else if($(".stu_tab select").get(0).selectedIndex==8){
					select_data.grade=202;
				}else if($(".stu_tab select").get(0).selectedIndex==9){
					select_data.grade=203;
				}else if($(".stu_tab select").get(0).selectedIndex==10){
					select_data.grade=301;
				}else if($(".stu_tab select").get(0).selectedIndex==11){
					select_data.grade=302;
				}else if($(".stu_tab select").get(0).selectedIndex==12){
					select_data.grade=303;
				};
					
				if($(".stu_tab select").get(1).selectedIndex==0){				//学程状态
					select_data.status='';
				}else if($(".stu_tab select").get(1).selectedIndex==1){			
					select_data.status=0;
				}else if($(".stu_tab select").get(1).selectedIndex==2){			
					select_data.status=1;
				}else if($(".stu_tab select").get(1).selectedIndex==3){
					select_data.status=2;
				}else if($(".stu_tab select").get(1).selectedIndex==4){
					select_data.status=3;
				}else if($(".stu_tab select").get(1).selectedIndex==5){
					select_data.status=4;
				}else if($(".stu_tab select").get(1).selectedIndex==6){
					select_data.status=5;
				}else if($(".stu_tab select").get(1).selectedIndex==7){
					select_data.status=6;
				};
				
				if($(".stu_tab select").get(2).selectedIndex==0){				//礼包寄送
					select_data.gift_sent='';
				}else if($(".stu_tab select").get(2).selectedIndex==1){			
					select_data.gift_sent=0;
				}else if($(".stu_tab select").get(2).selectedIndex==2){
					select_data.gift_sent=1;
				};
					
				if($(".stu_tab select").get(3).selectedIndex==0){				//剩余课时
					select_data.lesson_left='';
				}else if($(".stu_tab select").get(3).selectedIndex==1){			
					select_data.lesson_left=0;
				}else if($(".stu_tab select").get(3).selectedIndex==2){
					select_data.lesson_left=1;
				};
				
			stu_select.push(select_data);
			//console.log(stu_select);
			//console.log(select_data.grade)
			page_ajax(1,0,select_data.grade,select_data.status,select_data.gift_sent,select_data.lesson_left);//下拉列表选中即时刷新列表
		});
		
		//手动输入查找学生函数
		var user_names;
		var phones;
		function putin_search(){
			if($('.put_name').val()==''){
				user_names = '';
			}else{
				user_names = $('.put_name').val();
			};
			if($('.put_phone').val()==''){
				phones = '';
			}else{
				phones = $('.put_phone').val();
			};
				
			console.log(user_names+phones);
			page_ajax(1,0,'','','','',user_names,phones);
		};
		
		$('.stu_search').click(function(){
			putin_search();
		});//按钮调用函数
		$(window).keypress(function(e) { 
			if (e.which == 13) { 
				putin_search();
			} 
		});//回车键调用函数
		
	};
	
	page_ajax(1,0);	//初始化在第一页
	
	var inde=0;
	var page_num=inde+1;
	
	//单个页码点击
	$('.page_click').live('click', function(){
		inde=$(this).index()-1;
		page_num=inde+1;
		page_ajax(page_num,inde);
	});
	
	//点击下一页
	$('.next').live('click', function(){
		$('.pre').removeClass('current');
		if(page_num<$('.page_click').length){
			inde++;
			page_num=inde+1;
			page_ajax(page_num,inde);
		};
	}); 
	
	//点击上一页 
	$('.pre').live('click', function(){
		$('.next').removeClass('current');
		if(page_num>1){
			inde--;
			page_num=inde+1;
			page_ajax(page_num,inde);
		};
	});
		
		
	//点击进入个人主页
	$('.done_a').live('click',function(){		
	
		var userid=$(this).parent().siblings('input').val();
		
		$(this).attr('href','page1_01.html?sid='+userid);
		
	});
	
		
	
	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
})