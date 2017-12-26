/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-market_extension.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        order_by_str : g_args.order_by_str,
        type:	$('#id_type').val(),
        date_type_config:	$('#id_date_type_config').val(),
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val()
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

    Enum_map.append_option_list("market_gift_type",$("#id_type"));
    $('#id_type').val(g_args.type);

    $('#id_add').on("click", function (g_adminid_right) {
        var opt_data=$(this).get_opt_data();

        var $main_type_name = $("<select/>");
        var $title = $("<textarea style='width:100%' />");
        var $describe = $("<textarea style='width:100%'/>");
        var $img = $("<div/>");
        var $img_src1 = $("<input />");
        var $img_src2 = $("<input />");
        var $img_src3 = $("<input />");
        var $img_src4 = $("<input />");

        //http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png

        $img.html("<div> <div>PNG格式</div>     <div style='margin-top:1rem;'>   <div style='float:left'> <span style='margin-right:0.5rem;'>封面页</span><img id='id_img1' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span style=' font-size:0.2rem;'>尺寸:300X300</span></div>   <div style='float:right'> <span style='margin-right:0.5rem;'>活动页</span><img id='id_img2' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span style=' font-size:0.2rem;'>尺寸:750X1334</span></div><div style='clear:both'></div> </div>  <div style='margin-top:2rem;'>   <div style='float:left'> <span style='margin-right:0.5rem;'>分享页</span><img id='id_img3' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span style=' font-size:0.2rem;'>尺寸:750X1344</span></div>   <div style='float:right'> <span style='margin-right:0.5rem;'>关注页</span><img id='id_img4' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span style=' font-size:0.2rem;'>尺寸:750X1334</span></div> </div>  </div>");




        Enum_map.append_option_list("market_gift_type", $main_type_name,true);

        //处理key
        $.do_ajax("/user_deal/seller_init_group_info", {
        }, function (ret) {
        });

        var arr = [
            ["礼品类型", $main_type_name],
            ["标题", $title],
            ["活动描述", $describe],
            ["活动图片", $img],
            ["图片1",$img_src1],
            ["图片2",$img_src2],
            ["图片3",$img_src3],
            ["图片4",$img_src4]
        ];

        $.show_key_value_table("添加推广活动", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                if(!$main_type_name.val()){ alert('请选择礼品类型!'); return; }
                if(!$title.val()){ alert('请填写活动标题!'); return; }
                if(!$describe.val()){ alert('请填写活动描述!'); return; }
                if(!$img_src1.val() && !$img_src2.val() && !$img_src3.val() && !$img_src4.val() ){ alert('请选择活动图片!'); return; }

                $.do_ajax("/ss_deal/addMarketExtend",{
                    'gift_type' : $main_type_name.val(),
                    'title'     : $title.val(),
                    'act_descr' : $describe.val(),
                    'shareImgUrl' : $img_src3.val(),
                    'coverImgUrl' : $img_src1.val(),
                    'followImgUrl' : $img_src4.val(),
                    'activityImgUrl' : $img_src2.val(),
                });
            }
        },function(){
            $img_src1.parent().parent().css('display','none');
            $img_src2.parent().parent().css('display','none');
            $img_src3.parent().parent().css('display','none');
            $img_src4.parent().parent().css('display','none');

            $.custom_upload_file('id_img1',true,function (up, info, file) { // 封面页
                var res = $.parseJSON(info);
                $img_src1.val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

            $.custom_upload_file('id_img2',true,function (up, info, file) { // 活动页
                var res = $.parseJSON(info);
                $img_src2.val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

            $.custom_upload_file('id_img3',true,function (up, info, file) { // 分享页
                var res = $.parseJSON(info);
                $img_src3.val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

            $.custom_upload_file('id_img4',true,function (up, info, file) { // 关注页
                var res = $.parseJSON(info);
                $img_src4.val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
        });
    });


    $('.opt-del').on("click",function(g_adminid_right){
        var data = $(this).get_opt_data();
        var id = data.id;
        if(confirm("确定要删除此活动吗?")){
            $.do_ajax("/user_deal/delMarketExtend", {
                "id" : id
            }, function (ret) {
                load_data();
            });
        }
    });



    $('.opt-edit').on("click", function (g_adminid_right) {
        var opt_data=$(this).get_opt_data();
        var id = opt_data.id;
        $.do_ajax("/user_deal/editMarketExtend",{
            'id' : id
        },function(ret){
            var $main_type_name = $("<select/>");
            var $title = $("<textarea style='width:100%' />");
            var $describe = $("<textarea style='width:100%'/>");
            var $img = $("<div/>");
            var $img_src1 = $("<input />");
            var $img_src2 = $("<input />");
            var $img_src3 = $("<input />");
            var $img_src4 = $("<input />");

            $img.html("<div> <div>PNG格式</div>     <div style='margin-top:1rem;'>   <div style='float:left'> <span style='margin-right:0.5rem;'>封面页</span><img id='id_img1' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span style=' font-size:0.2rem;'>尺寸:300X300</span></div>   <div style='float:right'> <span style='margin-right:0.5rem;'>活动页</span><img id='id_img2' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span style=' font-size:0.2rem;'>尺寸:750X1334</span></div><div style='clear:both'></div> </div>  <div style='margin-top:2rem;'>   <div style='float:left'> <span style='margin-right:0.5rem;'>分享页</span><img id='id_img3' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span style=' font-size:0.2rem;'>尺寸:750X1344</span></div>   <div style='float:right'> <span style='margin-right:0.5rem;'>关注页</span><img id='id_img4' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span style=' font-size:0.2rem;'>尺寸:750X1334</span></div> </div>  </div>");




            Enum_map.append_option_list("market_gift_type", $main_type_name,true);

            //处理key
            $.do_ajax("/user_deal/seller_init_group_info", {
            }, function (ret) {
            });

            var arr = [
                ["礼品类型", $main_type_name],
                ["标题", $title],
                ["活动描述", $describe],
                ["活动图片", $img],
                ["图片1",$img_src1],
                ["图片2",$img_src2],
                ["图片3",$img_src3],
                ["图片4",$img_src4]
            ];

            $.show_key_value_table("添加推广活动", arr, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function (dialog) {
                    $.do_ajax("/ss_deal/addMarketExtend",{
                        'gift_type' : $main_type_name.val(),
                        'title'     : $title.val(),
                        'act_descr' : $describe.val(),
                        'shareImgUrl' : $img_src3.val(),
                        'coverImgUrl' : $img_src1.val(),
                        'followImgUrl' : $img_src4.val(),
                        'activityImgUrl' : $img_src2.val(),
                    });
                }
            },function(){
                $img_src1.parent().parent().css('display','none');
                $img_src2.parent().parent().css('display','none');
                $img_src3.parent().parent().css('display','none');
                $img_src4.parent().parent().css('display','none');

                $.custom_upload_file('id_img1',true,function (up, info, file) { // 封面页
                    var res = $.parseJSON(info);
                    $img_src1.val(res.key);
                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

                $.custom_upload_file('id_img2',true,function (up, info, file) { // 活动页
                    var res = $.parseJSON(info);
                    $img_src2.val(res.key);
                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

                $.custom_upload_file('id_img3',true,function (up, info, file) { // 分享页
                    var res = $.parseJSON(info);
                    $img_src3.val(res.key);
                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

                $.custom_upload_file('id_img4',true,function (up, info, file) { // 关注页
                    var res = $.parseJSON(info);
                    $img_src4.val(res.key);
                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
            });
        });
    });

    $('.opt-show').on("click",function(g_adminid_right){
        var opt_data=$(this).get_opt_data();
        var id = opt_data.id;
        $.do_ajax("/user_deal/showMarketExtendImg",{
            'id' : id
        },function(ret){

            console.log(ret.data);

            return ;
            var $main_type_name = $("<select/>");
            var $title = $("<textarea style='width:100%' />");
            var $describe = $("<textarea style='width:100%'/>");
            var $img = $("<div/>");
            var $img_src1 = $("<input />");
            var $img_src2 = $("<input />");
            var $img_src3 = $("<input />");
            var $img_src4 = $("<input />");

            $img.html("<div> <div>PNG格式</div>     <div style='margin-top:1rem;'>   <div style='float:left'> <span style='margin-right:0.5rem;'>封面页</span><img id='id_img1' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span style=' font-size:0.2rem;'>尺寸:300X300</span></div>   <div style='float:right'> <span style='margin-right:0.5rem;'>活动页</span><img id='id_img2' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span style=' font-size:0.2rem;'>尺寸:750X1334</span></div><div style='clear:both'></div> </div>  <div style='margin-top:2rem;'>   <div style='float:left'> <span style='margin-right:0.5rem;'>分享页</span><img id='id_img3' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span style=' font-size:0.2rem;'>尺寸:750X1344</span></div>   <div style='float:right'> <span style='margin-right:0.5rem;'>关注页</span><img id='id_img4' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span style=' font-size:0.2rem;'>尺寸:750X1334</span></div> </div>  </div>");




            Enum_map.append_option_list("market_gift_type", $main_type_name,true);

            //处理key
            $.do_ajax("/user_deal/seller_init_group_info", {
            }, function (ret) {
            });

            var arr = [
                ["礼品类型", $main_type_name],
                ["标题", $title],
                ["活动描述", $describe],
                ["活动图片", $img],
                ["图片1",$img_src1],
                ["图片2",$img_src2],
                ["图片3",$img_src3],
                ["图片4",$img_src4]
            ];

            $.show_key_value_table("添加推广活动", arr, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function (dialog) {
                    $.do_ajax("/ss_deal/addMarketExtend",{
                        'gift_type' : $main_type_name.val(),
                        'title'     : $title.val(),
                        'act_descr' : $describe.val(),
                        'shareImgUrl' : $img_src3.val(),
                        'coverImgUrl' : $img_src1.val(),
                        'followImgUrl' : $img_src4.val(),
                        'activityImgUrl' : $img_src2.val(),
                    });
                }
            },function(){
                $img_src1.parent().parent().css('display','none');
                $img_src2.parent().parent().css('display','none');
                $img_src3.parent().parent().css('display','none');
                $img_src4.parent().parent().css('display','none');
            });
        });
    });


    $('.opt-change').set_input_change_event(load_data);
});
