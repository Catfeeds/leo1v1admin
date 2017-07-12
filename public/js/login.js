/**
 * Created by aaron on 14-9-9.
 */
$(function(){
    $('#verify_image').attr('src', '/login/get_verify_code?r='+Math.random());
	function login_fun(){
        var account = $('#account').val();
        var password = $('#password').val();
        var seccode  = $('#seccode').val();

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
                   // window.location.reload();
				   $('.error').hide();
				   window.location.href = "/";
                } else {
                    $('#verify_image').attr('src', '/login/get_verify_code?r='+Math.random());
					$('.error').show();
                }
				console.log(data);
            }

        });
		 
    };
	
    $('#user_login').on('click',function(){
		login_fun();
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

    
});

function check_account() {
    if ( $('#account').val() == '' ) {
        //show_err_tip('用户名不能为空');
		alert('用户名不能为空');
        return false;
    }
    return true;
}
function check_password() {
    if ( $('#password').val() == '' ) {
        //show_err_tip('密码不能为空');
        alert('密码不能为空');
        return false;
    }
    return true;
}
function check_seccode() {
    if ( $('#seccode').val() == '' ) {
        //show_err_tip('验证码不能为空');
        alert('验证码不能为空');
        return false;
    }
    return true;
}

function show_err_tip(msg) {
    $('.shadow').show();
    $('#login_tip').show();
    $('#login_msg').text(msg);
}















