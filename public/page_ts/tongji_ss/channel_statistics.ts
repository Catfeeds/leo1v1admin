/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-origin_count.d.ts" />

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
        seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
        origin:	$('#id_origin').val(),
        is_history:	$('#id_is_history').val(),
        sta_data_type:	$('#id_sta_data_type').val()
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
            //load_data();
        }
    });
    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);

    $("#id_seller_groupid_ex").init_seller_groupid_ex();
    $('#id_tmk_adminid').val(g_args.tmk_adminid);
    $('#id_check_field_id').val(g_args.check_field_id);
    $('#id_is_history').val(g_args.is_history);
    $('#id_sta_data_type').val(g_args.sta_data_type);

    $("#id_subject_pic,#id_has_pad_pic,#id_grade_pic,#id_area_pic,#id_origin_level_pic,#id_order_area_pic,#id_order_grade_pic,#id_order_subject_pic,#id_order_has_pad_pic,#id_order_origin_level_pic,#id_test_area_pic,#id_test_subject_pic,#id_test_grade_pic,#id_test_has_pad_pic,#id_test_origin_level_pic").css({
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
    if(g_args.is_show_pie_flag == 1){
        gen_data( g_subject_map,"subject","id_subject_pic");
        gen_data( g_grade_map,"grade","id_grade_pic",true);
        gen_data( g_has_pad_map,"pad_type","id_has_pad_pic");
        gen_data( g_area_map,"","id_area_pic");
        gen_data( g_origin_level_map,"origin_level","id_origin_level_pic");
        gen_data( g_order_area_map, "", "id_order_area_pic");
        gen_data( g_order_subject_map, "subject", "id_order_subject_pic");
        gen_data( g_order_grade_map, "grade", "id_order_grade_pic");
        gen_data( g_order_has_pad_map,"pad_type","id_order_has_pad_pic");
        gen_data( g_order_origin_level_map,"origin_level","id_order_origin_level_pic");
        gen_data( g_test_area_map, "", "id_test_area_pic");
        gen_data( g_test_subject_map, "subject", "id_test_subject_pic");
        gen_data( g_test_grade_map, "grade", "id_test_grade_pic");
        gen_data( g_test_has_pad_map,"pad_type","id_test_has_pad_pic");
        gen_data( g_test_origin_level_map,"origin_level","id_test_origin_level_pic");
    }





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


    //$('.opt-change').set_input_change_event(load_data);

    // $(".common-table").table_group_level_more_init(8);

    // $(".common-table").table_group_level_4_init();
    $(".common-table").table_group_level_5_init();

    if ($.get_action_str()=="origin_count_bd") {
        $("#id_origin_ex").parent().parent().hide();
    }

    $("#id_query").on("click",function(){
        var sta_data_type = $('#id_sta_data_type').val();
        var is_history = $('#id_is_history').val();
        if(sta_data_type == 2 && is_history == 2){
            alert('节点型没有存档数据啊！');
            return false;
        }else{
            load_data();
        }

    });


    $("#id_align").on("click",function(){
        $(".common-table").tbody_scroll_table(500);
    });

    $('.opt-go-info').on('click', function(){
        var opt_type = $(this).attr('data-opt');
        var par = 'check_value=' + $(this).attr("data-val");
        var cond = 'cond='+$(this).attr('data-cond');
        if ($(this).attr("data-val") !== ''){
            if(location.search){
                if(cond)
                    $.wopen("/tongji_ss/origin_count_"+opt_type+"_info"+location.search+"&"+par+'&'+cond);
                else
                    $.wopen("/tongji_ss/origin_count_"+opt_type+"_info"+location.search+"&"+par);
            } else {
                $.wopen("/tongji_ss/origin_count_"+opt_type+"_info?"+par+'&'+cond);
            }
        }

    });

    //@desn:更新统计数据到存档数据
    $('#id_update').on('click',function(){
        var sta_data_type = $('#id_sta_data_type').val();
        var sta_data_type_str = sta_data_type == 1 ? '漏斗型':'节点型';
        BootstrapDialog.confirm(
            '该操作将会更新'+sta_data_type_str+"数据(请20秒后刷新页面或重新点击查询按钮)"+
                "<span style='color:red;'>（请勿多次操作!）</span>",
            function(val){
                if (val) {
                    $.do_ajax("/tongji_ss/update_archive_data",{
                        "sta_data_type" : sta_data_type
                    });

                }
            }
        );

    })

});
