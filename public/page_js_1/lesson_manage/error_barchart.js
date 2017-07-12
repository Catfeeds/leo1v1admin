$(function(){
    $.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;
    };

    var start_date = $.getUrlParam('start_date');
    var end_date   = $.getUrlParam('end_date');
    var title = start_date+'到'+end_date+'的错误数据';
    $("#title").text(title);
    var canvas_start = function(){
        do_ajax( "/lesson_manage/get_error_barchart",{
            "start_date" : start_date,
            "end_date"   : end_date
        }, function(result){
            var error=new Array;
            var html_node = '';
            
            $.each(result.error,function(i,item){
                error[i]=item['val'];
                if(item['type_name']!=null){
                    html_node +='<tr><td>'+item['type_name']+'</td><td>'+item['val']+'</td></tr>';
                }
            });
            $("#error_list").append(html_node);
            
		    var barChartData = {
		        labels : [
                    "学生听不到老师声音",
                    "老师听不到学生声音",
                    "学生卡在加载语音服务",
                    "老师卡在加载语音服务",
                    "学生卡在加载白板",
                    "老师卡在加载白板",
                    "学生白板无法显示图片",
                    "老师白板无法显示图片",
                    "进出白板次数过多",
                    "网络错误1001",
                    "声音卡顿",
                    "老师无法加载讲义",
                    "学生无法加载讲义",
                    "白板中出现遗留或莫名图片划线",
                    "其他错误"
                ],
		        datasets : [
			        {
				        fillColor : "rgba(120,220,220,0.5)",
				        strokeColor : "rgba(220,220,220,0.8)",
				        highlightFill: "rgba(220,220,220,0.75)",
				        highlightStroke: "rgba(220,220,220,1)",
				        data : [
                            error[1],error[2],error[3],error[4],
                            error[5],error[6],error[7],error[8],
                            error[9],error[10],error[11],error[12],
                            error[13],error[14],error['other']
                        ]
			        }
		        ]
	        };
	        var ctx = document.getElementById("canvas").getContext("2d");
		    window.myBar = new Chart(ctx).Bar(barChartData, {responsive : true});
        });
    };
    canvas_start();
});
