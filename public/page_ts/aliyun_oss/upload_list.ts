/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/aliyun_oss-upload_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        date_type_config:   $('#id_date_type_config').val(),
        date_type:  $('#id_date_type').val(),
        opt_date_type:  $('#id_opt_date_type').val(),
        start_time: $('#id_start_time').val(),
        end_time:   $('#id_end_time').val()
    });
}
$(function(){


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
    $('#id_add_file1').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open(
              '/aliyun_oss/upload_page'
          );
    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("确定删除["+opt_data.file_path+""+opt_data.version_name+"],点击确认应用包将被替换",function(val){
            if(val){
                $.do_ajax("/aliyun_oss/update_status",{
                    "id" : opt_data.id
                });
            }
        });
    });

    $("#id_add_file").on("click",function(){
        var opt_data = $(this).get_opt_data;
        var id_file_type        = $("<select/>");  //文件类型
        var id_version          = $("<input/>");   //版本号

        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\"> </a>   </div>");
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;
        $upload_link.attr('href',"");

        var $upload_div_b2  = $("<div > <button id=\"id_upload_from_url_b2\" > 上传</button>  <a href=\"\" target=\"_blank\"> </a>   </div>");
        var $upload_btn_b2  = $upload_div_b2.find("button") ;
        var $upload_link_b2 = $upload_div_b2.find("a") ;
        $upload_link_b2.attr('href',"");

        var $upload_div_b3  = $("<div > <button id=\"id_upload_from_url_b3\" > 上传</button>  <a href=\"\" target=\"_blank\"> </a>   </div>");
        var $upload_btn_b3  = $upload_div_b3.find("button") ;
        var $upload_link_b3 = $upload_div_b3.find("a") ;
        $upload_link_b3.attr('href',"");

        Enum_map.append_option_list("file_type", id_file_type, true);
        var arr = [
            ["类型",id_file_type],
            ["版本号",id_version],
        ];
        arr.push(['Windows exe文件',$upload_div]);
        arr.push(['yml文件',$upload_div_b2]);
        arr.push(['Mac     dmg文件',$upload_div_b3]);

        $.show_key_value_table("请填写软件信息", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){    
                if( id_version.val() !== '' && ( 
                    ($upload_link.attr('href') !==  '' && $upload_link_b2.attr('href') !== '' && $upload_link_b3.attr('href') !== '') 
                        || ($upload_link.attr('href') !==  '' && $upload_link_b2.attr('href') !== '' && $upload_link_b3.attr('href') === '')
                        || ($upload_link.attr('href') ===  '' && $upload_link_b2.attr('href') === '' && $upload_link_b3.attr('href') !== ''))){

                }else{
                    alert("请将所有内容都填充完整");
                    return;
                }
                $.do_ajax("/aliyun_oss/add_file",{
                    "version"       : id_version.val(),
                    "file_url"      : $upload_link.attr('href'),
                    "file_url_yml"  : $upload_link_b2.attr('href'),
                    "file_url_dmg"  : $upload_link_b3.attr('href'),
                });
            }
        },function(){
            $.custom_upload_file_soft(
                        1,
                        "id_upload_from_url" ,
                        true,
                        function( up, info, file ){
                            var res = $.parseJSON(info);
                            var url=res.key;
                            $.do_ajax("/common_new/get_qiniu_download",{
                                "file_url" :res.key ,
                                "public_flag" :1,
                            }, function(resp){
                                $upload_link.attr("href", resp.url);
                                $upload_link.html("查看");
                            })
                        }, null,
                        ["exe"] );
            $.custom_upload_file_soft(
                        1,
                        "id_upload_from_url_b2" ,
                        true,
                        function( up, info, file ){
                            var res = $.parseJSON(info);
                            var url=res.key;
                            $.do_ajax("/common_new/get_qiniu_download",{
                                "file_url" :res.key ,
                                "public_flag" :1,
                            }, function(resp){
                                $upload_link_b2.attr("href", resp.url);
                                $upload_link_b2.html("查看");
                            })
                        }, null,
                        ["yml"] );
            $.custom_upload_file_soft(
                        1,
                        "id_upload_from_url_b3" ,
                        true,
                        function( up, info, file ){
                            var res = $.parseJSON(info);
                            var url=res.key;
                            $.do_ajax("/common_new/get_qiniu_download",{
                                "file_url" :res.key ,
                                "public_flag" :1,
                            }, function(resp){
                                $upload_link_b3.attr("href", resp.url);
                                $upload_link_b3.html("查看");
                            })
                        }, null,
                        ["dmg"] );
        })
    });
    $('.opt-change').set_input_change_event(load_data);
});
