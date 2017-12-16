/// <reference path="../typings/tsd.d.ts" />
$(function () {



    $(".opt-update-news2").on("click", function () {
        var phone = $(this).get_opt_data("phone");
        do_ajax("/seller_student/get_user_info", {
            "phone": phone

        }, function (result) {

            var data = result.data;
            var id_nick = $("<input/>");
            var id_update_grade = $("<select/>");
            var id_update_subject = $("<select/>");
            var id_update_pad = $("<select/>");
            var id_update_origin = $("<input/>");
            var id_from_type = $("<select/>");
            var id_user_desc = $("<input/>");

            Enum_map.append_option_list("grade", id_update_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
            Enum_map.append_option_list("pad_type", id_update_pad, true);
            Enum_map.append_option_list("subject", id_update_subject, true);
            Enum_map.append_option_list("test_listen_from_type", id_from_type, true);

            var arr = [
                ["修改姓名", id_nick],
                ["修改年级", id_update_grade],
                ["修改科目", id_update_subject],
                ["修改pad ", id_update_pad],
                ["修改渠道 ", id_update_origin],
                ["试听分类", id_from_type],
                ["用户备注", id_user_desc],
            ];

            id_nick.val(data.nick);
            id_update_origin.val(data.origin);
            id_update_subject.val(data.subject);
            id_update_pad.val(data.has_pad);
            id_update_grade.val(data.grade);
            id_from_type.val(data.from_type);
            id_user_desc.val(data.user_desc);

            show_key_value_table("修改用户", arr, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function (dialog) {
                    var grade = id_update_grade.val();
                    var subject = id_update_subject.val();
                    var pad = id_update_pad.val();
                    var origin = id_update_origin.val();
                    var from_type = id_from_type.val();

                    $.ajax({
                        url: '/seller_student/update_news',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            'old_phone': data.phone,
                            'grade': grade,
                            'subject': subject,
                            'origin': origin,
                            'nick': id_nick.val(),
                            'from_type': from_type,
                            'pad': pad,
                            'user_desc': id_user_desc.val()
                        },
                        success: function (data) {
                            alert(data.info);
                            if (data.ret != -1) {
                                window.location.reload();
                            }
                        }
                    });
                }
            });

        });
    });
    
    $(".opt-upload-test-paper").on("click",function(){
        var $this = $(this);
        var opt_data = $this.get_opt_data();
        var phone = opt_data.phone; 
        if (!$this.data("isset_flag") ) {
            custom_upload_file(
                $this.attr("id"),
                false,
                function (up, info, file) {
                    var res = $.parseJSON(info);
                    do_ajax('/seller_student/set_test_lesson_st_test_paper', {
                        'st_test_paper': res.key,
                        'phone': phone
                    });
                }, null,
                ["pdf", "zip", "rar", "png", "jpg"]);
            $this.data("isset_flag",true);
            alert("再点一下");
        }

    });




    $(" .opt-download-test-paper ").on("click", function () {
        var phone = $(this).get_opt_data("phone");
        do_ajax("/seller_student/get_user_info", {
            "phone": phone
        }, function (result) {
            var st_test_paper = result.data.st_test_paper;
            custom_show_pdf(st_test_paper);
        });

    });

    //点击进入个人主页
    $('.opt-user').on('click', function () {
        var phone = $(this).get_opt_data("phone");
        do_ajax("/user_manage/get_userid_by_phone", {
            phone: $(this).get_opt_data("phone")
        }, function (result) {
            var userid = result.userid;
            if (userid) {
                wopen('/stu_manage?sid=' + userid + "&return_url=" + encodeURIComponent(window.location.href));
            } else {
                alert('用户未注册');
            };
        });
    });

    //多级搜索
    $("#id_origin_ex").on("click", function () {
        var $key0 = $("<select/>");
        var $key1 = $("<select/>");
        var $key2 = $("<select/>");
        var $key3 = $("<select/>");
        var $key4 = $("<select/>");
        var me = $(this);
        var key_list = me.val();

        var clean_select = function ($select) {
            $select.html("<option value=\"\">[全部]</option>");
        };

        key_list = key_list.split(",");
        //处理key
        do_ajax("/user_deal/origin_init_key_list", {
            "key0": key_list[0],
            "key1": key_list[1],
            "key2": key_list[2],
            "key3": key_list[3]
        }, function (ret) {
            clean_select($key0);
            clean_select($key1);
            clean_select($key2);
            clean_select($key3);
            clean_select($key4);

            $.each(ret.key0_list, function () {
                var v = this.k;
                $key0.append("<option value=\"" + v + "\">" + v + "</option>");
            });
            $.each(ret.key1_list, function () {
                var v = this.k;
                $key1.append("<option value=\"" + v + "\">" + v + "</option>");
            });
            $.each(ret.key2_list, function () {
                var v = this.k;
                $key2.append("<option value=\"" + v + "\">" + v + "</option>");
            });

            $.each(ret.key3_list, function () {
                var v = this.k;
                $key3.append("<option value=\"" + v + "\">" + v + "</option>");
            });

            $.each(ret.key4_list, function () {
                var v = this.k;
                $key4.append("<option value=\"" + v + "\">" + v + "</option>");
            });
            $key0.val(key_list[0]);
            $key1.val(key_list[1]);
            $key2.val(key_list[2]);
            $key3.val(key_list[3]);
            $key4.val(key_list[4]);
        });



        var set_select = function ($select, key1, key2, key3,key0) {
            do_ajax("/user_deal/origin_get_key_list", {
                "key0": key0,
                "key1": key1,
                "key2": key2,
                "key3": key3
            }, function (ret) {
                var sel_v = $select.val();
                $select.html("");
                $select.append("<option value=\"\">[全部]</option>");
                $.each(ret.list, function () {
                    var v = this.k;
                    $select.append("<option value=\"" + v + "\">" + v + "</option>");
                });
            });

        };

        $key0.on("change", function () {
            clean_select($key1);
            clean_select($key2);
            clean_select($key3);
            clean_select($key4);
            if ($key0.val()) {
                set_select($key1, "", "", "",$key0.val());
            }
        });

        $key1.on("change", function () {
            clean_select($key2);
            clean_select($key3);
            clean_select($key4);
            if ($key1.val()) {
                set_select($key2, $key1.val(), "", "",$key0.val());
            }
        });

        $key2.on("change", function () {
            clean_select($key3);
            clean_select($key4);
            if ($key2.val()) {
                set_select($key3, $key1.val(), $key2.val(), "",$key0.val());
            }
        });
        $key3.on("change", function () {
            clean_select($key4);
            if ($key3.val()) {
                set_select($key4, $key1.val(), $key2.val(), $key3.val(),$key0.val());
            }
        });


        var arr = [
            ["零级", $key0],
            ["一级", $key1],
            ["二级", $key2],
            ["三级", $key3],
            ["四级", $key4],
        ];

        show_key_value_table("渠道选择", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                var arr = [];
                arr.push($key0.val());
                arr.push($key1.val());
                arr.push($key2.val());
                arr.push($key3.val());
                arr.push($key4.val());
                me.val(arr.join(","));
                dialog.close();
                load_data();
            }
        });


    });

});
