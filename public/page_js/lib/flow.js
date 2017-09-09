/// <reference path="../common.d.ts" />
jQuery.extend({
    flow_dlg_show:function (title, add_func , flow_type,  from_key_int,  from_key2_int, from_key_str ) {

        $.do_ajax("/user_deal/get_flow_info_by_key",{
           from_key_int :from_key_int,
            flow_type:flow_type
        },function(resp){

            var arr=resp.table_data;
            if (  resp.node_list.length)  {
                arr.push([$("<font color=blue>审核人/时间</font>"), $("<font color=blue>审核状态/说明</font>")]);
            }


            $.each(resp.node_list,function(i,item){
                arr.push([$( "<div>"+item["admin_nick"]+"<br/>" + item["check_time"] +"</div>" ),  $("<div> " +item["node_name"]+ ":" +item["flow_check_flag_str"]  +"<br/>" +  item["check_msg"] + "</div>") ]);
            });

            var table_obj=$("<table class=\"table table-bordered table-striped\"  > <tr> <thead> <td style=\"text-align:right;\">属性  </td>  <td> 值 </td> </thead></tr></table>");

            $.each(arr , function( index,element){
                var row_obj=$("<tr> </tr>" );
                var td_obj=$( "<td style=\"text-align:right; width:30%;\"></td>" );
                var v=element[0] ;
                td_obj.append(v);
                row_obj.append(td_obj);
                td_obj=$( "<td ></td>" );

                td_obj.append( element[1] );
                row_obj.append(td_obj);
                table_obj.append(row_obj);
            });

            var all_btn_config=[{
                label: '预期审核流程',
                action: function(dialog) {
                    $.flow_show_define_list( resp.flowid);
                }
            },{
                label: '申请',
                cssClass: "btn-primary",
                action: function(dialog) {
                    add_func();
                }
            },{

                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }];

            BootstrapDialog.show({
                title: "申请信息",
                message :  table_obj ,
                closable: true,
                buttons: all_btn_config
            });


        });

    },

    flow_show_node_list:function( flowid ){
        //$.ad
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"          : "/ajax_deal/get_flow_node_list_for_js",
            //其他参数
            "args_ex" : {
                flowid:flowid
            },
            //字段列表
            'field_list' :[
                {
                    title:"编号",
                    width :50,
                    field_name:"nodeid"
                },{
                    title:"节点",
                    field_name:"node_name"
                },{

                    title:"时间",
                    field_name:"add_time"
                },{
                    title:"审核者",
                    //width :50,
                    render:function(val,item) {
                        return item.admin_nick;
                    }
                },{
                    title:"审核时间",
                    render:function(val,item) {
                        return item.check_time;
                    }

                },{
                    title:"审核状态",
                    render:function(val,item) {
                        return item.flow_check_flag_str;
                    }
                },{
                    title:"说明",
                    render:function(val,item) {
                        return item.check_msg;
                    }

                }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null,

        });

    },

    flow_show_define_list:function( flowid ){
        //$.ad
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"          : "/ajax_deal/get_flow_list_for_js",
            //其他参数
            "args_ex" : {
                flowid:flowid
            },
            //字段列表
            'field_list' :[
                {
                    title:"编号",
                    width :50,
                    field_name:"node_type"
                },{
                    title:"说明",
                    field_name:"name"
                },{
                    title:"审核者",
                    //width :50,
                    render:function(val,item) {
                        return item.admin_nick;
                    }

                },{
                    title:"自动通过",
                    //width :50,
                    render:function(val,item) {
                        return item.auto_pass_flag?"<font color=red>是</font>": "否" ;
                    }
                }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null,

        });

    },

    flow_show_all_info: function (flowid) {

        $.do_ajax("/self_manage/flow_table_data",{
            flowid:flowid,
        },function(resp){
            if (resp.ret==-1) {
                alert(resp.info);
                return;
            }
            var arr=resp.table_data;
            arr.push([$("<font color=blue>审核人/时间</font>"), $("<font color=blue>审核状态/说明</font>")]);


            $.each(resp.node_list,function(i,item){
                arr.push([$( "<div>"+item["admin_nick"]+"<br/>" + item["check_time"] +"</div>" ),  $("<div> " +item["node_name"]+ ":" +item["flow_check_flag_str"]  +"<br/>" +  item["check_msg"] + "</div>") ]);
            });

            var table_obj=$("<table class=\"table table-bordered table-striped\"  > <tr> <thead> <td style=\"text-align:right;\">属性  </td>  <td> 值 </td> </thead></tr></table>");

            $.each(arr , function( index,element){
                var row_obj=$("<tr> </tr>" );
                var td_obj=$( "<td style=\"text-align:right; width:30%;\"></td>" );
                var v=element[0] ;
                td_obj.append(v);
                row_obj.append(td_obj);
                td_obj=$( "<td ></td>" );

                td_obj.append( element[1] );
                row_obj.append(td_obj);
                table_obj.append(row_obj);
            });
            var all_btn_config=[{

                label: '预期审核流程',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.flow_show_define_list(flowid);
                }},{

                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }];

            BootstrapDialog.show({
                title: "审核进度",
                message :  table_obj ,
                closable: true,
                buttons: all_btn_config
            });


        });

    }

});
