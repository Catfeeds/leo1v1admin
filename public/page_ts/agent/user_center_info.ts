/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-user_center_info.d.ts" />


$(function(){
    function load_data(){
        $.reload_self_page ( {
            phone: $("#id_phone").val(),
            nickname: $("#id_nickname").val(),
            id: g_args.id
        });
    }


    $('#id_phone').val(g_args.phone);
    $('#id_nickname').val(g_args.nickname);


    $('.opt-change').set_input_change_event(load_data);

    //@desn:获取用户个人中心
    $.ajax({
        type : "get",
        url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_user_center_info?_agent_id="+g_args.id ,
        dataType : "jsonp",//数据类型为jsonp
        success : function(data){
            var user= data.user_info_list;
            $("#id_agent_level_str").text(user.agent_level_str );
            $("#id_wx_headimgurl").attr("src", user.wx_headimgurl );
            $("#id_wx_nick").text( user.usernick );
            $("#id_all_money_info").text(""+ user. all_money );
            $("#id_child_all_count").text(user.child_all_count);

        }
    });
    //@desn:获取用户收入列表
    $.ajax({
        type : "get",
        url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/my_income_info?_agent_id="+g_args.id ,
        dataType : "jsonp",//数据类型为jsonp
        success : function(data){
            var income_info= data.income_info;
            $("#id_all_money").text(income_info.all_money);
            $("#id_open_money").text(income_info.open_moeny);
            $("#id_all_have_cash_money").text(income_info.all_have_cush_money);
            $("#id_is_cash_money").text(income_info.is_cash_money);
        }
    });
    //@desn:获取用户邀请奖励[全部]
    $("#id_all_invite").on("click",function() {
        $("#id_title").text('总收入金额>>邀请奖励>>我的邀请');
        $("#id_title_two").text('总收入金额>>邀请奖励>>会员邀请');
        $.ajax({
            type : "get",
            url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_invite_list?_agent_id="+g_args.id+"&page_count=1000",
            dataType : "jsonp",//数据类型为jsonp
            success : function(data){
                var my_str="" ;
                var member_str="" ;
                var agent_status_str = '';
                //遍历我的邀请
                $.each( data.my_invite.list, function(){
                    if(this.agent_student_status == 100)
                        agent_status_str = this.agent_status_str+'['+this.agent_student_status_str+']'; 
                    else
                        agent_status_str = this.agent_status_str;
                    my_str+="<tr style=\"\" ><td>"
                        +"姓名:" + this.nickname  + "<br>"
                        +"时间:" + this.create_time+ "<br>"
                        +"状态:" + agent_status_str + "<br>"
                        +"收入:" + this.agent_status_money + "<br>"
                        +" </td> </tr>";
                });
                //遍历会员邀请
                $("#id_detail_info").html(my_str);
                $.each( data.member_invite.list, function(){
                    if(this.agent_student_status == 100)
                        agent_status_str = this.agent_status_str+'['+this.agent_student_status_str+']'; 
                    else
                        agent_status_str = this.agent_status_str;

                    member_str+="<tr style=\"\" ><td>"
                        +"姓名:" + this.nickname  + "<br>"
                        +"时间:" + this.create_time+ "<br>"
                        +"状态:" + agent_status_str + "<br>"
                        +"收入:" + this.agent_status_money + "<br>"
                        +" </td> </tr>";

                });
                $("#id_detail_info_two").html(member_str);
            },
            error:function(){
                alert('fail');
            }
        });


    });
    //@desn:获取用户佣金奖励[全部]
    $("#id_all_commission").on("click",function() {
        $("#id_title").text('总收入金额>>佣金奖励>>我的邀请');
        $("#id_title_two").text('总收入金额>>佣金奖励>>会员邀请');
        $.ajax({
            type : "get",
            url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_commission_reward?_agent_id="+g_args.id+"&page_count=1000",
            dataType : "jsonp",//数据类型为jsonp
            success : function(data){
                var my_str="" ;
                //遍历我的邀请
                $.each( data.child_reward.list, function(){
                    my_str+="<tr style=\"\" ><td>"
                        +"姓名:" + this.nickname  + "<br>"
                        +"时间:" + this.create_time+ "<br>"
                        +"购买课程:" + this.price + "<br>"
                        +"收入:" + this.p_price + "<br>"
                        +"上课节数:" + this.count + "<br>"
                        +" </td> </tr>";
                });
                $("#id_detail_info").html(my_str);
            },
            error:function(){
                alert('fail');
            }
        });


        $.ajax({
            type : "get",
            url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_commission_reward?_agent_id="+g_args.id+"&table_type=2&page_count=1000",
            dataType : "jsonp",//数据类型为jsonp
            success : function(data){
                var member_str="" ;
                //遍历会员邀请
                $.each( data.member_reward.list, function(){
                    member_str+="<tr style=\"\" ><td>"
                        +"姓名:" + this.nickname  + "<br>"
                        +"时间:" + this.create_time+ "<br>"
                        +"购买课程:" + this.price + "<br>"
                        +"收入:" + this.p_price + "<br>"
                        +"上课节数:" + this.count + "<br>"
                        +" </td> </tr>";

                });
                $("#id_detail_info_two").html(member_str);
            },
            error:function(){
                alert('fail');
            }
        });


    });
    //@desn:获取活动奖励[全部]
    $("#id_all_activity").on("click",function() {
        $("#id_title").text('总收入金额>>活动奖励');
        $("#id_title_two").text('值');
        $.ajax({
            type : "get",
            url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_activity_rewards?_agent_id="+g_args.id+"&page_count=1000",
            dataType : "jsonp",//数据类型为jsonp
            success : function(data){
                var my_str="" ;
                //遍历我的邀请
                $.each( data.reward_list.list, function(){
                    my_str+="<tr style=\"\" ><td>"
                        +"活动:" + this.agent_money_ex_type_str  + "<br>"
                        +"时间:" + this.add_time+ "<br>"
                        +"奖励:" + this.money + "<br>"
                        +" </td> </tr>";
                });
                $("#id_detail_info").html(my_str);
                $("#id_detail_info_two").html('');
            },
            error:function(){
                alert('fail');
            }
        });


    });



    //@desn:获取用户邀请奖励[全部]
    $("#id_cash_invite").on("click",function() {
        $("#id_title").text('可提现>>邀请奖励>>我的邀请');
        $("#id_title_two").text('可提现>>邀请奖励>>会员邀请');
        $.ajax({
            type : "get",
            url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_had_invite_rewards?_agent_id="+g_args.id+"&page_count=1000",
            dataType : "jsonp",//数据类型为jsonp
            success : function(data){
                var my_str="" ;
                var member_str="" ;
                //遍历我的邀请
                $.each( data.my_invite.list, function(){
                    my_str+="<tr style=\"\" ><td>"
                        +"学员姓名:" + this.nickname  + "<br>"
                        +"奖励金额:" + this.agent_status_money+ "<br>"
                        +" </td> </tr>";
                });
                //遍历会员邀请
                $("#id_detail_info").html(my_str);
                $.each( data.member_invite.list, function(){
                    member_str+="<tr style=\"\" ><td>"
                        +"学员姓名:" + this.nickname  + "<br>"
                        +"奖励金额:" + this.agent_status_money+ "<br>"
                        +" </td> </tr>";

                });
                $("#id_detail_info_two").html(member_str);
            },
            error:function(){
                alert('fail');
            }
        });


    });
    //@desn:获取用户佣金奖励[全部]
    $("#id_cash_commission").on("click",function() {
        $("#id_title").text('可提现>>佣金奖励>>我的邀请');
        $("#id_title_two").text('可提现>>佣金奖励>>会员邀请');
        $.ajax({
            type : "get",
            url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_can_cash_commission?_agent_id="+g_args.id+"&page_count=1000",
            dataType : "jsonp",//数据类型为jsonp
            success : function(data){
                var my_str="" ;
                var member_str="" ;
                //遍历我的邀请
                $.each( data.child_reward.list, function(){
                    my_str+="<tr style=\"\" ><td>"
                        +"姓名:" + this.nickname  + "<br>"
                        +"时间:" + this.create_time+ "<br>"
                        +"购买课程:" + this.price + "<br>"
                        +"收入:" + this.p_open_price + "<br>"
                        +"状态:学生已上" + this.count + "节课<br>"
                        +" </td> </tr>";
                });
                //遍历会员邀请
                $("#id_detail_info").html(my_str);
                $.each( data.member_reward.list, function(){
                    member_str+="<tr style=\"\" ><td>"
                        +"姓名:" + this.nickname  + "<br>"
                        +"时间:" + this.create_time+ "<br>"
                        +"购买课程:" + this.price + "<br>"
                        +"收入:" + this.p_open_price + "<br>"
                        +"状态:学生已上" + this.count + "节课<br>"
                        +" </td> </tr>";

                });
                $("#id_detail_info_two").html(member_str);
            },
            error:function(){
                alert('fail');
            }
        });


    });
    //@desn:获取活动奖励[全部]
    $("#id_cash_activity").on("click",function() {
        $("#id_title").text('可提现>>活动奖励');
        $("#id_title_two").text('值');
        $.ajax({
            type : "get",
            url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_activity_rewards?_agent_id="+g_args.id+"&page_count=1000&is_cash=2",
            dataType : "jsonp",//数据类型为jsonp
            success : function(data){
                var my_str="" ;
                //遍历我的邀请
                $.each( data.reward_list.list, function(){
                    my_str+="<tr style=\"\" ><td>"
                        +"活动:" + this.agent_money_ex_type_str  + "<br>"
                        +"时间:" + this.add_time+ "<br>"
                        +"奖励:" + this.money + "<br>"
                        +" </td> </tr>";
                });
                $("#id_detail_info").html(my_str);
                $("#id_detail_info_two").html('');
            },
            error:function(){
                alert('fail');
            }
        });


    });

    //@desn:获取已提现列表
    $("#id_have_cash_list").on("click",function() {
        $("#id_title").text('已提现>>已提现列表');
        $("#id_title_two").text('值');
        $.ajax({
            type : "get",
            url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_have_cash?_agent_id="+g_args.id,
            dataType : "jsonp",//数据类型为jsonp
            success : function(data){
                var my_str="" ;
                var is_suc_flag = '结算中';
                //遍历已提现列表
                $.each( data.list, function(){
                    if(this.is_suc_flag == 1)
                        is_suc_flag = '已结算';
                    my_str+="<tr style=\"\" ><td>"
                        +"提现日期:" + this.create_time  + "<br>"
                        +"提现金额:" + this.cash+ "<br>"
                        +"状态:" + is_suc_flag + "<br>"
                        +" </td> </tr>";
                });
                $("#id_detail_info").html(my_str);
                $("#id_detail_info_two").html('');
            },
            error:function(){
                alert('fail');
            }
        });


    });

});
