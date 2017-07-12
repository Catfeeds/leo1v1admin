/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/testbb-imgupload.d.ts" />

// $(function(){
//     function load_data(){
//         $.reload_self_page ( {

//         });
//     }


// 	$('.opt-change').set_input_change_event(load_data);
// });

  var images = {
    localId: [],
    serverId: []
  };
wx.config({
    debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: 'wxa99d0de03f407627', // 必填，公众号的唯一标识
    timestamp: 1494474414, // 必填，生成签名的时间戳
    nonceStr: 'leo123', // 必填，生成签名的随机串
    signature: '',// 必填，签名，见附录1
    jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});

  document.querySelector('#chooseImage').onclick = function () {
    wx.chooseImage({
      success: function (res) {
        images.localId = res.localIds;
        alert('宸查€夋嫨 ' + res.localIds.length + ' 寮犲浘鐗�');
      }
    });
  };



