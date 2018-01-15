/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_simulate-tea_simulate_wages_info.d.ts" />

$(function(){
    var notify_cur_playpostion =null;
    function load_data(){
        $.reload_self_page ( {
			      date_type_config : $('#id_date_type_config').val(),
			      date_type        : $('#id_date_type').val(),
			      opt_date_type    : $('#id_opt_date_type').val(),
			      start_time       : $('#id_start_time').val(),
			      end_time         : $('#id_end_time').val(),
			      teacherid        : $('#id_teacherid').val(),
			      studentid        : $('#id_studentid').val(),
			      show_type        : $('#id_show_type').val(),
        });
    }

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery          : function() {
            load_data();
        }
    });

	  $('#id_teacherid').val(g_args.teacherid);
	  $('#id_studentid').val(g_args.studentid);
	  $('#id_show_type').val(g_args.show_type);
	  $('.opt-change').set_input_change_event(load_data);

    var link_css = {
        color  : "#3c8dbc",
        cursor : "pointer"
    };

    $(".l-1 .key1").css(link_css);
    $(".l-2 .key2").css(link_css);
    $(".l-1 .key1").on("click",function(){
        var $this      = $(this);
        var class_name = $this.data("class_name");
        if ($this.data("show") == true) {
            $(".key2."+class_name ).parent().hide();
        }else{
            var $opt_item = $(".key2."+class_name ).parent(".l-2");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });

    $(".l-2 .key2").on("click",function(){
        var $this      = $(this);
        var class_name = $this.data("class_name");
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
        var $this      = $(this);
        var class_name = $this.data("class_name");
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

    $.admin_select_user( $("#id_teacherid"), "teacher",  load_data, true) ;
    $.admin_select_user( $("#id_studentid"), "student",  load_data, false) ;

    $(".opt-div").each(function() {
        var $this=$(this) ;
        if (!$this.data("lessonid")) {
            $(this).hide();
        }
    });

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery          : function() {
            load_data();
        }
    });

    $(".teacher_reward_list").on("click",function(){
        var teacherid  = g_args.teacherid;
        var start_time = g_args.start_time;
        var url = "/user_manage_new/teacher_trial_reward_list?teacherid="+teacherid+"&start_time="+start_time;

        window.open(url,"_blank");
    });


});
