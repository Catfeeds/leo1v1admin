 /// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-assistant_leader_new.d.ts" />

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
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    $("#id_opt_date_type").find("option").each(function(){
        if($(this).val() !=1 && $(this).val() !=2 &&  $(this).val() !=3){
            $(this).remove();
        }
    });

    function show_top( $person_body_list) {

        $($person_body_list[0]).find("td").css(
            {
                "color" :"red"
            }
        );
        $($person_body_list[1]).find("td").css(
            {
                "color" :"orange"
            }
        );

        $($person_body_list[2]).find("td").css(
            {
                "color" :"blue"
            }
        );

    }

    show_top( $("#id_ass_list > tr")) ;
    show_top( $("#id_ass_list_group > tr")) ;
    show_top( $("#id_ass_group > tr")) ;


    $(".per").on("click",function(){
        //alert(1111);
        var per= $(this).data("per");
        //alert("完成率:"+per);
        BootstrapDialog.alert("完成率:"+per+"%");


    });

    $(".opt_kk_suc").on("click",function(){
        var uid= $(this).data("uid");
        if(uid > 0){
            var title = "扩课成功学生详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>userid</td><td>学生</td><td>科目</td><td>老师</td><td>第一次常规课时间</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_ass_stu_kk_suc_info',{
                "adminid"  : uid,
                "start_time" : g_args.start_time,
                "end_time"   : g_args.end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var userid = item["userid"];
                    var nick     = item["nick"]
                    var time     = item["time"];
                    var subject  = item["subject_str"];
                    var realname    = item["realname"];
                    html_node.find("table").append("<tr><td>"+userid+"</td><td>"+nick+"</td><td>"+subject+"</td><td>"+realname+"</td><td>"+time+"</td></tr>");
                });
            });

            var dlg=BootstrapDialog.show({
                title:title,
                message :  html_node   ,
                closable: true,
                buttons:[{
                    label: '返回',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();

                    }
                }],
                onshown:function(){

                }
            });

            dlg.getModalDialog().css("width","1024px");
        }

    });


    var init_noit_btn=function( id_name, title ,type) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value = parseInt( btn.text() );
        if (value >0 ) {
            btn.addClass("btn-warning");
        }
        btn.attr('data-warning', type);
    };

    init_noit_btn("warning-one", "预警1～5","1" );
    init_noit_btn("warning-two", "预警5～7", "2" );
    init_noit_btn("warning-three", "预警超时", "3" );
    $(".opt-warning-type").on("click",function(){

        window.location.href="/user_manage_new/ass_revisit_warning_info?revisit_warning_type="+$(this).attr('data-warning');
    });


    $('.opt-change').set_input_change_event(load_data);
});
