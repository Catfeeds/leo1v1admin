/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-qingjia.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        onQuery :function() {
            load_data();
        }
    });

    $("#id_add").on("click",function(){
        var $id_type =$("<select/>");
        var $id_start_time =$("<input/>");
        var $id_end_time =$("<input />");
        var $id_day_count=$("<input style=\"width:50px\"/>").val(0);
        var $id_hour_count=$("<input style=\"width:50px\" />").val(0);
        var $id_msg=$("<textarea/>");

        var $id_date_range=$('<div class="row  " >'+
                             '<div class="col-md-12 " >'+
                             '<div class="input-group" >'+
                             '</div>'+
                             '</div>'+
                             '</div>');
        var $id_hour_range=$('<div class="input-group" >'+
                             '</div>');

        Enum_map.append_option_list("qingjia_type",$id_type, true);
        ;

        $id_date_range.find(".input-group").append( $id_start_time );
        $id_date_range.find(".input-group").append("<span>-</span>" );
        $id_date_range.find(".input-group").append( $id_end_time );

        $id_hour_range.append( $id_day_count );
        $id_hour_range.append("<span>天</span>" );
        $id_hour_range.append( $id_hour_count );
        $id_hour_range.append("<span>小时</span>" );
        $id_start_time.val( $.DateFormat((new Date()).getTime()/1000   ,"yyyy-MM-dd") + " 00" );
        $id_end_time.val( $.DateFormat((new Date()).getTime()/1000   ,"yyyy-MM-dd") + " 00" );

        var arr=[
            ["类型", $id_type] ,
            ["时段"  , $id_date_range]  ,
            ["时长",  $id_hour_range ]  ,
            ["说明", $id_msg  ]  ,
        ];
        $.show_key_value_table("请假申请", arr,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var hour_count=parseInt( $id_day_count.val())*8+  parseInt($id_hour_count.val());
                if (!(hour_count>0)) {
                    alert("时长不对") ;
                    return;
                }
                $.do_ajax("/self_manage/qingjia_add",{
                    msg :　$id_msg.val(),
                    "type" :$id_type.val(),
                    start_time:　$id_start_time.val()+":00",
                    end_time:　$id_end_time.val()+":00",
                    hour_count:  hour_count
                });

            }
        },function( ){
            $id_start_time.datetimepicker({
                timepicker:true,
                format:'Y-m-d H',
                step:60,
            } ) ;
            $id_end_time.datetimepicker({
                timepicker:true,
                format:'Y-m-d H',
                step:60,
            } ) ;

        } );

    });



    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm("请假开始时间["+opt_data.start_time +" ] 要删除? ",function(val){
            if (val)  {
                $.do_ajax("/self_manage/qingjia_del",{
                    id: opt_data.id
                });

            }
        });
    });


    $(".opt-flow-def-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.flow_show_define_list( opt_data.flowid);
    });
    $(".opt-flow-node-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.flow_show_node_list( opt_data.flowid);
       //  $.flow_show_all_info( opt_data.flowid);

    });


    $('.opt-change').set_input_change_event(load_data);



});
