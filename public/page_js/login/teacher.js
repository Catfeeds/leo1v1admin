/// <reference path="../common.d.ts" />
/**
 * Created by aaron on 14-9-9.
 */
$(function(){
    $('#verify_image').attr('src', '/login/get_verify_code?r='+Math.random());

    var ua = window.navigator.userAgent.toLowerCase();
    $('#id_remember').on('click', function (){
        $(this).toggleClass('remember');
    })

    function login_fun(){
        var account = $('#id_account').val();
        var password = $('#id_password').val();
        var seccode  = $('#id_seccode').val();
        var remember = $('#id_remember').hasClass('remember');
        if (!check_account() || !check_password() || !check_seccode()) {
            $('#verify_image').attr('src', '/login/get_verify_code?r='+Math.random());
            return;
        }

        password = $.md5(password);
        $.ajax({
            'url':'/login/login_teacher',
            'type': 'POST',
            'data': {'account':account,'password':password,'seccode':seccode},
            // 'data': {'account':account,'password':password,'seccode':seccode,'remember':remember},
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

$('.navs a').on('click', function() {
    $(this).addClass('selected');
    $(this).siblings().removeClass('selected');
    var show_id = $(this).attr('data-id');
    $(show_id).removeClass('hide');
    $(this).siblings().each(function(){
        var hide_id = $(this).attr('data-id');
        $(hide_id).addClass('hide');
    });
});

$('.download span').on('click', function() {
    $(this).addClass('choised');
    $(this).siblings().removeClass('choised');
    var show_class = $(this).attr('data-type');
    $(show_class).removeClass('hide');
    $(this).siblings().each(function(){
        var hide_class = $(this).attr('data-type');
        $(hide_class).addClass('hide');
    });
});

$('.download-pc-url').click( function () {
    var window_url="http://leowww.oss-cn-shanghai.aliyuncs.com/LeoeduTeacher/%E7%90%86%E4%BC%981%E5%AF%B91%20-%20%E8%80%81%E5%B8%88%E7%AB%AF%20Setup%204.1.0.exe";
    var mac_url="http://leowww.oss-cn-shanghai.aliyuncs.com/LeoeduTeacher/LeoeduTeacher-4.1.0.dmg";
    if(navigator.platform.indexOf('mac') > -1 || navigator.platform.indexOf('Mac') > -1){
        window.location.href = mac_url;
    }else{
        if(navigator.userAgent.indexOf("Windows XP") > -1 || navigator.userAgent.indexOf("Windows NT 5.1") > -1){
            alert('不支持Windows XP,请安装Windows7及以上操作系统')
        }else{
            window.location.href = window_url;
        }
    }
});
$(".download-pdf").click( function(){
    var window_url = "http://leowww.oss-cn-shanghai.aliyuncs.com/LeoeduTeacher/FoxitReader_8.2.0.2051.exe";
    var mac_url = "http://leowww.oss-cn-shanghai.aliyuncs.com/LeoeduTeacher/FoxitReader.2.3.0.2197.enu.Setup.pkg";
    console.log(navigator.platform);
    if(navigator.platform.indexOf('mac') > -1 || navigator.platform.indexOf('Mac') > -1){
        window.location.href = mac_url;
    }else{
        if(navigator.userAgent.indexOf("Windows XP") > -1 || navigator.userAgent.indexOf("Windows NT 5.1") > -1){
            alert('不支持Windows XP,请安装Windows7及以上操作系统')
        }else{
            window.location.href = window_url;
        }
    }
});
