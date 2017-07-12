$(function(){
    $("#id_add_placeholder").css({
        "height" : 400
    });

	function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
	}


    var data = [];
    $.each(g_data_list,function(i,item){
        if (item.praise_num>0) {
 		    data.push({
			    label: item.type_str,
			    data: item.praise_num
		    });
        }
	}) ;



    $.plot('#id_add_placeholder', data, {
        series: {
            pie: {
                show: true,
                radius: 1,
                tilt: 0.5,
                label: {
                    show: true,
                    radius: 1,
                    formatter: labelFormatter,
                    background: {
                        opacity: 0.8
                    }
                },
                combine: {
                    color: '#999',
                    threshold: 0.1
                }
            }
        },
        legend: {
            show: false
        }
    });
    


    Enum_map.append_option_list("praise", $("#id_praise_type"),true);
    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $('#id_praise_type').val(g_args.praise_type);

    function load_data(){
        var start_date  = $("#id_start_date").val();
        var end_date    = $("#id_end_date").val();
        var praise_type = $("#id_praise_type").val();
        reload_self_page({
            start_date : start_date,
            end_date   : end_date,
            praise_type: praise_type
        });
	}

    $(".opt-change").on("change",function(){
        load_data();
    });
    
	//TODO
	//时间控件
	$('#id_start_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
	    onChangeDateTime :function(){
		    load_data();
        }
	});
	$('#id_end_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		onChangeDateTime :function(){
		    load_data();
        }
	});




	//时间控件-over
    //回访记录
    $(".opt-type-info").on("click",function(){
        var type=$(this).get_opt_data("type");
		$.ajax({
			type     :"post",
			url      :"/user_manage/get_mypraise_info",
			dataType :"json",
			data     :{"type":type},
			success  : function(result){
				var html_str="<table class=\"table table-bordered table-striped\"  > ";
                html_str+=" <tr><th> 时间 <th> 学生账号 <th> 课堂id <th> 获赞数目 <th>内容 </tr>   ";
                
				$.each( result.praise_list ,function(i,item){
					html_str=html_str+"<tr><td>"+item.ts+"</td><td>"+item.userid+"</td><td>"+ item.lessonid+"</td><td>"+item.praise_num+"</td><td>"+item.reason+" </td></tr>";
				} );
                
                BootstrapDialog.show({
                    title: '获赞记录',
                    message :  html_str , 
                    closable: false, 
                    buttons: [{
                        label: '返回',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }]
                }); 
			  }
		  });
	  });

	$('.opt-type').on('click',function(){
        var type       = $(this).get_opt_data("type");
        var start_date = $("#id_start_date").val();
        var end_date   = $('#id_end_date').val();
        window.open(
            '/user_manage/zan_info?praise_type='+ type+'&start_date='+start_date+'&end_date='+end_date
        );
	});
	

    
});
