/// <reference path="../typings/tsd.d.ts" />
$(function () {

    $(".opt-set-test-lesson-info").on("click", function () {
        var phone             = $(this).get_opt_data("phone");
        var origin            = $(this).get_opt_data("origin");
        var grade             = $(this).get_opt_data("grade");
        var admin_revisiterid = $(this).get_opt_data("admin_revisiterid");
        if (!admin_revisiterid) { //
            alert("请设置销售!");
            return;
        }

        var admin_select_user = $(this).get_opt_data("origin");
        do_ajax("/seller_student/get_user_info", {
            "phone": phone
        }, function (result) {
            var data = result.data;
            var $st_class_time = $("<input/>");
            var $st_from_school = $("<input/>");
            var $st_demand = $("<textarea/>");
            $st_demand.css({
                //  width :"90%", 
                height: "80px"
            });

            $st_class_time.datetimepicker({
                lang: 'ch',
                timepicker: true,
                format: 'Y-m-d H:i'
            });

            var arr = [
                ["电话", phone],
                ["期待试听时间", $st_class_time],
                ["在读学校", $st_from_school],
                ["试听需求", $st_demand]
            ];

            var phone_ex = ("" + phone).split("-")[0];

            if (data.st_class_time > 0) {
                $st_class_time.val(DateFormat(data.st_class_time, "yyyy-MM-dd hh:mm"));
            }
            $st_from_school.val(data.st_from_school);
            $st_demand.val(data.st_demand);

            show_key_value_table("设置试听信息", arr, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function (dialog) {
                    if (
                        data.status == 6 ||
                        data.status == 7 ||
                        data.status == 8
                    ) {
                        alert("已试听中，不可重置");
                        return;
                    }

                    do_ajax("/user_manage/get_userid_by_phone", {
                        phone: phone
                    }, function (result) {
                        var userid = result.userid;
                        if (!userid) {
                            do_ajax('/login/register', {
                                'telphone': phone_ex,
                                'passwd': 123456,
                                'grade': grade
                            }, function () {

                            });
                        }

                        //设置
                        do_ajax('/seller_student/register_appstore', {
                            'telphone': phone_ex,
                            'origin': origin,
                            'seller': admin_revisiterid
                        }, function () {

                        });

                        do_ajax('/seller_student/set_test_lesson_info', {
                            'phone': phone,
                            'st_class_time': $st_class_time.val(),
                            'st_from_school': $st_from_school.val(),
                            'st_demand': $st_demand.val()
                        }, function () {
                            alert('设置成功');
                            window.location.reload();
                        });
                        dialog.close();
                    });
                }
            });
        });
    });


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

            Enum_map.append_option_list("grade", id_update_grade, true);
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
            ];

            id_nick.val(data.nick);
            id_update_origin.val(data.origin);
            id_update_subject.val(data.subject);
            id_update_pad.val(data.has_pad);
            id_update_grade.val(data.grade);
            id_from_type.val(data.from_type);

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
                            'pad': pad
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

    $.each($(".opt-upload-test-paper"), function () {
        var $this = $(this);
        var phone = $this.get_opt_data("phone");
        console.log($this.attr("id"));
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
            "key1": key_list[0],
            "key2": key_list[1],
            "key3": key_list[2]
        }, function (ret) {
            clean_select($key1);
            clean_select($key2);
            clean_select($key3);
            clean_select($key4);

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
            $key1.val(key_list[0]);
            $key2.val(key_list[1]);
            $key3.val(key_list[2]);
            $key4.val(key_list[3]);
        });



        var set_select = function ($select, key1, key2, key3) {
            do_ajax("/user_deal/origin_get_key_list", {
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


        $key1.on("change", function () {
            clean_select($key2);
            clean_select($key3);
            clean_select($key4);
            if ($key1.val()) {
                set_select($key2, $key1.val(), "", "");
            }
        });

        $key2.on("change", function () {
            clean_select($key3);
            clean_select($key4);
            if ($key2.val()) {
                set_select($key3, $key1.val(), $key2.val(), "");
            }
        });
        $key3.on("change", function () {
            clean_select($key4);
            if ($key3.val()) {
                set_select($key4, $key1.val(), $key2.val(), $key3.val());
            }
        });


        var arr = [
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
                arr.push($key1.val());
                arr.push($key2.val());
                arr.push($key3.val());
                arr.push($key4.val());
                me.val(arr.join(","));
                load_data();
                dialog.close();
            }
        });


    });

});
