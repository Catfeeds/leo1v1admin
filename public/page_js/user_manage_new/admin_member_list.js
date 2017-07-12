/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-admin_member_list.d.ts" />

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
    $(".opt-seller-month-money").on("click",function(){
        var $this= $(this);
        var opt_data = $(this).get_opt_data();
        var old_month_money = opt_data.month_money;
        var o_m = $this.parent().parent().parent().find(".month_money").text();
        if(old_month_money != o_m ){
            old_month_money = o_m;
        }

        var id_month_money=$("<input/>");
        id_month_money.val(old_month_money);
        var arr=[
            ["金额", id_month_money],
        ];

        $.show_key_value_table("编辑月度目标额", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                $.do_ajax( '/user_deal/set_seller_month_money',
                           {
                               "groupid" : opt_data.groupid,
                               "month" : g_args.start_time,
                               "month_money" : id_month_money.val(),
                           },function(res){
                               var money_now = res.data;
                               $this.parent().parent().parent().find(".month_money").text(money_now);
                               var prev_l_2 = $($this.parent().parent().parent().prevAll(".l-2")[0]).find(".month_money");
                               var prev_l_2_money = prev_l_2.text();
                               prev_l_2.text(parseInt(prev_l_2_money)+parseInt(money_now)-parseInt(old_month_money));
                               var prev_l_1 = $($this.parent().parent().parent().prevAll(".l-1")[0]).find(".month_money");
                               var prev_l_1_money = prev_l_1.text();
                               prev_l_1.text(parseInt(prev_l_1_money)+parseInt(money_now)-parseInt(old_month_money));

                               var all_money = $(".l-0").find(".month_money").text();
                               var all_money_now = parseInt(all_money)+parseInt(money_now)-parseInt(old_month_money);
                               $(".l-0").find(".month_money").text(all_money_now);

                               dialog.close();
                           });

            }
        });


    });

    $(".opt-seller-personal-money").on("click",function(){
        var $this= $(this);
        var opt_data = $(this).get_opt_data();
        console.log(opt_data.adminid);

        var old_personal_money = opt_data.personal_money;
        var o_m = $this.parent().parent().parent().find(".personal_money").text();
        if(old_personal_money != o_m ){
            old_personal_money = o_m;
        }
        var id_personal_money=$("<input/>");
        id_personal_money.val(old_personal_money);

        var id_test_lesson_count=$("<input/>");
        var old_personal_money = opt_data.personal_money;
        var old_test_lesson_count=   $this.parent().parent().parent().find(".test_lesson_count").text();
        id_test_lesson_count.val( old_test_lesson_count);

        var arr=[
            ["金额", id_personal_money],
            ["试听数", id_test_lesson_count ],

        ];
        $.show_key_value_table("编辑月度目标额", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                $.do_ajax( '/user_deal/set_seller_personal_money',
                           {
                               "adminid" : opt_data.adminid,
                               "month" : g_args.start_time,
                               "personal_money" : id_personal_money.val(),
                               "test_lesson_count" : id_test_lesson_count.val(),
                           },function(res){

                               var personal_money_now = res.personal_money ;
                               $this.parent().parent().parent().find(".personal_money").text(personal_money_now);
                               var prev_l_3 = $($this.parent().parent().parent().prevAll(".l-3")[0]).find(".personal_money");
                               var prev_l_3_money = prev_l_3.text();
                               prev_l_3.text(parseInt(prev_l_3_money)+parseInt(personal_money_now)-parseInt(old_personal_money));
                               var prev_l_2 = $($this.parent().parent().parent().prevAll(".l-2")[0]).find(".personal_money");
                               var prev_l_2_money = prev_l_2.text();
                               prev_l_2.text(parseInt(prev_l_2_money)+parseInt(personal_money_now)-parseInt(old_personal_money));

                               var prev_l_1 = $($this.parent().parent().parent().prevAll(".l-1")[0]).find(".personal_money");
                               var prev_l_1_money = prev_l_1.text();
                               prev_l_1.text(parseInt(prev_l_1_money)+parseInt(personal_money_now)-parseInt(old_personal_money));

                               var all_money = $(".l-0").find(".personal_money").text();
                               var all_money_now = parseInt(all_money)+parseInt(personal_money_now)-parseInt(old_personal_money);
                               $(".l-0").find(".personal_money").text(all_money_now);

                               //
                               var test_lesson_count_now = res.test_lesson_count ;
                               $this.parent().parent().parent().find(".test_lesson_count").text(test_lesson_count_now);
                               var prev_l_3 = $($this.parent().parent().parent().prevAll(".l-3")[0]).find(".test_lesson_count");
                               var prev_l_3_money = prev_l_3.text();
                               prev_l_3.text(parseInt(prev_l_3_money)+parseInt(test_lesson_count_now)-parseInt(old_test_lesson_count));
                               var prev_l_2 = $($this.parent().parent().parent().prevAll(".l-2")[0]).find(".test_lesson_count");
                               var prev_l_2_money = prev_l_2.text();
                               prev_l_2.text(parseInt(prev_l_2_money)+parseInt(test_lesson_count_now)-parseInt(old_test_lesson_count));

                               var prev_l_1 = $($this.parent().parent().parent().prevAll(".l-1")[0]).find(".test_lesson_count");
                               var prev_l_1_money = prev_l_1.text();
                               prev_l_1.text(parseInt(prev_l_1_money)+parseInt(test_lesson_count_now)-parseInt(old_test_lesson_count));

                               var all_money = $(".l-0").find(".test_lesson_count").text();
                               var all_money_now = parseInt(all_money)+parseInt(test_lesson_count_now)-parseInt(old_test_lesson_count);
                               $(".l-0").find(".test_lesson_count").text(all_money_now);


                               dialog.close();

                           });

            }
        });


    });

    $(".opt-seller-personal-money,.opt-user").each(function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.level != "l-4" ){
            $(this).hide();
        }
    });
    $(".opt-seller-month-money").each(function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.level != "l-3" ){
            $(this).hide();
        }
    });

    $(".opt-edit-seller-time,.opt-edit").each(function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.level != "l-4" && opt_data.level != "l-3" ){
            $(this).hide();
        }
    });




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


    $(".opt-edit").on("click",function(i,item ){
        var title = "销售月度时间安排";
        var html_node= $("<div  id=\"div_table\"><div id=\"big_title\" style=\"font-size:30px;text-align:center\"></div><table width=\"100%\" height=\"300\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" id=\"cal_week\" style=\"margin-top:20px\"  > <tr id=\"week_head\"><th>周一 </th><th>周二 </th><th>周三 </th><th>周四 </th><th>周五 </th><th>周六 </th><th>周日 </th></tr><tbody  id=\"id_time_body_3\"  ><tr id=\"tr_list_1\"><td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr id=\"tr_list_2\"><td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr id=\"tr_list_3\"> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr id=\"tr_list_4\"> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr id=\"tr_list_5\"> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr id=\"tr_list_6\"> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr></tbody></table></div>");

        var init_title=function( id_name, start_time ) {
            start_time=start_time*1;
            var tr_list=$(html_node.find("#"+id_name+"> td"));
            for ( var i=0;i<7;i++) {
                var title=$.DateFormat(start_time+i*86400, "dd");
                $(tr_list[i]).text(title);
                var title_ex=$.DateFormat(start_time+i*86400, "yyyy-MM-dd");
                $(tr_list[i]).attr("tit",title_ex);
            }
        };
        var start_time=g_month_week_start;

        init_title("tr_list_1", start_time );
        init_title("tr_list_2", parseInt(start_time)+7*86400 );
        init_title("tr_list_3", parseInt(start_time)+14*86400 );
        init_title("tr_list_4", parseInt(start_time)+21*86400 );
        init_title("tr_list_5", parseInt(start_time)+28*86400 );
        init_title("tr_list_6", parseInt(start_time)+35*86400 );
        var num = $(html_node.find("#tr_list_4 > td")[0]).attr("tit").substr(5,2);
        html_node.find("#big_title").text($(html_node.find("#tr_list_4 > td")[0]).attr("tit").substr(0,7));

        var opt_data = $(this).get_opt_data();
        $.do_ajax('/user_manage_new/get_seller_month_time_js',{
            groupid : opt_data.groupid,
            adminid: opt_data.adminid,
            month: g_args.start_time
        },function(resp) {
            var month_time = resp.data;
            html_node.find("#cal_week tbody td").each(function() {
                if ($(this).attr("tit").substr(5,2) != num) {
                    $(this).css('color','#eee');
                }else{
                    $(this).css("cursor","pointer");
                }

            });

            $.each(month_time,function(i,item){
                var day = item[0].substr(0,10);
                var day_flag = item[0].substr(11,1);

                html_node.find("#cal_week tbody td").each(function() {
                    var $this=$(this);

                    if ($(this).attr("tit") == day ) {
                        if(day_flag ==1){
                            $(this).addClass("select_free_time");
                        }
                    }
                });

            });


        });



        if($(html_node.find("#tr_list_6 > td")[0]).attr("tit").substr(5,2) != num ){
            html_node.find("#tr_list_6").hide();
        }
        html_node.find("#cal_week tbody td").on("click",function(){
            if($(this).attr("tit").substr(5,2) == num){
                if ( $(this).hasClass("select_free_time")) {
                    $(this).removeClass("select_free_time");
                }else{
                    $(this).addClass("select_free_time");
                }
            }
        });

        var do_week = function(obj,arr){
            if(obj.attr("tit").substr(5,2) == num){
                arr.push(obj);
            }
            return arr;
        }
        $.each(html_node.find("#week_head > th"),function(i,item){
            $(this).css("cursor","pointer");
            $(this).on("click",function(){
                var arr=[];
                do_week($( html_node.find("#tr_list_1 > td")[i]),arr);
                do_week($( html_node.find("#tr_list_2 > td")[i]),arr);
                do_week($( html_node.find("#tr_list_3 > td")[i]),arr);
                do_week($( html_node.find("#tr_list_4 > td")[i]),arr);
                do_week($( html_node.find("#tr_list_5 > td")[i]),arr);
                do_week($( html_node.find("#tr_list_6 > td")[i]),arr);
                var len = arr.length;
                var j=0;
                for(var s in arr){
                    if(arr[s].hasClass("select_free_time")){
                        j =j+1;
                    };
                }
                if(j==len){
                    for(var s in arr){
                        if (arr[s].hasClass("select_free_time")) {
                            arr[s].removeClass("select_free_time");
                        }
                    }
                }else{
                    for(var s in arr){
                        if (!arr[s].hasClass("select_free_time")) {
                            arr[s].addClass("select_free_time");
                        }
                    }
                }
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
            },{
                label: '保存',
                cssClass: 'btn btn-warning',
                action: function(dialog)  {
                    var month_list=[];
                    html_node.find("#cal_week tbody td").each(function() {
                        var $this=$(this);

                        if ($(this).attr("tit").substr(5,2) == num) {
                            var tmp_date= $this.attr("tit");
                            if($(this).hasClass("select_free_time")){
                                month_list.push ([
                                    ""+tmp_date +":1"
                                ]);

                            }else{
                                month_list.push ([
                                    ""+tmp_date +":0"
                                ]);

                            }
                        }
                    });
                    $.do_ajax("/user_deal/update_seller_month_time",{
                        groupid : opt_data.groupid,
                        adminid: opt_data.adminid,
                        month: g_args.start_time,
                        month_time : JSON.stringify(month_list )
                    });

                }

            }],
            onshown:function(){

            }

        });

        dlg.getModalDialog().css("width","1024px");

    });

    $(".opt-user").on("click",function(i,item ){
        var title = "请假及加班设置";
        var html_node= $("<div  id=\"div_table\"><div><span style=\"width:50px;height:20px;background-color:red\">请假</span><span style=\"width:50px;height:20px;background-color:orange;margin-left:20px\">加班</span><span style=\"width:50px;height:20px;background-color:#17a6e8;margin-left:20px\">正常</span></div><div id=\"big_title\" style=\"font-size:30px;text-align:center\"></div><table width=\"100%\" height=\"300\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" id=\"cal_week\" style=\"margin-top:20px\"  > <tr id=\"week_head\"><th>周一 </th><th>周二 </th><th>周三 </th><th>周四 </th><th>周五 </th><th>周六 </th><th>周日 </th></tr><tbody  id=\"id_time_body_3\"  ><tr id=\"tr_list_1\"><td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr id=\"tr_list_2\"><td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr id=\"tr_list_3\"> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr id=\"tr_list_4\"> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr id=\"tr_list_5\"> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr id=\"tr_list_6\"> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr></tbody></table></div>");

        var init_title=function( id_name, start_time ) {
            start_time=start_time*1;
            var tr_list=$(html_node.find("#"+id_name+"> td"));
            for ( var i=0;i<7;i++) {
                var title=$.DateFormat(start_time+i*86400, "dd");
                $(tr_list[i]).text(title);
                var title_ex=$.DateFormat(start_time+i*86400, "yyyy-MM-dd");
                $(tr_list[i]).attr("tit",title_ex);
            }
        };
        var start_time=g_month_week_start;

        init_title("tr_list_1", start_time );
        init_title("tr_list_2", parseInt(start_time)+7*86400 );
        init_title("tr_list_3", parseInt(start_time)+14*86400 );
        init_title("tr_list_4", parseInt(start_time)+21*86400 );
        init_title("tr_list_5", parseInt(start_time)+28*86400 );
        init_title("tr_list_6", parseInt(start_time)+35*86400 );
        var num = $(html_node.find("#tr_list_4 > td")[0]).attr("tit").substr(5,2);
        html_node.find("#big_title").text($(html_node.find("#tr_list_4 > td")[0]).attr("tit").substr(0,7));

        var opt_data = $(this).get_opt_data();
        $.do_ajax('/user_manage_new/get_seller_month_time_js',{
            groupid : opt_data.groupid,
            adminid: opt_data.adminid,
            month: g_args.start_time
        },function(resp) {
            var month_time = resp.data;
            html_node.find("#cal_week tbody td").each(function() {
                if ($(this).attr("tit").substr(5,2) != num) {
                    $(this).css('color','#eee');
                }else{
                    $(this).css("cursor","pointer");
                }

            });

            $.each(month_time,function(i,item){
                var day = item[0].substr(0,10);
                var day_flag = item[0].substr(11,1);

                html_node.find("#cal_week tbody td").each(function() {
                    var $this=$(this);

                    if ($(this).attr("tit") == day ) {
                        if(day_flag ==1){
                            $(this).addClass("select_free_time");
                            $(this).attr("plan_time",1);
                        }else{
                            $(this).attr("plan_time",0);
                        }
                    }
                });

            });


        });
        $.do_ajax('/user_manage_new/get_seller_leave_and_overtime_js',{
            adminid: opt_data.adminid,
            month: g_args.start_time
        },function(resp) {
            var leave_and_overtime = resp.data;

            $.each(leave_and_overtime,function(i,item){
                var day = item[0].substr(0,10);
                var time_flag = item[0].substr(11,1);

                html_node.find("#cal_week tbody td").each(function() {
                    var $this=$(this);

                    if ($(this).attr("tit") == day ) {
                        if($(this).attr("plan_time")==1){
                            if(time_flag==2){
                                $(this).removeClass("select_free_time");
                                $(this).addClass("leave");
                            }
                        }else{
                            if(time_flag==3){
                                $(this).addClass("overtime");
                            }

                        }

                    }
                });

            });


        });

        if($(html_node.find("#tr_list_6 > td")[0]).attr("tit").substr(5,2) != num ){
            html_node.find("#tr_list_6").hide();
        }
        html_node.find("#cal_week tbody td").on("click",function(){
            if($(this).attr("tit").substr(5,2) == num){
                if($(this).attr("plan_time")==1){
                    if ( $(this).hasClass("select_free_time")) {
                        $(this).removeClass("select_free_time");
                        $(this).addClass("leave");
                    }else{
                        $(this).removeClass("leave");
                        $(this).addClass("select_free_time");
                    }

                }else{
                    if ( !$(this).hasClass("overtime")) {
                        $(this).addClass("overtime");
                    }else{
                        $(this).removeClass("overtime");
                    }

                }
            }
        });

        console.log(opt_data.adminid);
        console.log(g_args.start_time);

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
            },{
                label: '保存',
                cssClass: 'btn btn-warning',
                action: function(dialog)  {
                    var month_leave_list=[];
                    html_node.find("#cal_week tbody td").each(function() {
                        var $this=$(this);

                        if ($(this).attr("tit").substr(5,2) == num) {
                            var tmp_date= $this.attr("tit");
                            if($(this).hasClass("leave")){
                                month_leave_list.push ([
                                    ""+tmp_date +":2"
                                ]);

                            }else if($(this).hasClass("overtime")){
                                month_leave_list.push ([
                                    ""+tmp_date +":3"
                                ]);

                            }
                        }
                    });
                    console.log(JSON.stringify(month_leave_list ));
                    $.do_ajax("/user_deal/update_seller_month_leave_and_overtime",{
                        adminid: opt_data.adminid,
                        month: g_args.start_time,
                        leave_and_overtime : JSON.stringify(month_leave_list )
                    });

                }

            }],
            onshown:function(){

            }

        });

        dlg.getModalDialog().css("width","1024px");

    });


  $('.opt-change').set_input_change_event(load_data);
});
