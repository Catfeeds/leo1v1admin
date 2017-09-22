/**
 * Created by aaron on 14-9-9.
 */
$(function(){
    $('#verify_image').attr('src', '/login/get_verify_code?r='+Math.random());
    $('#id_seccode').blur(function() {
        var seccode = $(this).val();
    });

    var ua = window.navigator.userAgent.toLowerCase();

    function check_in_phone(){
        return $(window).width()<= 678;
    }

    if (!g_passwd_login_flag )
    {
        $("#id_password").hide();
        $("#id_seccode").hide();
        $("#id_user_login").hide();
        $("#verify_image").hide();
        $("#id_reset_passwd").hide();
        $("#id_account").hide();
        $("#id_wx_account_login").hide();
        $("#id_wx_login").show();
        if ( check_in_phone() ) {
            if ( ua.match(/MicroMessenger/i) == 'micromessenger' ) {
                $("#id_wx_login").hide();
            }else{
                $("#id_password").hide();
                $("#id_seccode").hide();
                $("#id_user_login").hide();
                $("#verify_image").hide();
                $("#id_reset_passwd").hide();

                $("#id_account").show();
                $("#id_wx_login").hide();
                $("#id_wx_account_login").show();
            }
        }else{

        }

    }else{
        $("#id_wx_account_login").hide();
        $("#id_wx_login").hide();
    }



    var need_verify_flag=true;
    var reset_verify = function(){
        $.ajax({
            'url':'/login/login_check_verify_code',
            'type': 'POST',
            'data': {'account': $('#id_account').val() },
            'dataType': 'json',
            success: function(data) {
                need_verify_flag=data.need_verify_flag;
                if (need_verify_flag){
                    $("#id_verify").show();
                }else{
                    $("#id_verify").hide();
                }
            }
        });
    };

    $('#id_account').on("change",function(){
        reset_verify();
    });

    reset_verify();



  function login_fun(){
        var account = $('#id_account').val();
        var password = $('#id_password').val();
        var seccode  = $('#id_seccode').val();

        if (!check_account() || !check_password() || !check_seccode()) {
            $('#verify_image').attr('src', '/login/get_verify_code?r='+Math.random());
            return;
        }

        password = $.md5(password);
        $.ajax({
            'url':'/login/login',
            'type': 'POST',
            'data': {'account':account,'password':password,'seccode':seccode},
            'dataType': 'jsonp',
            success: function(data) {
                if (data['ret'] == 0) {
                    if ( $.query.get("to_url") ){
                        window.location.href= $.query.get("to_url") ;
                    }else{
                        window.location.reload();
                    }
                } else {
                    $('#id_errmsg').html( "错误:"+ data["ret"] +":"+ data["info"] );
                    $('#verify_image').attr('src', '/login/get_verify_code?r='+Math.random());
                }
        console.log(data);
            }

        });

    };
    $("#id_wx_account_login").on("click",function(){
        var account=$('#id_account').val();
        var admin_code="AD_"+Math.random()*10000+"_"+Math.random()*10000;

        var url="http://admin.yb1v1.com/wx_login/login?admin_code="+admin_code;

        $.ajax({
            'url': "/wx_login/init",
            'type': 'POST',
            'data': {
                'admin_code': admin_code,
                'account': account ,
                'url': url
            },
            'dataType': 'json',
            success: function(ret) {
                //
                if (ret.ret==0){
                    setInterval(function(){
                        $.ajax({
                            'url': "/wx_login/check",
                            'type': 'POST',
                            'data': {'admin_code': admin_code },
                            'dataType': 'json',
                            success: function(ret) {
                                if (ret.flag ) {
                                    window.location.reload();
                                }

                            }
                        });
                    },1000);

                    BootstrapDialog.alert ("发送成功，如你的微信没收到登陆信息,请确认你已关注［理优教育在线学习APP］");
                }else{
                    alert(ret.info);
                }
            }
        });

        return false;
    });


    $("#id_wx_login").on("click",function(){
        var admin_code="AD_"+Math.random()*10000+"_"+Math.random()*10000;

        $.ajax({
            'url': "/wx_login/init",
            'type': 'POST',
            'data': {'admin_code': admin_code},
            'dataType': 'json',
            success: function(ret) {
                var text=encodeURIComponent(  ret.wx_url);
                var dlg = BootstrapDialog.show({
                    title: "微信登陆",
                    message: "<div style = \"text-align:center\"><img width=\"350px\" src=\"/common/get_qr?text=" + text + "\"></img>",
                    closable: true
                });
                dlg.getModalDialog().css("width", "600px");

                try {
                    var ws = $.websocket("ws://" + window.location.hostname + ":9501/", {
                        events: {
                            "noti_check_login": function (e) {
                                $.ajax({
                                    'url': "/wx_login/check",
                                    'type': 'POST',
                                    'data': {'admin_code': admin_code },
                                    'dataType': 'json',
                                    success: function(ret) {
                                        if (ret.flag ) {
                                            window.location.reload();
                                        }
                                    }
                                });
                            }
                        },open : function () {
                            ws.send("check_login",{
                                "user_login_key": admin_code
                            });
                        }
                    });
                }catch(e) {
                    var check_func=function () {
                        setInterval(function(){
                            $.ajax({
                                'url': "/wx_login/check",
                                'type': 'POST',
                                'data': {'admin_code': admin_code },
                                'dataType': 'json',
                                success: function(ret) {
                                    if (ret.flag ) {
                                        window.location.reload();
                                    }
                                }
                            });
                        },1000);
                    };
                    check_func();
                }
            }
        });
        return false;
    });

    $('#id_user_login').on('click',function(){
        login_fun();
        return false;
    });

    $(window).keypress(function(e) {
        if (e.which == 13) {
            login_fun();
        }
    });

    $('#verify_image').on('click', function(){
        $(this).attr('src', '/login/get_verify_code?r='+Math.random());
    });

    $('#logout').on('click', function(){
        $('.confirm_operate').addClass('user_logout');
        $('.alert_cont').text('确定退出？');
        $('.without_cancel').hide();
        $('.with_cancel').show();
    });



    $("#id_reset_passwd").on("click",function(){
        var account=$('#id_account').val();
        $.ajax({
            'url': "/index/get_admin_phone",
            'type': 'POST',
            'data': {'account': account },
            'dataType': 'json',
            success: function(ret) {
                if (ret.phone) {
                    var phone       = ret.phone;
                    var $phone      = $( "<div> <span>"+phone + " </span> <a href=\"javascript:;\"> 发送验证码</a> </div>" );
                    var $phone_code = $("<input class='form-control' style=\"width:120px;float:left\" />"
                                        +"<div class='check_flag fa' style='float:left;line-height:30px;margin-left:10px'></div>");
                    var $passwd     = $("<input class='form-control' style=\"width:240px;\" type=\"password\"/>");
                    var phone_code  = '';
                    var check_flag  = '';
                    $phone_code.on("change",function(){
                        phone_code  = $phone_code.val();
                        $.ajax({
                            'url'  : "/index/check_phone_code",
                            'type' : 'POST',
                            'data' : {
                                'phone'      : phone,
                                'phone_code' : phone_code
                            },
                            'dataType': 'json',
                            success: function(ret) {
                                if (ret.check_flag) {
                                    $('body').find(".check_flag").removeClass("fa-times");
                                    $('body').find(".check_flag").addClass("fa-check");
                                    //alert("验证成功！");
                                }else{
                                    $('body').find(".check_flag").removeClass("fa-check");
                                    $('body').find(".check_flag").addClass("fa-times");
                                    //alert("验证码错误！");
                                }
                                check_flag=ret.check_flag;
                            }
                        });
                    });

                    $phone.find("a").on("click",function(){
                        $.ajax({
                            'url': "/index/send_phone_code",
                            'type': 'POST',
                            'data': {'phone': phone },
                            'dataType': 'json',
                            success: function(ret) {
                                BootstrapDialog.alert("请填写编号为"+ret.index+"的验证码");
                            }
                        });
                        return false;
                    });

                    var arr=[
                        ["电话", $phone ],
                        ["输入验证码", $phone_code ],
                        ["密码", $passwd]
                    ];

                    show_key_value_table ("重置密码",arr,  {
                        label: '确认',
                        cssClass: 'btn-warning',
                        action: function(dialog) {
                            $.ajax({
                                url: '/index/set_passwd',
                                type: 'POST',
                                dataType: 'json',
                                data : {
                                    'account'    : account,
                                    'check_flag' : check_flag,
                                    'passwd'     : $passwd.val()
                          },
                                success: function(data) {
                                    if(data.ret==0){
                                        BootstrapDialog.alert(data.info+"请重新登录!");
                                        window.location.reload();
                                    }else{
                                        BootstrapDialog.alert(data.info);
                                    }
                                }
                            });
                        }
                    });
                }else{
                    alert("请输入有效的用户名");
                }
            }
        });
    });
});

