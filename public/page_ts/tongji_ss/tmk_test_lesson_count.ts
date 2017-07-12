/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-tmk_test_lesson_count.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			subject:	$('#id_subject').val()
        });
    }


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
   
    Enum_map.append_option_list("subject",$("#id_subject")); 

	$('#id_subject').val(g_args.subject);
	$('.opt-change').set_input_change_event(load_data);
    /*$.each($(".l-2,.l-3,.l-4"),function(){       
        $(this).hide();
       
    });*/


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
        if (!$this.data("show") ==true) {
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
        if (!$this.data("show") ==true) {
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
        if (!$this.data("show") ==true) {
            $(".account."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".account."+class_name ).parent(".l-4");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });


   
    $(".id_all_count,.id_success_count,.id_fail_count").each(function(){
        var opt_data=$(this).parent().parent().find(".row-data").get_self_opt_data();
        if(opt_data.level != "l-4"){
            $(this).removeAttr("href").css("color","#333");
        }else{
            $(this).on("click",function(){
                var data = $(this).attr("data");
                if(data=="all_count"){
                    var success_flag = -1;
                }else if(data=="success_count"){
                    var success_flag = -2;
                }else if(data=="fail_count"){
                    var success_flag = 2;
                }
	            $.wopen("../seller_student_new2/test_lesson_plan_list?date_type=4&opt_date_type="+g_args.opt_date_type+"&start_time="+g_args.start_time+"&end_time="+g_args.end_time+"&grade=-1&subject=-1&test_lesson_student_status=-1&lessonid=undefined&userid=-1&teacherid=-1&success_flag="+success_flag+"&require_admin_type=-1&require_adminid=-1&tmk_adminid="+opt_data.adminid+"&is_test_user=0&test_lesson_fail_flag=-1&accept_flag=-2&seller_groupid_ex=&ass_test_lesson_type=-1 ");
            });

        }
    });








});


    


