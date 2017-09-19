/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-upload_face_pic.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }

    $.custom_upload_file('id_upload_face',1,function (up, info, file) {
        var res = $.parseJSON(info);
        $.ajax({
            url: '/self_manage/set_manager_face',
            type: 'POST',
            data: {
                'face':  res.key,
                'uid':g_uid
            },
            dataType: 'json',
            success: function(data) {
                window.location.reload();
            }
        });

    }, null,["png", "jpg",'jpeg','bmp','gif']);



  $('.opt-change').set_input_change_event(load_data);
});
