/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-channel_manage.d.ts" />

$(function(){

    $("#id_key1").val(g_args.key1);
    $("#id_key2").val(g_args.key2);
    $("#id_key3").val(g_args.key3);
    $("#id_key4").val(g_args.key4);
    $("#id_value").val(g_args.value);

    function load_data(){
        $.reload_self_page ({
            key1 : $("#id_key1").val(),
            key2:	$('#id_key2').val(),
            key3:	$('#id_key3').val(),
            key4:	$('#id_key4').val(),

            value: $("#id_value").val(),
            origin_level: $("#id_origin_level").val()
        });
    }

  $('#id_key2').val(g_args.key2);
  $('#id_key3').val(g_args.key3);
  $('#id_key4').val(g_args.key4);

    // alert(g_args.key2);


    Enum_map.append_option_list("origin_level", $("#id_origin_level"));
    $("#id_origin_level").val(g_args.origin_level);

    $("#id_value,#id_origin_level").on("change",function(){
        load_data ();
    });

    var clean_select=function($select) {
        $select.html( "<option value=\"\">[全部]</option>" );
    };

    $("#id_key1").on("change",function(){

        var key2 =  $('#id_key2');
        var key3 =  $('#id_key3');
        var key4 =  $('#id_key4');

        key2.val("");
        key3.val("");
        key4.val("");
        load_data();
    });


    $("#id_key2").on("change",function(){
        var key3 =  $('#id_key3');
        var key4 =  $('#id_key4');
        key3.val("");
        key4.val("");
        load_data ();
    });

    $("#id_key3").on("change",function(){
        var key4 =  $('#id_key4');
        key4.val("");
        load_data ();
    });

    $("#id_key4").on("change",function(){
        load_data ();
    });






    $("#id_add_new").on("click",function(){
        // 处理
        var input_str= "<input style=\"width:50%\"  />";
        var $key1=$(input_str );
        var $key2=$(input_str );
        var $key3=$(input_str );
        var $key4=$(input_str );
        var $value=$(input_str );
        var $key1_s=$("<select/>");
        var $key2_s=$("<select/>");
        var $key3_s=$("<select/>");
        var $key4_s=$("<select/>");
        var origin_level=$("<select/>");

        Enum_map.append_option_list("origin_level",origin_level ,true,[1,2,3,4,5] );
        var bind_item = function($input,$select, $d ) {
            $d.append($input).append($select);
            $select.on("change",function(){
                $input.val($select.val());
            });
        };

        var $d1=$("<div />");
        var $d2=$("<div/>");
        var $d3=$("<div/>");
        var $d4=$("<div/>");
        bind_item( $key1,$key1_s,$d1 );
        bind_item( $key2,$key2_s,$d2 );
        bind_item( $key3,$key3_s,$d3 );
        bind_item( $key4,$key4_s,$d4 );


        var clean_select=function($select) {
            $select.html( "<option value=\"\">[全部]</option>" );
        };
        var set_select=  function ( $select, key1,key2,key3) {
            $.do_ajax("/user_deal/origin_get_key_list", {
                "key1" : key1,
                "key2" : key2,
                "key3" : key3
            },function(ret){
                var sel_v=$select.val();
                $select.html("");
                $select.append( "<option value=\"\">[全部]</option>" );
                $.each( ret.list,function(){
                    var v=this.k;
                    $select.append( "<option value=\""+v+"\">"+v+"</option>" );
                });
                origin_level.val( ret.last_level );
            });

        };


        $key1_s.on("change",function(){
            clean_select( $key2_s );
            clean_select( $key3_s );
            clean_select( $key4_s );
            if( $key1_s.val() ) {
                set_select( $key2_s, $key1.val(),"","" );
            }
        });

        $key2_s.on("change",function(){
            clean_select( $key3_s );
            clean_select( $key4_s );
            if( $key2_s.val() ) {
                set_select( $key3_s, $key1.val(), $key2.val(),"" );
            }
        });
        $key3_s.on("change",function(){
            clean_select( $key4_s );
            if( $key3_s.val() ) {
                set_select( $key4_s, $key1.val(), $key2.val(), $key3.val() );
            }
        });


        var arr                = [
            ["key1", $d1],
            ["key2", $d2],
            ["key3", $d3],
            ["key4", $d4],
            ["value", $value],
            ["类别", origin_level],
        ];


        $.show_key_value_table("新增信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {

                var key1=$key1.val();
                var key2=$key2.val();
                var key3=$key3.val();
                var key4=$key4.val();
                var value=$value.val();
                if(key1 == "" || key2 == "" || key3 == "" || key4 == "" || value=="" ) {
                    alert("请将信息填写完整") ;
                    return;
                }
                $.do_ajax('/seller_student/add_origin_key', {
                    'value': value,
                    'key1': key1,
                    'key2': key2,
                    'key3': key3,
                    'key4': key4,
                    'origin_level':origin_level.val()
                },function(){
                    alert('添加成功' );
                    window.location.reload();
                });
                dialog.close();
            }
        });

        //init key1
        if (g_args.key1 ) {
            $key1.val(g_args.key1);
            $key1.attr("readonly", "readonly");
            $key1_s.hide();
            $key1_s.val( g_args.key1  );
            set_select( $key2_s, g_args.key1 ,"","" );
        }else {
            set_select( $key1_s, "","","" );
        }


    });



    $("#id_edit_all_origin_level").on("click",function(){

        var origin_level=$("<select/>");
        var key1_str = $("#id_key1 option:selected").val();
        var key2_str = $("#id_key2 option:selected").val();
        var key3_str = $("#id_key3 option:selected").val();
        var key4_str = $("#id_key4 option:selected").val();

        Enum_map.append_option_list("origin_level",origin_level ,true );

        var arr                = [
            ["渠道类别", origin_level],
        ];

        $.show_key_value_table("设置当前渠道等级", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var key1 = key1_str;
                    var key2 = key2_str;
                    var key3 = key3_str;
                    var key4 = key4_str;

                    $.ajax({
                        url: '/seller_student/edit_origin_level_by_batch',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            'key1'     : key1,
                            'key2'     : key2,
                            'key3'     : key3,
                            'key4'     : key4,
                            "origin_level": origin_level.val()
                        },
                        success: function(data) {
                            alert(data.info);
                            if(data.ret != -1){
                                window.location.reload();
                            }
                        }
                    });
                }
            });

    });
















    $(".done_e").on("click",function(){
        var value=$(this).get_opt_data("value");
        $.do_ajax ( "/seller_student/get_origin_key", {
            "value" : value
        },function(result){
            var data     = result.data;
            //alert(JSON.stringify(data));
            var id_key1  = $("<input/>");
            var id_key2  = $("<input/>");
            var id_key3  = $("<input/>");
            var id_key4  = $("<input/>");
            var id_value = $("<input/>");

            var id_origin_level=$("<select/>");
            Enum_map.append_option_list("origin_level",id_origin_level ,true);

            var arr               = [
                [ "key1",  id_key1] ,
                [ "key2",  id_key2] ,
                [ "key3",  id_key3] ,
                [ "key4",  id_key4] ,
                [ "value", id_value] ,
            ];
            if ( data.origin_level !=90 ) {
                arr.push( [ "类别", id_origin_level]);
            }  else if ( g_account=="张亚东"|| g_account=="jo"   ) {
                arr.push( [ "类别", id_origin_level]);
            }

            id_key1.val(data.key1);
            id_key2.val(data.key2);
            id_key3.val(data.key3);
            id_key4.val(data.key4);
            id_value.val(data.value);
            id_origin_level.val(data.origin_level);

            $.show_key_value_table("编辑信息", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var key1 = id_key1.val();
                    var key2 = id_key2.val();
                    var key3 = id_key3.val();
                    var key4 = id_key4.val();
                    var value= id_value.val();

                    $.ajax({
                        url: '/seller_student/edit_origin_key',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            'key1'     : key1,
                            'key2'     : key2,
                            'key3'     : key3,
                            'key4'     : key4,
                            'value'    : value,
                            'old_value'    : data.value,
                            "origin_level": id_origin_level.val()
                        },
                        success: function(data) {
                            alert(data.info);
                            if(data.ret != -1){
                                window.location.reload();
                            }
                        }
                    });
                }
            });

        });

    });


    $(".done_t").on("click", function(){
        var value = $(this).get_opt_data("value");
        BootstrapDialog.confirm("删除该记录,"+value+" 要删除吗?!" , function(val){
            if ( val ) {
                $.ajax({
                    url: '/seller_student/delete_origin_key',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'value': value
                    },
                    success: function(data) {
                        if(data.ret == -1){
                            alert(data.info);
                        }else{
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });

    if ( g_args.key1_filed_hide   ) {
        $("#id_key1").parent().parent().hide();
    }


});
