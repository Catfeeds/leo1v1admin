// JavaScript Document
$(function(){
	
	var QueryString = function() {
		// Usage: QueryString.tid
		// This function is anonymous, is executed immediately and
		// the return value is assigned to QueryString!
		var query_string = {};
		var query = window.location.search.substring(1);
		var vars = query.split("&");
		for (var i = 0; i < vars.length; i++) {
			var pair = vars[i].split("=");
			// If first entry with this name
			if ( typeof query_string[pair[0]] === "undefined") {
				query_string[pair[0]] = pair[1];
				// If second entry with this namepay_success
			} else if ( typeof query_string[pair[0]] === "string") {
				var arr = [query_string[pair[0]], pair[1]];
				query_string[pair[0]] = arr;
				// If third or later entry with this name
			} else {
				query_string[pair[0]].push(pair[1]);
			}
		}
		return query_string;
	}();
	
	//初始页面刷新请求数据
	var userid=QueryString.sid;//从url中获取学生id

	/*
	$.ajax({
		url: 'http://adminapi.weiyi.com/stu_manage/get_stu_info',
		type: 'POST',
		data: {'userid': userid},
		dataType: 'jsonp',
		success: function(data) {
			if (data['ret'] == 0) {
				var userid_num = [];
				var idNum_data = new Object();
				idNum_data.face   	  	= data['student_info'].face;			//头像地址
				idNum_data.courseid		= data['student_info'].courseid;		//课程id
				idNum_data.nick		   	= data['student_info'].nick;			//昵称
				idNum_data.parent_name 	= data['student_info'].parent_name;		//家长姓名
				idNum_data.phone   	   	= data['student_info'].phone;			//手机号
				idNum_data.address     	= data['student_info'].address;			//收货地址
				idNum_data.school      	= data['student_info'].school;			//学校名称
				idNum_data.ass_nick   	= data['student_info'].ass_nick;		//助教昵称
				idNum_data.tea_nick   	= data['student_info'].tea_nick;		//老师昵称
				idNum_data.lesson_total = data['student_info'].lesson_total;	//所有的课次数
				idNum_data.lesson_left  = data['student_info'].lesson_left;		//剩余课时数
				idNum_data.praise   	= data['student_info'].praise;			//点赞个数
				idNum_data.course_grade = data['student_info'].course_grade;	//课程年级
				idNum_data.requirement	= data['student_info'].requirement;		//排课需求
				
				idNum_data.textbook	= data['student_info'].textbook;			//教材版本
				
				
				if(data['student_info'].parent_type==1){						//家长与学生之间的关系
					idNum_data.relation ='父亲';
				}else if(data['student_info'].parent_type==2){
					idNum_data.relation ='母亲';
				}else if(data['student_info'].parent_type==3){
					idNum_data.relation ='爷爷';
				}else if(data['student_info'].parent_type==4){
					idNum_data.relation ='奶奶';
				}else if(data['student_info'].parent_type==5){
					idNum_data.relation ='外公';
				}else if(data['student_info'].parent_type==6){
					idNum_data.relation ='外婆';
				}else if(data['student_info'].parent_type==7){
					idNum_data.relation ='其他';
				};
				
					
				if(data['student_info'].grade==101){							//年级
					idNum_data.grade ='小一';
				}else if(data['student_info'].grade==102){
					idNum_data.grade ='小二';
				}else if(data['student_info'].grade==103){
					idNum_data.grade ='小三';
				}else if(data['student_info'].grade==104){
					idNum_data.grade ='小四';
				}else if(data['student_info'].grade==105){
					idNum_data.grade ='小五';
				}else if(data['student_info'].grade==106){
					idNum_data.grade ='小六';
				}else if(data['student_info'].grade==201){
					idNum_data.grade ='初一';
				}else if(data['student_info'].grade==202){
					idNum_data.grade ='初二';
				}else if(data['student_info'].grade==203){
					idNum_data.grade ='初三';
				}else if(data['student_info'].grade==301){
					idNum_data.grade ='高一';
				}else if(data['student_info'].grade==302){
					idNum_data.grade ='高二';
				}else if(data['student_info'].grade==303){
					idNum_data.grade ='高三';
				}
				
				
				if(data['student_info'].course_status==0){						//学程状态
					idNum_data.status ='未联系';
				}else if(data['student_info'].course_status==1){
					idNum_data.status ='待付款';
				}else if(data['student_info'].course_status==2){
					idNum_data.status ='待分配老师';
				}else if(data['student_info'].course_status==3){
					idNum_data.status ='待排课';
				}else if(data['student_info'].course_status==4){
					idNum_data.status ='正常上课';
				}else if(data['student_info'].course_status==5){
					idNum_data.status ='提出退费申请';
				}else if(data['student_info'].course_status==6){
					idNum_data.status ='退费成功';
				}
				
				userid_num.push(idNum_data);
				
				console.log(userid_num);
			
				$('.myself').empty();
				
				$('#user_info').find('tr').eq(0).find('.myself').html(idNum_data.nick);
				$('#user_info').find('tr').eq(1).find('.myself').html(idNum_data.parent_name);
				$('#user_info').find('tr').eq(1).find('.myself02').html(idNum_data.relation);
				$('#user_info').find('tr').eq(2).find('.myself').html(idNum_data.phone);
				$('#user_info').find('tr').eq(3).find('.myself').html(idNum_data.address);
				$('#user_info').find('tr').eq(4).find('.myself').html(idNum_data.school);
				
				$('#user_info02').find('tr').eq(0).find('span').eq(0).html(idNum_data.grade);
				$('#user_info02').find('tr').eq(0).find('span').eq(1).html(idNum_data.status);
				$('#user_info02').find('tr').eq(1).find('span').eq(0).html(idNum_data.grade);
				$('#user_info02').find('tr').eq(1).find('span').eq(1).html(idNum_data.ass_nick);
				$('#user_info02').find('tr').eq(2).find('span').eq(0).html(idNum_data.lesson_total);
				$('#user_info02').find('tr').eq(2).find('span').eq(1).html(idNum_data.tea_nick);
				$('#user_info02').find('tr').eq(3).find('span').eq(0).html(idNum_data.lesson_left);
				$('#user_info02').find('tr').eq(3).find('span').eq(1).html(idNum_data.praise);
				$('#user_info02').find('tr').eq(4).find('span').eq(0).html(idNum_data.requirement);
				
				$('#couseId').val(idNum_data.courseid);//储存课程id
			}
		}
	})
*/


/*
	//修改个人信息
	$('.load_editor').live('click',function(){
		var stu_nick	= $('#user_info').find('.edit_b').eq(0).val();	//昵称
		var parent_name	= $('#user_info').find('.edit_b').eq(1).val();	//家长姓名
		var phone		= $('#user_info').find('.edit_b').eq(2).val();	//联系电话
		var address		= $('#user_info').find('.edit_b').eq(3).val();	//地址
		var school		= $('#user_info').find('.edit_b').eq(4).val();	//学校
		var parent_type = $("#relationship option:selected").text();	//关系
		var textbook	= $("#textbook option:selected").text();		//教材版本
		if(parent_type=='父亲'){
			parent_type=1;
		}else if(parent_type=='母亲'){
			parent_type=2;
		}else if(parent_type=='爷爷'){
			parent_type=3;
		}else if(parent_type=='奶奶'){
			parent_type=4;
		}else if(parent_type=='外公'){
			parent_type=5;
		}else if(parent_type=='外婆'){
			parent_type=6;
		}else if(parent_type=='其他'){
			parent_type=7;
		}
		
		$.ajax({
			url: 'http://adminapi.weiyi.com/stu_manage/change_stu_info',
			type: 'POST',
			data: {'studentid': userid,'stu_nick': stu_nick,'parent_name': parent_name,'phone': phone,'address': address,'school': school,'parent_type': parent_type,'textbook':textbook},
			dataType: 'jsonp',
			success: function(data) {
				if (data['ret'] == 0) {
					console.log('success')
				}
				
			}
		});
		
	});

*/


	//更改排课需求
	$('.ss').click(function(){
		var requirement=$(this).siblings('.edit_b03').val();
		var courseid=$('#couseId').val();
		//console.log(requirement+courseid);
		$.ajax({
			url: 'http://adminapi.weiyi.com/course_manage/change_requirement',
			type: 'POST',
			data: {'courseid': courseid,'requirement': requirement},
			dataType: 'jsonp',
			success: function(data) {
				if (data['ret'] == 0) {
					console.log('successId')
				}
				
			}
		});
	});










})
	









































