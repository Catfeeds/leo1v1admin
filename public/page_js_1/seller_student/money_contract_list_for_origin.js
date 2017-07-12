function load_data( ){
    var start_date = $("#id_start_date").val();
    var end_date   = $("#id_end_date").val();
    
    reload_self_page({
        "start_date" : start_date,
        "end_date" : end_date,
        "origin" : $("#id_origin").val(),
        "origin_ex" : $("#id_origin_ex").val()
    });
}

$(function(){
   
    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $('#id_origin').val(g_args.origin);
    $('#id_origin_ex').val(g_args.origin_ex);
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




    set_input_enter_event($("#id_origin"),function(){
        load_data();
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
