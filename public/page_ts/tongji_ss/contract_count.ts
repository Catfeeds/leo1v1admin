/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-contract_count.d.ts" />

$(function(){

    function load_data(){
        $.reload_self_page ( {
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            contract_type:	$('#id_contract_type').val(),
            is_test_user:	$('#id_is_test_user').val(),
            studentid:	$('#id_studentid').val(),
            check_money_flag:	$('#id_check_money_flag').val(),
            origin:	$('#id_origin').val(),
            from_type:	$('#id_from_type').val(),
            account_role:	$('#id_account_role').val(),
            seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
            sys_operator:	$('#id_sys_operator').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            date_type:	$('#id_date_type').val()
        });
    }

    $("#id_date_range").select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    Enum_map.append_option_list( "check_money_flag", $("#id_check_money_flag"));
    Enum_map.append_option_list( "contract_from_type", $("#id_from_type"));
    Enum_map.append_option_list( "contract_type", $("#id_contract_type"));
    Enum_map.append_option_list( "account_role", $("#id_account_role"));
    Enum_map.append_option_list("boolean",$("#id_is_test_user"));


    //init  input data
    $("#id_start_time").val(g_args.start_time);
    $("#id_end_time").val(g_args.end_time);
    $("#id_studentid").val(g_args.studentid);
    $("#id_check_money_flag").val(g_args.check_money_flag );
    $('#id_is_test_user').val(g_args.is_test_user);
    $("#id_origin").val(g_args.origin);
    $("#id_from_type").val(g_args.from_type);
    $("#id_contract_type").val(g_args.contract_type);
    $("#id_sys_operator").val(g_args.sys_operator);
    $("#id_account_role").val(g_args.account_role);
    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);

    $("#id_seller_groupid_ex").init_seller_groupid_ex(g_adminid_right);


    $("#id_studentid").admin_select_user({
        "type"   : "student",
        "onChange": function(){
            load_data(  );
        }
    });




    //时间控件
    $('#id_start_time').datetimepicker({
        lang:'ch',
        timepicker:true,
        format:'Y-m-d H:i',
        onChangeDateTime :function(){
            load_data();
        }
    });

    $('#id_end_time').datetimepicker({
        lang:'ch',
        timepicker:true,
        format:'Y-m-d H:i',
        onChangeDateTime :function(){
            load_data(
            );
        }
    });//时间控件-over



    $.each($(".l-2,.l-3,.l-4"),function(){
        $(this).hide();

    });


    var link_css=        {
        color: "#3c8dbc",
        cursor:"pointer"
    };

    $(".l-1 .main_type").css(link_css);
    $(".l-2 .up_group_name").css(link_css);
    $(".l-3 .group_name").css(link_css);

    $(".l-1 .main_type").on("click",function(){
        var $this=$(this);
        var class_name= $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".up_group_name."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".up_group_name."+class_name ).parent(".l-2");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );

    });

    $(".l-2 .up_group_name").on("click",function(){
        var $this=$(this);
        var class_name= $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".group_name."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".group_name."+class_name ).parent(".l-3");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });

    $(".l-3 .group_name").on("click",function(){
        var $this=$(this);
        var class_name= $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".account."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".account."+class_name ).parent(".l-4");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });


    $("#id_subject_pic,#id_subject_pic_count,#id_grade_pic,#id_grade_pic_count,#id_phone_pic,#id_phone_pic_count").css({
        "height" : 400
    });

    function labelFormatter(label, series) {
        return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
    }


    var gen_data=function ( map, field_str,id_str) {
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
        objectList.sort(function(a,b){
            return b[0]-a[0];
        });
        $.each(objectList,function(i,item){
            tbody.append(item[1]);
        });

        console.log(data);
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
    gen_data( g_subject_map,"subject","id_subject_pic");
    gen_data( g_subject_count_map,"subject","id_subject_pic_count");
    gen_data( g_grade_map,"grade","id_grade_pic");
    gen_data( g_grade_count_map,"grade","id_grade_pic_count");
    gen_data( g_phone_map,"","id_phone_pic");
    gen_data( g_phone_count_map,"","id_phone_pic_count");

    $('.opt-change').set_input_change_event(load_data);
    if(g_account=='龚隽' || g_account=="班洁"){
        download_show();
    }
});
