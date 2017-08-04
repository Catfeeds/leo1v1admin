/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-qc_complaint.d.ts" />

$(function(){

            //实例化一个plupload上传对象
    var uploader = $.plupload_Uploader({
        browse_button : 'id_upload_xls', //触发文件选择对话框的按钮，为那个元素id
        url : '/wx_parent_gift/upload_excel', //服务器端的上传页面地址
        flash_swf_url : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        filters: {
            mime_types : [ //只允许上传图片和zip文件
                { title : "xls files", extensions : "xls" },
                { title : "xlsx files", extensions : "xlsx" }
            ],
            max_file_size : '40m', //最大只能上传400kb的文件
            prevent_duplicates : true //不允许选取重复文件
        }
    });

    uploader.init();
    uploader.bind('FilesAdded',function(up, files) {
        uploader.start();
    });


    function load_data(){
        $.reload_self_page ( {
            account_type:	$('#id_account_type').val(),
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            is_complaint_state:	$('#id_is_complaint_state').val(),

        });
    }




    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    Enum_map.append_option_list( "complaint_state", $("#id_is_complaint_state"));
    Enum_map.append_option_list( "complaint_user_type", $("#id_account_type"));

    $('#id_account_type').val(g_args.account_type);
    $('#id_is_complaint_state').val(g_args.is_complaint_state);




    $(".opt-assign").on("click",function(){
        var data            = $(this).get_opt_data();
        var id_complaint_deparment = $("<select>");
        var id_ass_remark          = $("<textarea>");
        var arr             = [
            ["处理人",id_complaint_deparment],
            ["分配备注",id_ass_remark],
        ];

        var set_select = function ($select) {
            $.do_ajax("/ss_deal/get_group_admin_name", {
            }, function (ret) {
                var sel_v = $select.val();
                $select.html("");
                $select.append("<option value=\"\">[全部]</option>");
                $.each(ret.list, function () {
                    var account    = this.account;
                    var group_name = this.group_name;
                    var account_role_str = this.account_role_str;
                    var master_adminid   = this.master_adminid;
                    $select.append("<option value=\"" + master_adminid + "\">" + account_role_str+' - '+group_name+' - '+account + "</option>");
                });
            });
        };

        set_select(id_complaint_deparment);

        $.show_key_value_table("分配部门",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/ss_deal/do_complaint_assign",{
                    "accept_adminid"      : id_complaint_deparment.val(),
                    "ass_remark"          : id_ass_remark.val(),
                    "complaint_id"        : data.complaint_id,
                },function(result){
                    BootstrapDialog.alert(result.info);
                    dialog.close();
                    load_data();
                });
            }
        });

    });



    $(".opt-assign_remark").on("click",function(){

        var data            = $(this).get_opt_data();
        var complaint_id    = data.complaint_id;
        var html_node    = $.obj_copy_node("#id_assign_log");

        BootstrapDialog.show({
            title: "分配列表",
            message: html_node,
            closable: true
        });

        $.ajax({
            type: "post",
            url: "/ss_deal/get_assign_log",
            dataType: "json",
            data: {
                'complaint_id': complaint_id,
            },
            success: function (result) {
                if (result['ret'] == 0) {
                    var data = result['data'];

                    var html_str = "";
                    $.each(data, function (i, item) {
                        var cls = "success";

                        html_str += "<tr class=\"" + cls + "\" > <td>" + item.ass_date + "<td>" + item.assign_str + "<td>" + item.accept_str + "<td>" + item.assign_remarks+ "</tr>";
                    });

                    html_node.find(".data-body").html(html_str);

                }
            }
        });

    });


    $('.opt-complaint-all').on("click",function(){
        var data = $(this).get_opt_data();
        $.show_key_value_table("投诉明细",[
            ["投诉类型" , data.complaint_type_str],
            ["投诉人身份" , data.account_type_str ],
            ["投诉时间" , data.complaint_date ],
            ["投诉人姓名/电话" , data.user_nick +'/'+ data.phone ],
            ["被投诉人" , data.complained_adminid_nick ],
            ["处理人" , data.deal_admin_nick ],
            ["投诉处理时间" , data.deal_date ],
            ["投诉内容" , data.complaint_info ],
            ["处理方案" , data.deal_info ],
            ["投诉人建议" , data.suggest_info ],
            ["处理状态" , data.complaint_state_str ],
        ]);
    });


    $('.opt-del').on("click",function(){
        var data = $(this).get_opt_data();

        if(confirm("确定要删除此条投诉吗？")){
            $.do_ajax("/ss_deal/del_complaint", {
                'complaint_id':data.complaint_id,
            }, function (result) {
                if(result.ret==0){
                    load_data();
                }else{
                    BootstrapDialog.alert(result.info);
                }

            });
        }

    });


    $('.opt-change').set_input_change_event(load_data);








});
