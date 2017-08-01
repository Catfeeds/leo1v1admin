/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/customer_service-proposal_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
	$('.opt-change').set_input_change_event(load_data);

	$("#id_add_proposal_user_info").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var username          = $("<input />");     //姓名
        var phone             = $("<input />");     //联系方式
        var complaint_user_type= $("<select />");   //身份
        var content           = $("<textarea />");  //建议内容

      	Enum_map.append_option_list("complaint_user_type",complaint_user_type,true);

        var arr = [
            ["姓名", username],
            ["联系方式", phone],
            ["身份", complaint_user_type],
            ["建议内容",content],
        ];
        $.show_key_value_table("添加用户建议信息", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
             	if(username.val() === ''){
                    alert("请输入姓名");
                    return;
                }
                if(phone.val() === ''){
                    alert("请输入联系方式");
                    return;
                }
                if(complaint_user_type.val() <= 0){
                    alert("请选择身份");
                    return;
                }
                if(content.val() <= 0){
                    alert("请输入建议内容");
                    return;
                }
                $.do_ajax("/ajax_deal2/add_proposal_info",{
                    "phone"          : phone.val(),
                    'username'       : username.val(),
                    'complaint_user_type' : complaint_user_type.val(),
                    'content'        : content.val(),
                });
            }
        },function(){
        });
    });


    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("要删除姓名是["+opt_data.username+"]的建议信息吗?",function(val){
            if(val){
                $.do_ajax("/ajax_deal2/del_proposal_info",{
                    "id" : opt_data.id
                });
            }
        });
    });

    
    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var username          = $("<input />");  //姓名
        var phone             = $("<input />");  //联系方式
        var complaint_user_type= $("<select />");  //身份
        var content           = $("<textarea />");  //建议内容

        Enum_map.append_option_list("complaint_user_type",complaint_user_type,true);


        phone.val(opt_data.phone);
        username.val(opt_data.username);
        complaint_user_type.val(opt_data.complaint_user_type);
        content.val(opt_data.content);

        var arr = [
            ["姓名", username],
            ["联系方式", phone],
            ["身份", complaint_user_type],
            ["建议内容",content],
        ];

        $.show_key_value_table("修改用户建议信息", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
             	if(username.val() === ''){
                    alert("请输入姓名");
                    return;
                }
                if(phone.val() === ''){
                    alert("请输入联系方式");
                    return;
                }
                if(complaint_user_type.val() <= 0){
                    alert("请选择身份");
                    return;
                }
                if(content.val() <= 0){
                    alert("请输入建议内容");
                    return;
                }
                $.do_ajax("/ajax_deal2/edit_proposal_info",{
                 	  "id"             : opt_data.id,
                    "phone"          : phone.val(),
                    'username'       : username.val(),
                    'complaint_user_type' : complaint_user_type.val(),
                    'content'        : content.val(),
                });
            }
        },function(){
        });
    }) ;

});
