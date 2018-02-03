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
        var $follow_activ_type = $("<select/>");
        var $title = $("<textarea style='width:100%' />");
        var $describe = $("<textarea style='width:100%'/>");
        var $img = $("<div/>");
        var $img_src1 = $("<input />");
        var $img_src2 = $("<input />");
        var $img_src3 = $("<input />");
        var $img_src4 = $("<input />");
        var $follow_list = $("<div />");

        $img.html("<div> <div>PNG格式</div>     <div style='margin-top:1rem;'>   <div style='float:left'> <span style='margin-right:0.5rem;'>封面页</span> <div><img id='id_img1' style='width:54px;height:48px' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/> <span class='del_cover' style='display:none'>删除</span> </div>  <span style=' font-size:0.2rem;'>尺寸:300X300</span></div>   <div style='float:right'> <span style='margin-right:0.5rem;'>活动页</span> <div> <img id='id_img2' style='width:54px;height:48px' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span class='del_activity'  style='display:none'>删除</span></div><span style=' font-size:0.2rem;'>尺寸:750X1334</span></div><div style='clear:both'></div> </div>  <div style='margin-top:2rem;'>   <div style='float:left'> <span style='margin-right:0.5rem;'>分享页</span><div><img id='id_img3' style='width:54px;height:48px' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/> <span class='del_share'  style='display:none'>删除</span> </div><span style=' font-size:0.2rem;'>尺寸:750X1334</span></div>   <div style='float:right'> <span style='margin-right:0.5rem;'>关注页</span><div><img id='id_img4' style='width:54px;height:48px' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span class='del_follow'  style='display:none'>清空</span></div><span style=' font-size:0.2rem;'>尺寸:750X1334</span></div> </div>  </div>");

        Enum_map.append_option_list("market_gift_type", $main_type_name,true);

        var arr = [
            ["礼品类型", $main_type_name],
            ["标题", $title],
            ["活动描述", $describe],
            ["活动图片", $img],
            ["关注图片列表", $follow_list],
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
                var img_list = $follow_list.children();
                var img_list_str = '';
                $.each(img_list,function(i,item){
                    img_list_str = img_list_str+','+$(item.firstChild).attr('img_key');
                });

                $.do_ajax("/ss_deal/addMarketExtend",{
                    'gift_type' : $main_type_name.val(),
                    'title'     : $title.val(),
                    'act_descr' : $describe.val(),
                    'shareImgUrl' : $img_src3.val(),
                    'coverImgUrl' : $img_src1.val(),
                    'followImgUrl' : img_list_str,
                    'activityImgUrl' : $img_src2.val(),
                    // 'img_list_str'   : img_list_str
                });
            }
        },function(){
            $img_src1.parent().parent().css('display','none');
            $img_src2.parent().parent().css('display','none');
            $img_src3.parent().parent().css('display','none');
            $img_src4.parent().parent().css('display','none');
            $follow_list.parent().parent().css('display','none');


            $.custom_upload_file('id_img1',true,function (up, info, file) { // 封面页
                var res = $.parseJSON(info);
                $img_src1.val(res.key);
                if(res.key){
                    $('.del_cover').css('display','block');
                    $('#id_img1').attr('src','https://ybprodpub.leo1v1.com/'+res.key);
                }

            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

            $.custom_upload_file('id_img2',true,function (up, info, file) { // 活动页
                var res = $.parseJSON(info);
                $img_src2.val(res.key);
                if(res.key){
                    $('.del_activity').css('display','block');
                    $('#id_img2').attr('src','https://ybprodpub.leo1v1.com/'+res.key);
                }
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

            $.custom_upload_file('id_img3',true,function (up, info, file) { // 分享页
                var res = $.parseJSON(info);
                $img_src3.val(res.key);
                if(res.key){
                    $('.del_share').css('display','block');
                    $('#id_img3').attr('src','https://ybprodpub.leo1v1.com/'+res.key);
                }
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

            $.custom_upload_file('id_img4',true,function (up, info, file) { // 关注页
                var res = $.parseJSON(info);
                $img_src4.val(res.key);
                if(res.key){
                    $('.del_follow').css('display','block');
                    // $('#id_img4').attr('src','https://ybprodpub.leo1v1.com/'+res.key);
                    $follow_list.parent().parent().css('display','table-row');

                    $follow_list.append('<div style="margin:1rem;display:inline-block;"><img style="width: 54px; height: 48px; position: relative; z-index: 1;" img_key="'+res.key+'" src="https://ybprodpub.leo1v1.com/'+res.key+'"></div>');
                }
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);


            $('.del_cover').on("click",function(){
                $img_src1.val('');
                $('#id_img1').attr('src','http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png');
                $('.del_cover').css('display','none');
            });

            $('.del_activity').on("click",function(){
                $img_src2.val('');
                $('#id_img2').attr('src','http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png');
                $('.del_activity').css('display','none');
            });

            $('.del_share').on("click",function(){
                $img_src3.val('');
                $('#id_img3').attr('src','http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png');
                $('.del_share').css('display','none');
            });

            $('.del_follow').on("click",function(){
                $img_src4.val('');
                $('#id_img4').attr('src','http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png');
                $('.del_follow').css('display','none');
                $follow_list.html('');
                $follow_list.parent().parent().css('display','none');
            });
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
        var $main_type_name = $("<select/>");
        var $title = $("<textarea style='width:100%' />");
        var $describe = $("<textarea style='width:100%'/>");
        var $img = $("<div/>");
        var $img_src1 = $("<input />");
        var $img_src2 = $("<input />");
        var $img_src3 = $("<input />");
        var $img_src4 = $("<input />");
        var $follow_list = $("<div />");

        $img.html("<div> <div>PNG格式</div>     <div style='margin-top:1rem;'>   <div style='float:left'> <span style='margin-right:0.5rem;'>封面页</span> <div><img id='id_img1' style='width:54px;height:48px' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/> <span class='del_cover' style='display:none'>删除</span> </div>  <span style=' font-size:0.2rem;'>尺寸:300X300</span></div>   <div style='float:right'> <span style='margin-right:0.5rem;'>活动页</span> <div> <img id='id_img2' style='width:54px;height:48px' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span class='del_activity'  style='display:none'>删除</span></div><span style=' font-size:0.2rem;'>尺寸:750X1334</span></div><div style='clear:both'></div> </div>  <div style='margin-top:2rem;'>   <div style='float:left'> <span style='margin-right:0.5rem;'>分享页</span><div><img id='id_img3' style='width:54px;height:48px' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/> <span class='del_share'  style='display:none'>删除</span> </div><span style=' font-size:0.2rem;'>尺寸:750X1334</span></div>   <div style='float:right'> <span style='margin-right:0.5rem;'>关注页</span><div><img id='id_img4' style='width:54px;height:48px' src='http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png' style=''/><span class='del_follow'  style='display:none'>清空</span></div><span style=' font-size:0.2rem;'>尺寸:750X1334</span></div> </div>  </div>");

        Enum_map.append_option_list("market_gift_type", $main_type_name,true);

        var arr = [
            ["礼品类型", $main_type_name],
            ["标题", $title],
            ["活动描述", $describe],
            ["活动图片", $img],
            ["关注图片列表", $follow_list],
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
                if(!$img_src1.val() && !$img_src2.val() && !$img_src3.val() && !$img_src4.val() ){
                    alert('请选择活动图片!');
                    return;
                }
                var img_list = $follow_list.children();
                var img_list_str = '';
                $.each(img_list,function(i,item){
                    img_list_str = img_list_str+','+$(item.firstChild).attr('img_key');
                });


                $.do_ajax("/ss_deal/updateMarketExtend",{
                    'gift_type' : $main_type_name.val(),
                    'title'     : $title.val(),
                    'act_descr' : $describe.val(),
                    'shareImgUrl' : $img_src3.val(),
                    'coverImgUrl' : $img_src1.val(),
                    'followImgUrl': img_list_str,
                    'activityImgUrl' : $img_src2.val(),
                    'id' : opt_data.id
                });
            }
        },function(){
            $img_src1.parent().parent().css('display','none');
            $img_src2.parent().parent().css('display','none');
            $img_src3.parent().parent().css('display','none');
            $img_src4.parent().parent().css('display','none');
            $follow_list.parent().parent().css('display','none');

            $title.val(opt_data.title);
            $describe.val(opt_data.act_descr);
            $main_type_name.val(opt_data.gift_type);
            $img_src1.val(opt_data.coverimgurl);
            $img_src2.val(opt_data.activityimgurl);
            $img_src3.val(opt_data.shareimgurl);
            $img_src4.val(opt_data.followimgurl);

            if(opt_data.coverimgurl){
                $('#id_img1').attr('src','https://ybprodpub.leo1v1.com/'+opt_data.coverimgurl);
                $('.del_cover').css('display','block');
            }
            if(opt_data.activityimgurl){
                $('#id_img2').attr('src','https://ybprodpub.leo1v1.com/'+opt_data.activityimgurl);
                $('.del_activity').css('display','block');
            }
            if(opt_data.shareimgurl){
                $('#id_img3').attr('src','https://ybprodpub.leo1v1.com/'+opt_data.shareimgurl);
                $('.del_share').css('display','block');
            }
            if(opt_data.followimgurl){
                $('.del_follow').css('display','block');
                $follow_list.parent().parent().css('display','table-row');
                var list_arr = opt_data.followimgurl.split(',');
                $.each(list_arr,function(i,item){
                    $follow_list.append('<div style="margin:1rem;display:inline-block;"><img style="width: 54px; height: 48px; position: relative; z-index: 1;" img_key="'+item+'" src="https://ybprodpub.leo1v1.com/'+item+'"></div>');
                });
            }

            $.custom_upload_file('id_img1',true,function (up, info, file) { // 封面页
                var res = $.parseJSON(info);
                $img_src1.val(res.key);
                if(res.key){
                    $('.del_cover').css('display','block');
                    $('#id_img1').attr('src','https://ybprodpub.leo1v1.com/'+res.key);

                }
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

            $.custom_upload_file('id_img2',true,function (up, info, file) { // 活动页
                var res = $.parseJSON(info);
                $img_src2.val(res.key);
                if(res.key){
                    $('.del_activity').css('display','block');
                    $('#id_img2').attr('src','https://ybprodpub.leo1v1.com/'+res.key);
                }
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

            $.custom_upload_file('id_img3',true,function (up, info, file) { // 分享页
                var res = $.parseJSON(info);
                $img_src3.val(res.key);
                if(res.key){
                    $('.del_share').css('display','block');
                    $('#id_img3').attr('src','https://ybprodpub.leo1v1.com/'+res.key);
                }
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

            $.custom_upload_file('id_img4',true,function (up, info, file) { // 关注页
                var res = $.parseJSON(info);
                $img_src4.val(res.key);
                if(res.key){
                    $('.del_follow').css('display','block');
                    $follow_list.parent().parent().css('display','table-row');
                    $follow_list.append('<div style="margin:1rem;display:inline-block;"><img style="width: 54px; height: 48px; position: relative; z-index: 1;" img_key="'+res.key+'" src="https://ybprodpub.leo1v1.com/'+res.key+'"></div>');

                }

            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

            $('.del_cover').on("click",function(){
                $img_src1.val('');
                $('#id_img1').attr('src','http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png');
                $('.del_cover').css('display','none');
            });

            $('.del_activity').on("click",function(){
                $img_src2.val('');
                $('#id_img2').attr('src','http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png');
                $('.del_activity').css('display','none');
            });

            $('.del_share').on("click",function(){
                $img_src3.val('');
                $('#id_img3').attr('src','http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png');
                $('.del_share').css('display','none');
            });

            $('.del_follow').on("click",function(){
                $img_src4.val('');
                $('.del_follow').css('display','none');
                $('#id_img4').attr('src','http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E5%B8%82%E5%9C%BA%E6%B4%BB%E5%8A%A8.png');
                $follow_list.html('');
                $follow_list.parent().parent().css('display','none');
            });
        });
    });

    $('.opt-show').on("click",function(g_adminid_right){
        var opt_data=$(this).get_opt_data();
        var id = opt_data.id;
        $.do_ajax("/user_deal/showMarketExtendImg",{
            'id' : id
        },function(ret){
            var imglist = ret.data;
            var $img = $("<div/>");
            var $follow_list = $("<div />");

            $img.html("<div> <div>PNG格式</div>     <div style='margin-top:1rem;'>   <div style='float:left'> <span style='margin-right:0.5rem;'>封面页</span><img  id='id_img1' style='width:54px;height:48px' src='"+imglist.coverImgUrl+"' /></div>   <div style='float:right'> <span style='margin-right:0.5rem;'>活动页</span><img style='width:54px;height:48px' id='id_img2'  src='"+imglist.activityImgUrl+"' /></div><div style='clear:both'></div> </div>  <div style='margin-top:2rem;'>   <div style='float:left'> <span style='margin-right:0.5rem;'>分享页</span><img id='id_img3' style='width:54px;height:48px' src='"+imglist.shareImgUrl+"' /></div>    </div>  </div>");


            var arr = [
                ["活动图片", $img],
                ["关注图片", $follow_list],
            ];

            $.show_key_value_table("查看活动图片", arr, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function (dialog) {
                    load_data();
                }
            },function(){
                var $imgArr = imglist['followImgUrl'].split(',');
                $.each($imgArr,function(i,item){
                    $follow_list.append('<div style="margin:1rem;display:inline-block;"><img style="width: 54px; height: 48px; position: relative; z-index: 1;" img_key="'+item+'" src="'+item+'"></div>');
                });

            });
        });
    });


    $('.opt-change').set_input_change_event(load_data);
});
