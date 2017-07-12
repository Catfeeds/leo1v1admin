/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-seller_user_count.d.ts" />

    function load_data(){
        $.reload_self_page ( {
        order_by_str: g_args.order_by_str,
      admin_revisiterid:	$('#id_admin_revisiterid').val(),
      groupid:	$('#id_groupid').val(),
      date_type:	$('#id_date_type').val(),
      opt_date_type:	$('#id_opt_date_type').val(),
      start_time:	$('#id_start_time').val(),
      end_time:	$('#id_end_time').val()
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
  $('#id_admin_revisiterid').val(g_args.admin_revisiterid);
  $('#id_groupid').val(g_args.groupid);
    $.admin_select_user(
        $('#id_admin_revisiterid'),
        "admin", load_data ,false, {
            "main_type": 2 //销售
        }
    );



  $('.opt-change').set_input_change_event(load_data);


    $(".require_test_lesson_count").on("click",function(){
        var opt_data= $(this).get_row_opt_data();
        var date_str=$.get_page_select_date_str();
        var admin_revisiterid=opt_data.admin_revisiterid;

        $.wopen("/seller_student/student_list?" +date_str
                + "&date_type=3"
                + "&test_lesson_cancel_flag=-2"
                + "&admin_revisiterid=" + admin_revisiterid
                );
    });


    $(".seller_test_lesson_count").on("click",function(){
        var opt_data= $(this).get_row_opt_data();
        var date_str=$.get_page_select_date_str();
        var admin_revisiterid=opt_data.admin_revisiterid;
        $.wopen("/seller_student/test_lesson_list?" +date_str
                + "&date_type=4"
                + "&test_lesson_cancel_flag=-2"
                + "&st_application_nick=" + opt_data.nick
                );
    });
    $(".test_lesson_count_succ").on("click",function(){
        var opt_data= $(this).get_row_opt_data();
        var date_str=$.get_page_select_date_str();
        var admin_revisiterid=opt_data.admin_revisiterid;
        $.wopen("/seller_student/test_lesson_list?" +date_str
                + "&date_type=4"
                + "&status=-4"
                + "&st_application_nick=" + opt_data.nick
                );
    });

    if(self_groupid != 0) {
        $("#id_groupid").parent().parent().hide();
        $("#id_admin_revisiterid").parent().parent().hide();
    }



    $.each($(".l-2,.l-3,.l-4"),function(){
        $(this).hide();

    });


    var link_css=        {
        color: "#3c8dbc",
        cursor:"pointer"
    };

    $(".l-1 .main_type").css(link_css);
    $(".l-2 .up_group_name").css(link_css);
    $(".l-3 .group_name").css(link_css);

    $(".l-1 .main_type").on("click",function(){

        var $this=$(this);
        var class_name= $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".up_group_name."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".up_group_name."+class_name ).parent(".l-2");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );

    });

    $(".l-2 .up_group_name").on("click",function(){
        var $this=$(this);
        var class_name= $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".group_name."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".group_name."+class_name ).parent(".l-3");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });

    $(".l-3 .group_name").on("click",function(){
        var $this=$(this);
        var class_name= $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".account."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".account."+class_name ).parent(".l-4");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });



});
