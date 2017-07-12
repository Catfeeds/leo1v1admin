// JavaScript Document
$(function(){
	
	//各地区学生分布
	var ctx1 = document.getElementById("myChart1").getContext("2d");
	var data = {
		labels : ["北京","上海","天津","重庆","广东","江苏","浙江","山东","山西","河南","河北","湖南","湖北","广西","陕西","安徽","四川","福建","贵州","海南","青海","甘肃","云南"],
		datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,1)",
				data : [65,59,90,81,56,55,40,59,90,81,56,55,40,59,90,81,56,55,40,81,56,55,40]
			}
		]
	};
		new Chart(ctx1).Bar(data,{
							
		scaleOverlay : false,
		
		scaleOverride : false,
		
		scaleSteps : null,
		
		scaleStepWidth : null,
		
		scaleStartValue : null,
	
		scaleLineColor : "rgba(0,0,0,.1)",
		
		scaleLineWidth : 1,
	
		scaleShowLabels : true,
		
		scaleLabel : "<%=value%>",
		
		scaleFontFamily : "'Arial'",
		
		scaleFontSize : 12,
		
		scaleFontStyle : "normal",
		
		scaleFontColor : "#666",	
		
		scaleShowGridLines : true,
		
		scaleGridLineColor : "rgba(0,0,0,.05)",
		
		scaleGridLineWidth : 1,	
	
		barShowStroke : true,
		
		barStrokeWidth : 2,
		
		barValueSpacing : 5,
		
		barDatasetSpacing : 1,
		
		animation : true,
	
		animationSteps : 60,
		
		animationEasing : "easeInOutQuart",
	
		onAnimationComplete :null
		
	});
	
	//各月课时消耗
	var ctx2 = document.getElementById("myChart2").getContext("2d");
	var data = {
		labels : ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
		datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,1)",
				data : [28,48,40,19,96,27,100,90,81,56,55,40]
			}
		]
	};
		new Chart(ctx2).Bar(data,{
							
		scaleOverlay : false,
		
		scaleOverride : false,
		
		scaleSteps : null,
		
		scaleStepWidth : null,
		
		scaleStartValue : null,
	
		scaleLineColor : "rgba(0,0,0,.1)",
		
		scaleLineWidth : 1,
	
		scaleShowLabels : true,
		
		scaleLabel : "<%=value%>",
		
		scaleFontFamily : "'Arial'",
		
		scaleFontSize : 12,
		
		scaleFontStyle : "normal",
		
		scaleFontColor : "#666",	
		
		scaleShowGridLines : true,
		
		scaleGridLineColor : "rgba(0,0,0,.05)",
		
		scaleGridLineWidth : 1,	
	
		barShowStroke : true,
		
		barStrokeWidth : 2,
		
		barValueSpacing : 5,
		
		barDatasetSpacing : 1,
		
		animation : true,
	
		animationSteps : 60,
		
		animationEasing : "easeInOutQuart",
	
		onAnimationComplete :null
		
	});
	
	
	//各年级人数分布
	var ctx3 = document.getElementById("myChart3").getContext("2d");
	var data = [
		{
			value: 30,
			color:"#E4EBEB",
			label: "六年级"
		},
		{
			value : 50,
			color : "#EDDDD3",
			label: "七年级"
		},
		{
			value : 80,
			color : "#DB968A",
			label: "八年级"
		},
		{
			value : 100,
			color : "#B6D6DB",
			label: "九年级"
		}		
	];
	new Chart(ctx3).Pie(data,{
		segmentShowStroke : true,
		
		segmentStrokeColor : "#fff",
		
		segmentStrokeWidth : 2,
		
		animation : true,
		
		animationSteps : 100,
		
		animationEasing : "easeOutBounce",
		
		animateRotate : true,
	
		animateScale : false,
		
		onAnimationComplete : null
	});
	
	
	
	
})