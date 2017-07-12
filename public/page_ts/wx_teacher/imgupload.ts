/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/wx_teacher-imgupload.d.ts" />


$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }

    var signature_str = $('#signature_str').attr('data_signature_str');

  $('.opt-change').set_input_change_event(load_data);

  var images = {
    localId: [],
    serverId: []
  };

    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: 'wxa99d0de03f407627', // 必填，公众号的唯一标识
        timestamp: 1494474414, // 必填，生成签名的时间戳
        nonceStr: 'leo123', // 必填，生成签名的随机串
        signature: signature_str,// 必填，签名，见附录1
        jsApiList: [
            'chooseImage',
            'uploadImage',
            'downloadImage'
                   ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });

    document.querySelector('#chooseImage').onclick = function () {
        wx.chooseImage({
            success: function (res) {
                images.localId = res.localIds;
            }
        });
    };


    document.querySelector('#uploadImage').onclick = function () {
        if (images.localId.length == 0) {
            alert('请先选择图片!');
            return;
        }
        var i = 0, length = images.localId.length;
        images.serverId = [];
        function upload() {
            wx.uploadImage({
                localId: images.localId[i],
                success: function (res) {
                    i++;
                    images.serverId.push(res.serverId);

                    // $.do_ajax("/wx_teacher/get_wximg_by_serverId", {
                    //     "serverId"  : res.serverId
                    // },function(resp){
                    //     // alert('fahui'+resp.data);
                    // });

                    if (i < length) {
                        upload();
                    } else {
                        // alert('ok'+JSON.stringify(images.serverId));

                        $.do_ajax("/wx_teacher/get_wximg_by_serverId", {
                            "serverId"  : JSON.stringify(images.serverId)
                        },function(resp){
                            // alert('fahui'+resp.data);
                        });



                    }
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
        }
        upload();
    };

});
