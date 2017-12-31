$( function () {

  $(".main-header .logo").on("click",function(){
    window.location.href=window.admin_api;
    return false;
  });


  $.do_ajax( "/self_manage/get_login_info", {}, function(resp ) {
    var $menu= $("#__id_menu");
    console.log(resp );
    $menu.html( resp.menu_html );
    window.g_account      = resp.account;
    window.g_account_role = resp.account_role;
    window.g_adminid      = resp.g_adminid;
    window.g_power_list   = JSON.parse( resp.power_list );


    var $user_info=$("#__id_user_info");
    $user_info.find("img").attr("src",resp.face_pic); //
    $user_info.find("p").text(window.g_account); //

    var check_url= window.location.toString().split("?" )[0];
    var obj=$menu.find("li>a[href=\""+ check_url +"\"]");

    $.do_select_menu(obj);

    $menu.find("li>a[href*=\"http\"]").on("click",function(e) {
      $.do_select_menu ($(e.currentTarget));
    });
  });
});
