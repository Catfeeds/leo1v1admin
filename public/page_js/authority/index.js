// SWITCH-TO:   ../../template/authority/authority_group.html  
$(function(){
    var reload_page=function (){
        window.location.href= window.location.pathname +"?groupid="+ $(".danger.auth_grp").data("groupid");
    };

    var current_groupid=-1;

    //adcc id_add_user_grp
    $("#id_add_user_grp").admin_select_admin();

    $(".auth_grp").on("click",function(){
	    current_groupid = $(this).data("groupid");
        $(".auth_grp").removeClass("danger");
        $(this).addClass("danger");
		$(".opt-group-name").text($(this).text());
		$.ajax({
            url: '/authority/get_grp_member',
            type: 'POST',
            data: {'groupid':current_groupid},
            dataType: 'json',
            success: function(result) {
                if (result['ret'] != 0)  {
                    BootstrapDialog.alert(result['info']);
                    return;
                }

                var user = '';
                var user_record = "";
                while((user = result['user_list'].shift())){
                    user_record += '<tr><td class="user_account" >' + user + '</td>' +
                            '<td><button class="btn fa fa-trash-o opt-del" data-account="'+user+'"></button></td></tr>';
                }
                $("#id_grp_member").html(user_record);
                $("#id_grp_member").find(".opt-del"). on("click",function(){
		            var account = $(this).data("account");
		            $.ajax({
                        url: '/authority/del_manager_from_grp',
                        type: 'POST',
                        data: {'groupid':current_groupid ,'account':account},
                        dataType: 'json',
                        success: function(result) {
                            if(result['ret'] != 0){
                                alert(result['info']);
                            }else{
                                reload_page();
                            }
			            }
                    });
                });
            }
        });
	});

	$("#id_change_grp_name").on("click",function(){
		var new_name = $("#id_new_name").val();
		$.ajax({
            url: '/authority/change_grp_name',
            type: 'POST',
            data: {'groupid':current_groupid,'new_name':new_name},
            dataType: 'json',
            success: function(result) {
				if(result.ret == 0){
                    reload_page();
				}else{
					alert(result.info);
				}
			}
	    });
	});

    $(".opt-add-group").on("click", function(){
        var html_node = $("<div></div>").html(dlg_get_html_by_class("dlg_add_grp"));
        BootstrapDialog.show({
	        title: "新增权限组",
	        message : html_node ,
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    var group_name = html_node.find(".group_name").val();
		            $.ajax({
                        url: '/authority/add_group',
                        type: 'POST',
                        data: {'group_name':group_name},
                        dataType: 'json',
                        success: function(result) {
                            if(result['ret'] != 0){
                                alert(result['info']);
                            }else{
                                reload_page();
                            }
			            }
		            });
			        dialog.close();
		        }
	        }]
        });
    });

    $(".opt-modify-group-name").on("click", function(){
        var html_node = $("<div></div>").html(dlg_get_html_by_class("dlg_add_grp"));

        BootstrapDialog.show({
	        title: "更改组名",
	        message : html_node ,
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    
		            var new_name = html_node.find(".group_name").val();
		            $.ajax({
                        url: '/authority/change_grp_name',
                        type: 'POST',
                        data: {'groupid':current_groupid,'new_name':new_name},
                        dataType: 'json',
                        success: function(result) {
				            if(result.ret == 0){
                                reload_page();
				            }else{
					            alert(result.info);
				            }
			            }
	                });

			        dialog.close();
		        }
	        }]
        });
    });

    $(".opt-del-group").on("click", function(){
        BootstrapDialog.show({
	        title: "删除用户组",
	        message : "是否删除本用户组",
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
		            $.ajax({
                        url: '/authority/delete_grp',
                        type: 'POST',
                        data: {'groupid':current_groupid},
                        dataType: 'json',
                        success: function(result) {
                            BootstrapDialog.alert(result['info']);
                            var ts = setTimeout(function(){
                                window.location.href = window.location.href;
                                clearTimeout(ts);
                            }, 1000);
			            }
                    });
			        dialog.close();
		        }
	        }]
        });
    });
    $(".auth_grp[data-groupid="+$.query.get("groupid") +"]" ).click();
});
