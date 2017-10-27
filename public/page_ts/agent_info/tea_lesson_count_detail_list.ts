/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-tea_lesson_count_detail_list.d.ts" />
function load_data( ){
    var start_time = $("#id_start_time").val();
    var end_time   = $("#id_end_time").val();
    
    $.reload_self_page({
        "year" : $("#id_year").val(),
        "month" : $("#id_month").val()
    });
}

var notify_cur_playpostion =null;

$(function(){
   

    $('#id_year').val(g_args.year );
    $('#id_month').val(g_args.month);

	$(".opt-change").on("change",function(){
		load_data();
	});

    /**
     *
     $.each($(".key2"),function(){
     var $this=$(this);
     if($.trim($this.text())!=""  ) {
     $this.parent().hide();
     }
     });
    */

    var link_css = {
        color: "#3c8dbc",
        cursor:"pointer"
    };

    $(".l-1 .key1").css(link_css);
    $(".l-2 .key2").css(link_css);
//    $(".l-3 .key3").css(link_css);

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



});
