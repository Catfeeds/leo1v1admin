/// <reference path="../common.d.ts" />
$(function(){
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
        $.do_ajax("/user_deal/origin_init_key_list", {
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
            $.do_ajax("/user_deal/origin_get_key_list", {
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

        $.show_key_value_table("渠道选择", arr, {
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
                load_data();
                dialog.close();
            }
        });

    });

});
