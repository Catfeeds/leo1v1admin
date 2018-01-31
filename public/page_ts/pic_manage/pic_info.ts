/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/pic_manage-pic_info.d.ts" />

$(function(){
        function load_data(){
        $.reload_self_page({
            type       : $(".pic_type").val(),
		        usage_type : $(".pic_usage_type").val(),
            active_status: $("#active_status").val()
        });
	  }

    $(".pic_type").val(g_args.type);
    $(".pic_usage_type").val(g_args.usage_type);
    $("#active_status").val(g_args.active_status);
    	  $('.opt-change').set_input_change_event(load_data);


    Enum_map.append_option_list("pic_type", $(".pic_type"));
    Enum_map.append_option_list("pic_type", $(".add_pic_type"),true);
    Enum_map.append_option_list("pic_jump_type", $(".add_jump_type"),true);
    Enum_map.append_option_list("click_status", $(".add_pic_click_status"),true);
    Enum_map.append_option_list("grade_part_ex", $(".add_pic_grade"),true,[0,1,2,3]);
    Enum_map.append_option_list_by_not_id("subject", $(".add_pic_subject"),true,[11]);

    $(".pic_type").val(g_args.type);
    var set_select_option_list=function(){
        Enum_map.append_child_option_list("pic_usage_type", $(".pic_type"),$(".pic_usage_type"));
        Enum_map.append_child_option_list("pic_usage_type", $(".add_pic_type"),$(".add_pic_usage_type"),true);
        $("body").on("change",".add_pic_type",function(){
            Enum_map.append_child_option_list("pic_usage_type", $(this),$(".add_pic_usage_type"),true);
        });
    };
    set_select_option_list();
    $(".pic_usage_type").val(g_args.usage_type);

    // function load_data(){
    //     $.reload_self_page({
    //         type       : $(".pic_type").val(),
		//         //usage_type : val
    //         active_status: $("#active_status").val()
    //     });
	  // }

    // $(".pic_type").val(g_args.type);
    // //$(".usage_type").val(usage_type);
    // $("#active_status").val(g_args.active_status);

    // //筛选
	  // $(".pic_type").on("change",function(){
		//     load_data(-1);
	  // });
	  // $(".pic_usage_type").on("change",function(){
		//     load_data($(this).val());
	  // });
    // $("#active_status").on("change", function() {
    //     alert($(this).val())
    // });

    var do_add_or_update = function( opt_type, item ){
        var html_txt = $.dlg_get_html_by_class('dlg_add_pic_info');
        html_txt=html_txt.
            replace(/\"id_upload_add\"/, "\"id_upload_add_tmp\"" )
            .replace(/\"id_container_add\"/, "\"id_container_add_tmp\"" )
            // .replace(/\"id_upload_tag_add\"/, "\"id_upload_tag_add_tmp\"" )
            // .replace(/\"id_container_tag_add\"/, "\"id_container_tag_add_tmp\"" )
        ;
        var html_node = $("<div></div>").html(html_txt);

        var pic_url = "";
        var pic_img = "";
        var tag_url = "";
        var tag_img = "";

        html_node.find(".share_s").hide();
        if (opt_type=="update") {
            min_date = item.min_date;

            pic_url=item.img_url;
            pic_img="<img width=100 src=\""+pic_url+"\" />";
            tag_url=item.img_tags_url;
            if(tag_url!=''){
                tag_img="<img width=100 src=\""+tag_url+"\" />";
            }
            html_node.find(".add_header_img").html(pic_img);
            html_node.find(".pic_url").html(pic_url + "<button class='del_img'>删除</button>");
            html_node.find('.del_img').on("click", function(){
                html_node.find(".add_header_img").html('');
                html_node.find(".pic_url").html('');
            });

            html_node.find(".add_header_tag_img").html(tag_img);

            html_node.find(".add_pic_type").val(item.type); // 图片类型
            Enum_map.append_child_option_list("pic_usage_type",html_node.find(".add_pic_type"),
                                              html_node.find(".add_pic_usage_type"),true);


            html_node.find(".add_pic_usage_type").val(item.usage_type); 
            html_node.find(".add_pic_name").val(item.name);
            html_node.find(".add_pic_click_status").val(item.status);
            if(item.status==1){
                html_node.find(".share_s").show();
            }
            html_node.find(".add_pic_order_by").val(item.order_by);
            html_node.find(".add_pic_grade").val(item.grade);
            html_node.find(".add_pic_subject").val(item.subject);
            //html_node.find(".add_title_share").val(item.title_share);
            //html_node.find(".add_info_share").val(item.info_share);
            html_node.find(".add_jump_url").val(item.jump_url);
            html_node.find(".add_jump_type").val(item.jump_type);
            html_node.find(".add_start_date").val(item.start_time);
            html_node.find(".add_end_date").val(item.end_time);
            var start = Date.parse(new Date(item.start_time));
            var current = Date.parse(new Date(min_date));
            if (start < current) {
                min_date = 0;
            }
        }

        var title = "";
        if (opt_type=="update"){
            title="修改信息";
        }else{
            title="添加信息";
        }

	      html_node.find('.add_start_date').datetimepicker({
		        lang:'ch',
		        timepicker:false,
            minDate: 0,
		        format:'Y-m-d'
	      });
	      html_node.find('.add_end_date').datetimepicker({
		        lang:'ch',
		        timepicker:false,
            //startDate: ,
            minDate: min_date,
		        format:'Y-m-d'
	      });

        BootstrapDialog.show({
            title           : title,
            message         : html_node,
            closable        : true,
            closeByBackdrop : false,
            onshown         : function(dialog){
                if (html_node.find(".add_pic_usage_type").val() == 303) {
                    if (html_node.find('.add_jump_type').val() == 1) {
                        $('.add_jump_url').val('');
                    }
                    $(".add_jump_type option[value='1']").remove()
                }

                $(".add_pic_usage_type").on("change", function() {
                    var val = $(".add_jump_type option[value='1']").val();
                    if (val == undefined) {
                        $(".add_jump_type").append("<option value='1'>视频</option>");
                    }
                    if ($(this).val() == 303) { // 删除视频选项
                        if (html_node.find('.add_jump_type').val() == 1) {
                            $('.add_jump_url').val('');
                        }
                        $(".add_jump_type option[value='1']").remove()
                    }
                });
                $(".add_pic_type").on("change", function() {
                    var val = $(".add_jump_type option[value='1']").val();
                    if (val == undefined) {
                        $(".add_jump_type").append("<option value='1'>视频</option>");
                    }
                });
                $('.add_jump_type').on("change", function() {
                    if (parseInt($(this).val()) == 2) {
                        if (html_node.find(".add_pic_usage_type").val() == 302) {
                            $('.add_jump_url').val('http://www.leo1v1.com/service_chat_panel.html');
                            $('.add_jump_url').attr("disabled","disabled");
                        }
                        if (html_node.find(".add_pic_usage_type").val() == 303) {
                            $('.add_jump_url').val('http://m.leo1v1.com/chat.html');
                            $('.add_jump_url').attr("disabled","disabled");
                        }
                    } else {
                        $('.add_jump_url').val('');
                        $('.add_jump_url').removeAttr('disabled');
                    }
                });


                                    custom_qiniu_upload("id_upload_add_tmp","id_container_add_tmp",
                                    g_args.qiniu_upload_domain_url,true,
                                    function (up, info, file){
                                        console.log(info);
                                        var res = $.parseJSON(info);
                                        pic_url = g_args.qiniu_upload_domain_url + res.key;
                                        pic_img="<img width=80 src=\""+pic_url+"\"/>";
                                        html_node.find(".add_header_img").html(pic_img);
                                        html_node.find(".pic_url").html(pic_url + "<button class='del_img'>删除</button>");
                                        $('.del_img').on("click", function(){
                                            html_node.find(".add_header_img").html('');
                                            html_node.find(".pic_url").html('');
                                        });
                                    });

                $('#id_upload_add_tmp').on('click', function() {
                    custom_qiniu_upload("id_upload_add_tmp","id_container_add_tmp",
                                    g_args.qiniu_upload_domain_url,true,
                                    function (up, info, file){
                                        console.log(info);
                                        var res = $.parseJSON(info);
                                        pic_url = g_args.qiniu_upload_domain_url + res.key;
                                        pic_img="<img width=80 src=\""+pic_url+"\"/>";
                                        html_node.find(".add_header_img").html(pic_img);
                                        html_node.find(".pic_url").html(pic_url + "<button class='del_img'>删除</button>");
                                        $('.del_img').on("click", function(){
                                            html_node.find(".add_header_img").html('');
                                            html_node.find(".pic_url").html('');
                                        });
                                    });

                });

                // custom_qiniu_upload("id_upload_add_tmp","id_container_add_tmp",
                //                     g_args.qiniu_upload_domain_url,true,
                //                     function (up, info, file){
                //                         console.log(info);
                //                         var res = $.parseJSON(info);
                //                         pic_url = g_args.qiniu_upload_domain_url + res.key;
                //                         pic_img="<img width=80 src=\""+pic_url+"\"/>";
                //                         html_node.find(".add_header_img").html(pic_img);
                //                         html_node.find(".pic_url").html(pic_url + "<button class='del_img'>删除</button>");
                //                         $('.del_img').on("click", function(){
                //                             html_node.find(".add_header_img").html('');
                //                             html_node.find(".pic_url").html('');
                //                         });
                //                     });


                // custom_qiniu_upload("id_upload_tag_add_tmp","id_container_tag_add_tmp",
                //                     g_args.qiniu_upload_domain_url , true,
                //                     function (up, info, file){
                //                         var res = $.parseJSON(info);
                //                         tag_url = g_args.qiniu_upload_domain_url + res.key;
                //                         tag_img="<img width=80 src=\""+tag_url+"\" />";
                //                         html_node.find(".add_header_tag_img").html(tag_img);
                //                     });
            },
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        if (opt_type=="update") {
		                        var id =  item.id;
                        }
                        var grade        = html_node.find(".add_pic_grade").val();
                        var subject      = html_node.find(".add_pic_subject").val();
                        var name         = html_node.find(".add_pic_name").val();
                        var type         = html_node.find(".add_pic_type").val();
                        var usage_type   = html_node.find(".add_pic_usage_type").val();
                        var click_status = html_node.find(".add_pic_click_status").val();
                        var order_by     = html_node.find(".add_pic_order_by").val();
                        var jump_url     = html_node.find(".add_jump_url").val();
                        var jump_type    = html_node.find(".add_jump_type").val();
                        var title_share  = html_node.find(".add_title_share").val();
                        var info_share   = html_node.find(".add_info_share").val();
                        var start_time   = html_node.find(".add_start_date").val();
                        var end_time     = html_node.find(".add_end_date").val();
                        if (!name || name.length > 30) {
                            alert('图片名称不能为空并长度不能超过30字符');
                            return false;
                        }
                        if (!start_time) {
                            alert("请选择开始时间");
                            return false;
                        }
                        if (!end_time) {
                            alert("请选择结束时间");
                            return false;
                        }
                        if (start_time >= end_time) {
                            alert("结束时间必须大于开始时间");
                            return false;
                        }
                        if (click_status == 1) { //处理可点击
                            if (!jump_url) {
                                alert("请输入跳转地址");
                                return false;
                            }
                        }
                        if (!pic_url) {
                            alert('图片不存在');
                            return false;
                        }
                        if(usage_type==207 || usage_type==211){
                            grade   = html_node.find(".add_pic_grade").val();
                            subject = html_node.find(".add_pic_subject").val();
                        }
                        $.ajax({
		                      type     : "post",
		                      url      : "/pic_manage/add_pic_info",
		                      dataType : "json",
		                      data : {
                                "opt_type"      : opt_type 
                                ,"id"           : id
                                ,"name"         : name
                                ,"type"         : type
                                ,"usage_type"   : usage_type
                                ,"click_status" : click_status
                                ,"order_by"     : order_by
                                ,"subject"      : subject
                                ,"grade"        : grade
                                ,"pic_url"      : pic_url 
                                //,"tag_url"      : tag_url 
                                ,"jump_url"     : jump_url 
                                //,"title_share"  : title_share
                                //,"info_share"   : info_share
                                ,"start_time"   : start_time 
                                ,"end_time"     : end_time 
                                ,"jump_type"    : jump_type 
                            },
			                      success : function(result){
                                if (result.ret == -1) {
                                    alert(result.info);
                                }

                                if(result.ret==0){
                                    window.location.reload();
                                }
                                // else{
                                //     dialog.close();
                                // }
			                      }
                        });
                    }
                },{
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });

        $("body").on("change",".add_pic_click_status",function(){
            var status = $(this).val();
            if(status==0){
                html_node.find(".share_s").hide();
            }else{
                html_node.find(".share_s").show();
            }
        });

    };

    $(".add_pic_info").on("click",function(){ // 添加数据
        do_add_or_update("add");
    });

    $(".opt-update-pic_info").on("click",function(){
        var id=$(this).get_opt_data( "id" );
        $.ajax({
            type     :"post",
			      url      :"/pic_manage/get_pic_info",
			      dataType :"json",
			      data     :{"id":id},
            success: function(data){
                console.log(data.ret_info);
                do_add_or_update("update", data.ret_info);
            }
        });
    });

    
    $(".opt-del").on("click",function(){
        var id=$(this).get_opt_data( "id" );
        BootstrapDialog.show({
            title: '删除信息',
            message : "确定删除?",
            closable: true, 
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
                    $.do_ajax('/pic_manage/del_pic_info', {'id':id});
		            // $.ajax({
			          //   type     :"post",
			          //   url      :"/pic_manage/del_pic_info",
			          //   dataType :"json",
			          //   data     :{"id":id},
			          //   success  : function(result){
                //             window.location.reload();
                //         }
		            // });
                    dialog.close();
                }
            }, {
                label: '取消',
                cssClass: 'btn',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    });

    
});
