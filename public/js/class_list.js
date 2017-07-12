// JavaScript Document
$(function(){
	//第一次获取学生列表
	function lesson_ajax(page_num,lesson_status,lesson_time,start_date,end_time,stu_attend){
		$.ajax({
			url: 'http://adminapi.weiyi.com/lesson_manage/get_lesson_records',
			type: 'POST',
			data: {'page_num': page_num,'courseid':10,'lesson_status':lesson_status,'lesson_time':lesson_time,'start_date':start_date,'end_time':end_time,'stu_attend':stu_attend},
			dataType: 'jsonp',
			success: function(data) {
				if (data['ret'] == 0) {
					console.log('ok');
					var classList_num = [];
					if (data['lesson_list'].length != 0) {
						var classNum_data = new Object();
						for (var i = 0; i < data['lesson_list'].length; i++ ) {
							classNum_data.tea_nick   	= data['lesson_list'][i].tea_nick;			//教师名称
							classNum_data.user_nick   	= data['lesson_list'][i].user_nick;			//学生名称
							classNum_data.lesson_start  = data['lesson_list'][i].lesson_start;	//上课开始时间
							classNum_data.lesson_end   	= data['lesson_list'][i].lesson_end;		//上课结束时间
							classNum_data.lesson_num   	= data['lesson_list'][i].lesson_num;		//课次
							classNum_data.grade   		= data['lesson_list'][i].grade;					//年级
							classNum_data.lesson_status = data['lesson_list'][i].lesson_status;	//课程状态
							classNum_data.stu_attend   	= data['lesson_list'][i].stu_attend;		//学生上课情况
							
							if(data['lesson_list'][i].lesson_time==1){								//上课时段
								classNum_data.tea_nick  = '8：00-9：30';
							}else if(data['lesson_list'][i].lesson_time==2){
								classNum_data.tea_nick  = '10：00-11：30';
							}else if(data['lesson_list'][i].lesson_time==3){
								classNum_data.tea_nick  = '12：30-14：00';
							}else if(data['lesson_list'][i].lesson_time==4){
								classNum_data.tea_nick  = '14：15-15：45';
							}else if(data['lesson_list'][i].lesson_time==5){
								classNum_data.tea_nick  = '16：00-17：30';
							}else if(data['lesson_list'][i].lesson_time==6){
								classNum_data.tea_nick  = '18：30-20：00';
							}else if(data['lesson_list'][i].lesson_time==7){
								classNum_data.tea_nick  = '20：15-21：45';
							};
							
							classList_num.push(classNum_data);
						}
						
					}
					
				}
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
		})
	};
	
	
	lesson_ajax(0);
	var inde=0;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
})