/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-tmk_count.d.ts" />

function load_data(){
    $.reload_self_page ( {
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        check_field_id:	$('#id_check_field_id').val(),
        end_time:	$('#id_end_time').val(),
        admin_revisiterid:	$('#id_admin_revisiterid').val(),
        groupid:	$('#id_groupid').val(),
        tmk_adminid:	$('#id_tmk_adminid').val(),
        origin_ex:	$('#id_origin_ex').val(),
        origin_level:	$('#id_origin_level').val(),
        seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
        origin:	$('#id_origin').val()
    });
}

$(function(){

    $(".common-table").tbody_scroll_table(500);

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

    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);

    $("#id_seller_groupid_ex").init_seller_groupid_ex();
    $('#id_tmk_adminid').val(g_args.tmk_adminid);
    $('#id_check_field_id').val(g_args.check_field_id);


    $('#id_origin_level').val(g_args.origin_level);
    $.enum_multi_select( $('#id_origin_level'), 'origin_level', function(){load_data();},null, {
        "T类" : [90],
        "非T类" : [0,1,2,3,4,100],
    }  );

    $("#id_subject_pic,#id_has_pad_pic,#id_grade_pic,#id_area_pic,#id_origin_level_pic").css({
        "height" : 400
    });

    function labelFormatter(label, series) {
        return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
    }


    var gen_data=function ( map, field_str,id_str,no_sort_flag) {
        var data = [];
        var tbody=$("#"+id_str).parent().find("tbody");


        var objectList = new Array();
        $.each(map,function(i,v){
            var desc="";
            if (field_str) {
                desc=Enum_map.get_desc(field_str,i) ;
            }else{
                desc=i;
                i="";
            }
            data.push({
                label: desc,
                data: v
            });
            objectList.push([v, "<tr> <td> "+i+"</td>   <td> "+desc+" </td>  <td> "+v+" </td></tr>" ]) ;

        }) ;
        if(!no_sort_flag) {
            objectList.sort(function(a,b){
                return b[0]-a[0];
            });
        }
        $.each(objectList,function(i,item){
            tbody.append(item[1]);
        });


        $.plot('#'+id_str, data, {
            series: {
                pie: {
                    show: true,
                    radius: 1,
                    tilt: 0.5,
                    label: {
                        show: true,
                        radius: 1,
                        formatter: labelFormatter,
                        background: {
                            opacity: 0.8
                        }
                    },
                    combine: {
                        color: '#999',
                        threshold: 0.1
                    }
                }
            },
            legend: {
                show: false
            }
        });

    };


    $('#id_origin').val(g_args.origin);
    $('#id_origin_ex').val(g_args.origin_ex);
    $("#id_admin_revisiterid").val(g_args.admin_revisiterid);
    $("#id_groupid").val(g_args.groupid);
    $.admin_select_user(
        $('#id_admin_revisiterid'),
        "admin", function(){},false, {
            "main_type": 2,
            select_btn_config: [{
                "label": "[未分配]",
                "value": 0
            }]
        }
    );




    $.admin_select_user(
        $('#id_tmk_adminid'),
        "admin", function(){},false, {
            " main_type": 2,
            select_btn_config: [
                {
                    "label": "[已分配]",
                    "value": -2
                }, {
                    "label": "[未分配]",
                    "value": 0
                }]
        }
    );


    $('.opt-change').set_input_change_event(load_data);


    $(".common-table").table_group_level_4_init();

    if ($.get_action_str()=="origin_count_bd") {
        $("#id_origin_ex").parent().parent().hide();
    }

    $(".opt_order_tmk").on("click",function(){
        var tmk_adminid =  $(this).attr('data-tmk_adminid');
        var start_time  =  g_args.start_time;
        var end_time    =  g_args.end_time;

        window.open('/tongji_ss/order_tmk_info?adminid='+tmk_adminid+'&start_time='+start_time+'&end_time='+end_time);



    });



    $('.opt-get_assign_time').on("click",function(){
        var tmk_adminid = $(this).attr('data-tmk_adminid');
        var start_time  =  g_args.start_time;
        var end_time    =  g_args.end_time;

        var html_node    = $.obj_copy_node("#id_assign_log");

         BootstrapDialog.show({
            title: "已回访时间列表",
            message: html_node,
            closable: true
        });


        $.do_ajax('/ss_deal2/get_tmk_assign_time',{
            'tmk_adminid' : tmk_adminid,
            'start_time'  : start_time,
            'end_time'    : end_time

        },function(result){
            if (result['ret'] == 0) {
                var data = result['data'];

                var html_str = "";
                $.each(data, function (i, item) {
                    var cls = "success";

                    html_str += "<tr class=\"" + cls + "\" > <td>" + item.nick + " <td>" + item.tmk_assign_time_str + "<td>" + item.first_call_time_str + "<td>" + item.global_tq_called_flag_str  + "</tr>";
                });

                html_node.find(".data-body").html(html_str);
            }

        });
    });



});
