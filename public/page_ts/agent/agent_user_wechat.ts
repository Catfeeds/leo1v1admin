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
            alert( JSON.stringify(data.list[0] ) );

        },
        error:function(){
            alert('fail');
        }
    });

});
