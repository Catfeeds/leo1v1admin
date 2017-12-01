/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-flow_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
      flow_check_flag:	$('#id_flow_check_flag').val(),
      post_adminid:	$('#id_post_adminid').val(),
      flow_type:	$('#id_flow_type').val(),
      date_type:	$('#id_date_type').val(),
      opt_date_type:	$('#id_opt_date_type').val(),
      start_time:	$('#id_start_time').val(),
      end_time:	$('#id_end_time').val()
        });
    }

  Enum_map.append_option_list("flow_type",$("#id_flow_type"));
  Enum_map.append_option_list("flow_check_flag",$("#id_flow_check_flag"));

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        onQuery :function() {
            load_data();
        }
    });
  $('#id_post_adminid').val(g_args.post_adminid);
  $('#id_flow_type').val(g_args.flow_type);
  $('#id_flow_check_flag').val(g_args.flow_check_flag);


    $.admin_select_user(
        $('#id_post_adminid'),
        "admin", load_data );


  $('.opt-change').set_input_change_event(load_data);

    $(".opt-check").on("click",function(){
        var opt_data=$(this).get_opt_data();

        $.do_ajax("/self_manage/flow_table_data",{
            flowid:opt_data.flowid,
            flow_type:opt_data.flow_type
        },function(resp){
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
            function set_flow_check_flag(flow_check_flag,dialog) {
                var str=Enum_map.get_desc("flow_check_flag", flow_check_flag);
                $.show_input("审核说明:"+str+"?", "",function( v){
                    $.do_ajax("/self_manage/flow_node_set_check_flag", {
                        nodeid : opt_data.nodeid,
                        flow_check_flag : flow_check_flag,
                        check_msg: v
                    });
                } , $("<input style=\"width:400px\"  placeholder=\"理由说明\"/>"));
            }
            var all_btn_config=[{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            },{
                label: '驳回',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    if (opt_data.flow_check_flag==1 ){
                        alert("已经通过,不能修改") ;
                        return;
                    }

                    set_flow_check_flag(3,dialog );
                }

            },{
                label: '不通过',
                cssClass: 'btn-danger',
                action: function(dialog) {
                    if (opt_data.flow_check_flag==1 ){
                        alert("已经通过,不能修改") ;
                        return;
                    }

                    set_flow_check_flag(2 ,dialog );
                }
            },{
                label: '通过',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    if (opt_data.flow_check_flag==1 ){
                        alert("已经通过,不能修改") ;
                        return;
                    }

                    set_flow_check_flag(1 ,dialog );
                }

            }];

            BootstrapDialog.show({
                title: "审核",
                message :  table_obj ,
                closable: true,
                buttons: all_btn_config
            });


        });


    });

    $(".opt-flow-node-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.flow_show_node_list( opt_data.flowid);

    });


    $(".opt-flow-def-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.flow_show_define_list( opt_data.flowid);
    });


});