function show_key_value_table(title,arr ,btn_config,onshownfunc){

    var table_obj=$("<table class=\"table table-bordered table-striped\"  > </table>");

    $.each(arr , function( index,element){
        var row_obj=$("<tr> </tr>" );
        var td_obj=$( "<td style=\"text-align:right; width:30%;\"></td>" );
        var v=element[0] ;
        td_obj.append(v);
        row_obj.append(td_obj);
        td_obj=$( "<td ></td>" );

        td_obj.append( element[1] );
        row_obj.append(td_obj);
        table_obj.append(row_obj);
    });
    var all_btn_config=[{
        label: '返回',
        action: function(dialog) {
            dialog.close();
        }
    }];
    if (btn_config){
        all_btn_config.push(btn_config );
    }

    BootstrapDialog.show({
        title: title,
        message :  table_obj ,
        closable: true,
        buttons: all_btn_config ,
        onshown:onshownfunc
    });
}


function check_account() {
    if ( $('#account').val() == '' ) {
    alert('用户名不能为空');
        return false;
    }
    return true;
}
function check_password() {
    if ( $('#password').val() == '' ) {
        alert('密码不能为空');
        return false;
    }
    return true;
}
function check_seccode() {
    if ( $('#seccode').val() == '' ) {
        alert('验证码不能为空');
        return false;
    }
    return true;
}
