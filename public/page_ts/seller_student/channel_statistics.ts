/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-channel_statistics.d.ts" />

function load_data( ){
    
    $.reload_self_page({
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
        "origin" : $("#id_origin").val(),
        "origin_ex" : $("#id_origin_ex").val(),
        "admin_revisiterid" : $("#id_admin_revisiterid").val(),
        "groupid":$("#id_groupid").val()
    });
}

$(function(){

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config), 
        onQuery :function() {
            load_data();
        }
    });

    $("#id_subject_pic,#id_has_pad_pic,#id_grade_pic,#id_area_pic").css({
        "height" : 400
    });

	function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
	}


    var gen_data=function ( map, field_str,id_str) {
        var data = [];
        var tbody=$("#"+id_str).parent().find("tbody");
        

        var objectList = new Array();
        $.each(map,function(i,v){
            var desc="";
            if (field_str) {
                desc=Enum_map.get_desc(field_str,i) ;
            }else{
                desc=i;
                i="";
            }
 		    data.push({
			    label: desc,
			    data: v 
		    });
            objectList.push([v, "<tr> <td> "+i+"</td>   <td> "+desc+" </td>  <td> "+v+" </td></tr>" ]) ;

	    }) ;
        objectList.sort(function(a,b){
            return b[0]-a[0];
        });
        $.each(objectList,function(i,item){
            tbody.append(item[1]);
        });


        $.plot('#'+id_str, data, {
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

    };
    gen_data( g_subject_map,"subject","id_subject_pic");
    gen_data( g_grade_map,"grade","id_grade_pic");
    gen_data( g_has_pad_map,"pad_type","id_has_pad_pic");
    gen_data( g_area_map,"","id_area_pic");


    

   
    $('#id_origin').val(g_args.origin);
    $('#id_origin_ex').val(g_args.origin_ex);
    $.each($(".key2"),function(){
        var $this=$(this);
        if($.trim($this.text())!=""  ) {
            $this.parent().hide();
        }
    });


    $("#id_admin_revisiterid").val(g_args.admin_revisiterid);
    $("#id_groupid").val(g_args.groupid);
    $.admin_select_user(
        $('#id_admin_revisiterid'),
        "admin", load_data ,false, {
            "main_type": 2,
            select_btn_config: [{
                "label": "[未分配]",
                "value": 0 
            }]
        }
    );


    var link_css=        {
            color: "#3c8dbc",
            cursor:"pointer"
    };
   

    $(".l-1 .key1").css(link_css);
    $(".l-2 .key2").css(link_css);
    $(".l-3 .key3").css(link_css);

    $(".l-1 .key1").on("click",function(){
        var $this=$(this);
        var class_name= $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".key2."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".key2."+class_name ).parent(".l-2");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );

    });

    $(".l-2 .key2").on("click",function(){
        var $this=$(this);
        var class_name= $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".key3."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".key3."+class_name ).parent(".l-3");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });

    $(".l-3 .key3").on("click",function(){
        var $this=$(this);
        var class_name= $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".key4."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".key4."+class_name ).parent(".l-4");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });




    
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

    
	$(".opt-change").on("change",function(){
		load_data();
	});

});
