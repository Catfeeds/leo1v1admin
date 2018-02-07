/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-assistant_admin_member_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val()

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
    $("#id_opt_date_type").hide();
    $(".opt-ass-month-target").on("click",function(){
        var $this= $(this);
        var opt_data = $(this).get_opt_data();
       // alert(opt_data.adminid);
        var lesson_target = opt_data.lesson_target;
        var o_m = $this.parent().parent().parent().find(".lesson_target").text();
        if(lesson_target != o_m ){
            lesson_target = o_m;
        }
        var id_lesson_target=$("<input/>");
        var id_renew_target=$("<input/>");
        var id_group_renew_target=$("<input/>");
        var id_all_renew_target=$("<input/>");
        id_lesson_target.val(g_args.rate_target);
        var arr=[
            ["目标系数", id_lesson_target],          
            ["个人目标续费值", id_renew_target],          
            ["团队目标续费值", id_group_renew_target],          
            ["总体目标续费值", id_all_renew_target],          
        ];
        id_renew_target.val(g_args.renew_target);
        id_group_renew_target.val(g_args.group_renew_target);
        id_all_renew_target.val(g_args.all_renew_target);
        $.show_key_value_table("编辑", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                $.do_ajax( '/user_deal/set_ass_month_target',{
                    "month" : g_args.start_time,
                    "lesson_target" : id_lesson_target.val(),
                    "renew_target"  : id_renew_target.val()*100,
                    "group_renew_target"  : id_group_renew_target.val()*100,
                    "all_renew_target"  : id_all_renew_target.val()*100
                });
                
            }
        });


    });
    



    $.each($(".l-2,.l-3,.l-4"),function(){       
        $(this).hide();
       
    });
    $.each($(".l-0,.l-2,.l-3,.l-4"),function(){       
        $(this).find(".opt-ass-month-target").hide();        
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


    $(".opt-show-change-list").on("opt-show-change-list",function(){
        var title = "更改记录";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>时间</td><td>操作人</td><td>更改前</td><td>更改后</td><tr></table></div>");

        $.do_ajax('/ajax_deal3/get_assistant_target_change_list',{
            "month" : g_args.start_time
        },function(resp) {
            var list = resp.data;
            $.each(list,function(i,item){               
                html_node.find("table").append("<tr><td>"+item["time_str"]+"</td><td>"+item["acc"]+"</td><td>"+item["old_str"]+"</td><td>"+item["new_str"]+"</td></tr>");
            });
        });

        var dlg=BootstrapDialog.show({
            title:title, 
            message :  html_node   ,
            closable: true, 
            buttons:[{
                label: '返回',
                cssClass: 'btn',
                action: function(dialog) {
                    dialog.close();

                }
            }],
            onshown:function(){
                
            }

        });

        dlg.getModalDialog().css("width","1024px");
 
    });
   
 
	$('.opt-change').set_input_change_event(load_data);
});

