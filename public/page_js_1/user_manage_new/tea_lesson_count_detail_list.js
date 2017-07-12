function load_data( ){
    var start_time = $("#id_start_time").val();
    var end_time   = $("#id_end_time").val();
    
    reload_self_page({
        "start_time" : start_time,
        "end_time" : end_time,
        "studentid" : $("#id_studentid").val(),
        "teacherid" : $("#id_teacherid").val()
    });
}

$(function(){
   

    $('#id_start_time').val(g_args.start_time);
    $('#id_end_time').val(g_args.end_time);
    $('#id_origin').val(g_args.origin);
    $('#id_origin_ex').val(g_args.origin_ex);
    $('#id_teacherid').val(g_args.teacherid);
    $('#id_studentid').val(g_args.studentid);

    $.each($(".key2"),function(){
        var $this=$(this);
        if($.trim($this.text())!=""  ) {
            $this.parent().hide();
        }
    });

    var link_css=        {
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




    set_input_enter_event($("#id_origin"),function(){
        load_data();
    });
    
	//时间控件
	$('#id_start_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
	    onChangeDateTime :function(){
		    load_data();
        }
	});
    
	$('#id_end_time').datetimepicker({
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

    admin_select_user( $("#id_teacherid"), "teacher",  load_data, true) ;
    admin_select_user( $("#id_studentid"), "student",  load_data, false) ;

    
    $("#id_reset_already_lesson_count").on("click",function(){
	    //
        do_ajax("/user_deal/reset_already_lesson_count",{
            "teacherid"    :$("#id_teacherid").val(),
            "start_time"    :$("#id_start_time").val(),
            "end_time"    :$("#id_end_time").val()
        });
	    
    });


});
