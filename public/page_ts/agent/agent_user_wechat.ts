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
   $.ajax({
        type : "get",
        url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_user_info?_agent_id="+g_args.id ,
        dataType : "jsonp",//数据类型为jsonp
        success : function(data){
            //data= {":{"level":2,"nick":"叶子君","pay":0,"cash":0,"have_cash":0,"num":0,"my_num":"0","count":2,"headimgurl":"http://wx.qlogo.cn/mmopen/rHBRSAHSNHgmSWpfYqVtoT5dDdozIGWkuuGd1U4fZibNT6rg3U8Lbic1MGVesiaA0gB5uDia2r6icicolibgdGjBcR0D0hThqNia8uej/0","nickname":"苹果🍎"},"ret":0,"info":"成功"};
            var user= data.user_info_list;
            $("#id_f_agent_level").text(Enum_map.get_desc("agent_level", user.level ));
            $("#id_f_headimgurl").attr("src", user.headimgurl );
            $("#id_f_nickname").text( user.nickname );
            $("#id_f_nick").text( user.nick);
            $("#id_f_cash").text( user.cash);
            $("#id_f_pay").text( user.pay);
            $("#id_f_have_cash").text( user.have_cash);
            $("#id_f_num").text( user.num);
            $("#id_f_my_num").text( user.my_num);
            $("#id_f_count").text( user.count);
        },
        error:function(){
            alert('fail');
        }
    });

   $.ajax({
        type : "get",
        url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_my_num?_agent_id="+g_args.id ,
        dataType : "jsonp",//数据类型为jsonp
        success : function(data){
            //{"phone":"13456568880","name":"跳妈","status":0,"count":0,"time":"2017.08.04"}
            //,0未试听,1试听成功,2已购课
            var status_conf={
                0 :  "未试听",
                1 :  "试听成功",
                2 :  "已购课",
            };

            var str="" ;
            $.each( data.list, function(){
                str+="<tr><td>"+this.phone +" <td> "+this.name+" <td> "+ status_conf[this.status]  +" <td> "+this.count+" <td> "+this.time+" </tr>";
            } );
            $("#id_my_list").html(str);
            //id_my_list

        },
        error:function(){
            alert('fail');
        }
    });


   $.ajax({
        type : "get",
        url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_user_cash_2?_agent_id="+g_args.id ,
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
});
