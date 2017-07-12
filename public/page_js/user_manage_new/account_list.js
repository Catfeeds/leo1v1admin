
function load_data(){

    reload_self_page({
        phone: $("#id_phone").val(),
        userid: $("#id_userid").val()
        
    });
}


$(function(){
    
    $("#id_phone").val(g_args.phone);
    set_input_enter_event($("#id_phone"),load_data );

    $("#id_userid").val(g_args.userid==-1?"": g_args.userid);
    set_input_enter_event($("#id_userid"),load_data );


    
    $(".opt-set-userid" ).on("click",function(){
        var phone=$(this).get_opt_data("phone");
        var role=$(this).get_opt_data("role");
        var id_userid=$("<input/>");
        var arr=[
            ["userid", id_userid]
        ];
        show_key_value_table("修改userid", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax("/user_manage_new/account_set_phone_userid",{
                    "phone" :phone,
                    "role" : role,
                    "userid" : id_userid.val()
                });
            }
        });
    
    });

});
