/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/wx_teacher-bind.d.ts" />

$(function(){
    $('#verify_image').attr('src', '/login/get_verify_code?r='+Math.random());
    $('#id_seccode').blur(function() {
        var seccode = $(this).val();
    }); 

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
            'url':'/wx_teacher/do_bind',
            'type': 'POST',
            'data': {
                'openid':g_args.openid,
                'account':account,
                'password':password,
                'seccode':seccode
            },
            'dataType': 'jsonp',
            success: function(data) {
                var ret=data["ret"];
                if (ret!=0) {
                    alert(data["info"]);
                }else{
                    alert("绑定成功");
                    window.location.href=g_args.url;
                }
            }

        });
		 
    };
    $('#id_user_bind').on('click',function(){
		login_fun();
        return true;
	});
	
	$(window).keypress(function(e) { 
		if (e.which == 13) { 
			login_fun();
		} 
	});
	
    $('#verify_image').on('click', function(){
        $(this).attr('src', '/login/get_verify_code?r='+Math.random());
    });


    
});



function check_account() {
    if ( $('#id_account').val() == '' ) {
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















