/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_join-login.d.ts" />

$(function(){
    
    
    $("#id_send_code").on("click",function(){
        var phone=$("#id_phone").val();
        $.ajax({
            'url': "/admin_join/send_phone_code",
            'type': 'POST',
            'data': {'phone': phone },
            'dataType': 'json',
            success: function(ret) {
                if (ret.ret==-1) {
                    alert(ret.info);
                }else{
                    alert("请填写编号为"+ret.index+"的验证码");
                }
            }
        });

        
    });



    
    $("#id_login").on("click",function(){
        var phone_code  = $("#id_code").val();
        var phone  = $("#id_phone").val();
        if (phone_code.length!=4) {
            alert("验证码长度是4位!");
            return;
        }

        $.ajax({
            'url'  : "/admin_join/check_phone_code",
            'type' : 'POST',
            'data' : {
                'phone'      : phone,
                'phone_code' : phone_code
            },
            'dataType': 'json',
            success: function(ret) {
                if (ret.check_flag) {
                    window.location.href="/admin_join";
                }else{
                    alert("验证码错误！");
                }
            }
        });
    });


});


