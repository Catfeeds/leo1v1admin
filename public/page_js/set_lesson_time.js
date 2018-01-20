(function($, window, document,undefined) {
    //定义构造函数
    var Cset_lesson_time= function(ele, opt) {
        this.$element = ele;
        this.defaults = {
            'lessonid':0,
            'reset_lesson_count':1,
            "onSelect":null
        };
        this.options = $.extend({}, this.defaults, opt);

        var me=this;
        me.$element.on("click",function( ){
            $.do_ajax("/lesson_manage/get_lesson_info", {
                "lessonid":me.options.lessonid
            },function(data){
                var lesson_count = data.lesson_count;
                var lesson_type  = data.data.lesson_type;
                if( lesson_type == 2 ){
                    lesson_count = 100;
                }

                data = data.data;
                if (!(data.teacherid >0) ) {
                    BootstrapDialog.alert("先设置老师");
                    return;
                }
                var now=new Date();
                now=now.getTime()/1000;

                // var check_end =  parseInt(data.lesson_end)+3600 ;
                // if ( data.lesson_end >0 && now> check_end  ) {
                //     BootstrapDialog.alert("时间结束超过1个小时,不能设置");
                //     return;
                // }

                var id_start_time = $("<input/> ");
                var id_end_time   = $("<input/> ");
                if ( data.lesson_status  != 0 ) {
                    BootstrapDialog.alert("课程状态不是未开始，无法修改!");
                    return false;

                    // id_start_time.css("readonly", "readonly");
                    // id_start_time.on("click",function(){
                    //     BootstrapDialog.alert("课程状态不是未开始，无法修改");
                    //     return false;
                    // });
                }else{
                    id_start_time.datetimepicker({
                        lang       : 'ch',
                        datepicker : true,
                        timepicker : true,
                        format     : 'Y-m-d H:i',
                        step       : 30,
                        onChangeDateTime :function(){
                            var tt = '';
                            if(lesson_count == 100){
                                tt = 2400;
                            }else if(lesson_count == 200){
                                tt = 5400;
                            }else{
                                tt = 7200;
                            }

                            var end_time= parseInt(strtotime(id_start_time.val() )) + tt;
                            id_end_time.val(DateFormat(end_time,"hh:mm"));
                        }
                    });

                    id_end_time.datetimepicker({
                        lang       : 'ch',
                        datepicker : false,
                        timepicker : true,
                        format     : 'H:i',
                        step       : 30
                    });

                    if (data.lesson_start>0) {
                        id_start_time.val(DateFormat (data.lesson_start , "yyyy-MM-dd hh:mm"));
                        id_end_time.val(DateFormat (data.lesson_end, "hh:mm"));
                    }

                    var arr = [
                        ["----","修改课程时间会修改课程的<font color='red'>课时数</font>,请谨慎修改"],
                        [ "课程id",data.lessonid] ,
                        [ "课次",data.lesson_num] ,
                        [ "开始时间",id_start_time] ,
                        [ "结束时间",id_end_time]
                    ];
                    show_key_value_table("修改课程时间", arr ,{
                        label    : '确认',
                        cssClass : 'btn-warning',
                        action   : function(dialog) {
                            var timestamp = (new Date()).valueOf()/1000;
                            var deal_func = function() {
                                BootstrapDialog.alert("正在修改，请稍后...");
                                $.do_ajax('/user_deal/set_lesson_time',{
                                    'reset_lesson_count' : me.options.reset_lesson_count,
                                    'lessonid'           : data.lessonid,
                                    'start'              : id_start_time.val(),
                                    'end'                : id_end_time.val()
                                },function(ret){
                                    if (ret.ret != 0) {
                                        BootstrapDialog.alert(ret.info) ;
                                    }else{
                                        BootstrapDialog.alert("设置成功") ;
                                        window.location.reload();
                                    }
                                });
                            };

                            if ( $.strtotime( id_start_time.val() )< timestamp-60  ) {
                                BootstrapDialog.alert("开始时间不能比现在还小!");
                                return ;
                            }

                            if ( $.strtotime( id_start_time.val().substr(0,11)+  (id_end_time.val())) < timestamp-60  ) {
                                BootstrapDialog.alert("结束时间不能比现在还小!");
                                return ;
                            }

                            if( $.strtotime( (id_start_time.val()))<timestamp+86400){
                                if (confirm("该时间离当前很近,:"+id_start_time.val()+".是吗？")) {
                                    deal_func();
                                }else{
                                    return ;
                                }
                            }else{
                                deal_func();
                            }
                        }
                    });
                }
            });
        });
    };

    //定义方法
    Cset_lesson_time.prototype = {
    };

    //在插件中使用对象
    $.fn.admin_set_lesson_time = function(options) {
        //创建的实体
        var set_lesson_time  = new Cset_lesson_time(this, options);
        //调用其方法
        return  set_lesson_time;
    };
})(jQuery, window, document);
