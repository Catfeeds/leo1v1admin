/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_origin_info_by_order.d.ts" />
function load_data(){
    $.reload_self_page ( {
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		origin:	$('#id_origin').val(),
		origin_ex:	$('#id_origin_ex').val(),
        seller_groupid_ex:	$('#id_seller_groupid_ex').val()
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
	$('#id_origin').val(g_args.origin);
	$('#id_origin_ex').val(g_args.origin_ex);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);

    $("#id_seller_groupid_ex").init_seller_groupid_ex();

    $.each($(".key2"),function(){
        var $this=$(this);
        if($.trim($this.text()) !="") {
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


	$('.opt-change').set_input_change_event(load_data);
});


