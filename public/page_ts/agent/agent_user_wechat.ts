/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_user_wechat.d.ts" />


$(function(){
    function load_data(){
        $.reload_self_page ( {
            phone: $("#id_phone").val(),
            id: g_args.id
        });
    }


    $('#id_phone').val(g_args.phone);


    $('.opt-change').set_input_change_event(load_data);
    function load_invite_list(test_lesson_succ_flag,  agent_status_money_open_flag , field_str ){
        $.ajax({
            type : "get",
            url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_"+field_str+"_invite_money_list?_agent_id="+g_args.id+"&test_lesson_succ_flag="+ test_lesson_succ_flag + "&agent_status_money_open_flag=" + agent_status_money_open_flag,
            dataType : "jsonp",//数据类型为jsonp
            success : function(data){
                var invite_list= data.list;
                var new_desc_list_str="";
                $.each( invite_list, function(){
                    new_desc_list_str+= "<tr><td>"
                        + "加入时间:" + this.create_time
                        + "<br/>学员:" + this.nick
                        + "<br/>状态:" + this.agent_status_str
                        + "<br/>提成:" + this.agent_status_money
                        + "<br/>可提现:" + this.agent_status_money_open_flag_str
                        +"</td></tr>";
                });
                $("#id_new_desc_list").html(new_desc_list_str);
            },
            error:function(){
                alert('fail');
            }
        });

    };

    $("#id_invite_money_not_open_not_lesson_succ").on("click",function(){
        load_invite_list(-1,0,"l1");
    });



    $("#id_invite_money_info").on("click",function(){
        load_invite_list(1,1,"l1");
    });


    $("#id_l2_invite_money_not_open_not_lesson_succ").on("click",function(){
        load_invite_list(-1,0,"l2");
    });

    $("#id_l2_invite_money_info").on("click",function(){
        load_invite_list(1,1,"l2");
    });





   $.ajax({
        type : "get",
        url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_user_info_new?_agent_id="+g_args.id ,
        dataType : "jsonp",//数据类型为jsonp
        success : function(data){
            var user= data.user_info_list;
            $("#id_agent_level_str").text(user.agent_level_str );
            $("#id_wx_headimgurl").attr("src", user.wx_headimgurl );
            $("#id_star_count").text( user.star_count);
            $("#id_wx_nick").text( user.wx_nick );
            $("#id_all_money_info").text(""+ user. all_money_info.all_money+"/"+ user.all_money_info.open_moeny +"/"+ user.all_have_cush_money );
            $("#id_order_money_info").text(""+ user. order_money_info.all_money+"/"+ user.order_money_info.open_moeny  );
            $("#id_invite_money_info").text(""+ user. invite_money_info.all_money+"/"+ user.invite_money_info.open_moeny );
            $("#id_invite_money_not_open_not_lesson_succ").text(user.invite_money_info.all_money- user.invite_money_info.open_moeny   );
            $("#id_l2_invite_money_info").text(""+ user. l2_invite_money_info.all_money+"/"+ user.l2_invite_money_info.open_moeny );
            $("#id_l2_invite_money_not_open_not_lesson_succ").text(user.l2_invite_money_info.all_money- user.l2_invite_money_info.open_moeny   );
            $("#id_order_user_count").text(user.order_user_count);
            $("#id_child_all_count").text(user.child_all_count);
            $("#id_activity_money").text(user.activity_money_info.all_money );

        },
        error:function(){
            alert('fail');
        }
    });

   $.ajax({
        type : "get",
        url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_level_1_user_list?_agent_id="+g_args.id ,
        dataType : "jsonp",//数据类型为jsonp
        success : function(data){
            var str="" ;
            $.each( data.list, function(){
                var sub_str="";
                var agent_type= this.agent_type;
                if( agent_type ==1 || agent_type==3 )  {
                    sub_str +="状态:" + this.agent_student_status_str + "<br>";
                }
                var css="";
                if( agent_type ==2 || agent_type==3 )  {
                    sub_str += "邀请:" + this.child_count + "<br>";

                    css="color:red;";
                }

                str+="<tr  data-agent_type="+this.agent_type +" data-agent_id="+this.agent_id +" ><td style=\""+css+"\">"
                    +"加入时间:" + this.create_time  + "<br>"
                    +"姓名:" + this.name+ "<br>"
                    +"类别:" + this.agent_type_str + "<br>"
                    +sub_str
                    +" </td> </tr>";
            } );
            $("#id_level1_list").html(str);

        },
        error:function(){
            alert('fail');
        }
    });


   $.ajax({
        type : "get",
        url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_user_cash?_agent_id="+g_args.id ,
        dataType : "jsonp",//数据类型为jsonp
        success : function(data){
            //{"phone":"13456568880","name":"跳妈","status":0,"count":0,"time":"2017.08.04"}
            //,0未试听,1试听成功,2已购课
            var cash = data.cash    ;
            $("#id_f_cash_2").text(cash);

            //{"price":490,"userid":"214727","orderid":"20854","pay_price":4900,"pay_time":"2017-08-13 16:30:43","parent_name":"15296031880","order_time":"1503558534","count":"0","order_cash":0,"level1_cash":98,"level2_cash":392}

            //array( "pay_time" => 购课时间 "parent_name" => 家长姓名 "count" => 上课次数 "order_cash" => 单笔提现金额 "level1_cash" => 上满2次课可提现金额 "level2_cash" => 上满8次课可提现金额 )) type=1: array(array( "price" => 单笔收入 "pay_price" => 购买课程金额 "pay_time" => 购课时间
            var str="" ;
            $.each( data.list, function(){
                str+="<tr><td> "
                    +" 购买时间："+this.pay_time + "<br/>"
                    +" 购买金额："+this.pay_price + "<br/>"
                    +" 家长姓名："+this.parent_name+ "<br/>"
                    +" 上课次数："+this.count+ "<br/>"
                    +" 单笔提成："+this.price + "<br/>"
                    +" 单笔提现金额："+this.order_cash+ "<br/>"
                    +" 上满2次课可提现金额："+this.level1_cash + "<br/>"
                    +" 上满8次课可提现金额："+this.level2_cash + "<br/>"
                    +" </tr>";
            });
            $("#id_cash_list").html(str);

        },
        error:function(){
            alert('fail');
        }
    });
    $("#id_level1_list").on("click", "tr", function() {
        var agent_id=$(this).data("agent_id");
        var agent_type=$(this).data("agent_type");


        $.ajax({
            type : "get",
            url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_level_2_user_list?_agent_id="+g_args.id + "&sub_agent_id="+ agent_id  ,
            dataType : "jsonp",//数据类型为jsonp
            success : function(data){
                var str="" ;
                $.each( data.list, function(){
                    var sub_str="";
                    var agent_type= this.agent_type;
                    if( agent_type ==1 || agent_type==3 )  {
                        sub_str +="状态:" + this.agent_student_status_str + "<br>";
                    }
                    if( agent_type ==2 || agent_type==3 )  {
                        sub_str += "邀请:" + this.child_count + "<br>";
                    }

                    str+="<tr style=\"\" data-agent_type="+this.agent_type +" data-agent_id="+this.agent_id +" ><td>"
                        +"加入时间:" + this.create_time  + "<br>"
                        +"姓名:" + this.name+ "<br>"
                        +"类别:" + this.agent_type_str + "<br>"
                        +sub_str
                        +" </td> </tr>";
                } );
                $("#id_level2_list").html(str);

            },
            error:function(){
                alert('fail');
            }
        });


    });

});
