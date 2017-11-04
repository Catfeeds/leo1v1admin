/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-user_center_info.d.ts" />


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




    //@desn:获取用户个人中心
    $.ajax({
        type : "get",
        url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_user_center_info?_agent_id="+g_args.id ,
        dataType : "jsonp",//数据类型为jsonp
        success : function(data){
            var user= data.user_info_list;
            $("#id_agent_level_str").text(user.agent_level_str );
            $("#id_wx_headimgurl").attr("src", user.wx_headimgurl );
            $("#id_wx_nick").text( user.wx_nick );
            $("#id_all_money_info").text(""+ user. all_money );
            $("#id_child_all_count").text(user.child_all_count);

        },
        error:function(){
            alert('fail');
        }
    });
    //@desn:获取用户收入列表
    $.ajax({
        type : "get",
        url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_my_income_info?_agent_id="+g_args.id ,
        dataType : "jsonp",//数据类型为jsonp
        success : function(data){
            var income= data.;
            $("#id_agent_level_str").text(user.agent_level_str );
            $("#id_wx_headimgurl").attr("src", user.wx_headimgurl );
            $("#id_wx_nick").text( user.wx_nick );
            $("#id_all_money_info").text(""+ user. all_money );
            $("#id_child_all_count").text(user.child_all_count);

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


   // $.ajax({
   //      type : "get",
   //      url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_user_cash?_agent_id="+g_args.id ,
   //      dataType : "jsonp",//数据类型为jsonp
   //      success : function(data){
   //          var cash = data.cash    ;
   //          $("#id_f_cash_2").text(cash);

   //          var str="" ;
   //          $.each( data.list, function(){
   //              str+="<tr><td> "
   //                  +" 购买时间："+this.pay_time + "<br/>"
   //                  +" 购买金额："+this.pay_price + "<br/>"
   //                  +" 家长姓名："+this.parent_name+ "<br/>"
   //                  +" 上课次数："+this.count+ "<br/>"
   //                  +" 单笔提成："+this.price + "<br/>"
   //                  +" 单笔提现金额："+this.order_cash+ "<br/>"
   //                  +" 上满2次课可提现金额："+this.level1_cash + "<br/>"
   //                  +" 上满8次课可提现金额："+this.level2_cash + "<br/>"
   //                  +" </tr>";
   //          });
   //          $("#id_cash_list").html(str);

   //      },
   //      error:function(){
   //          alert('fail');
   //      }
   //  });
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
