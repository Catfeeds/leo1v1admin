/// <reference path="../common.d.ts" />
/**
 * Created by aaron on 14-9-9.
 */
$(function(){
    $('#verify_image').attr('src', '/login/get_verify_code?r='+Math.random());

    var ua = window.navigator.userAgent.toLowerCase();

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
            'url':'/login/login_teacher',
            'type': 'POST',
            'data': {'account':account,'password':password,'seccode':seccode},
            'dataType': 'jsonp',
            success: function(data) {
                if (data['ret'] == 0) {
                    var to_url=$.query.get("to_url");
                    if (to_url ){
                        window.location.href= to_url;
                    }else{
                        window.location.href=  "/teacher_info";
                    }
                } else {
                    $('#id_errmsg').html( "错误:"+ data["ret"] +":"+ data["info"] );
                    $('#verify_image').attr('src', '/login/get_verify_code?r='+Math.random());
                }
            }

        });

    };




  $(window).keypress(function(e) {
    if (e.which == 13) {
      login_fun();
    }
  });

    $('#verify_image').on('click', function(){
        $(this).attr('src', '/login/get_verify_code?r='+Math.random());
    });

    $("#id_user_login").on("click",function(){
        login_fun();
        return false;
    } );

});



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
