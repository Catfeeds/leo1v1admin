(function($, window, document,undefined) {
    //定义构造函数
    var Cset_lesson_time= function(ele, opt) {
        this.$element = ele;
        this.defaults = {
            'lessonid':0,
            "onSelect":null
        };
        this.options = $.extend({}, this.defaults, opt);

        var me=this;
        me.$element.on("click",function( ){

            do_ajax("/lesson_manage/get_lesson_info", {
                "lessonid":me.options.lessonid
            },function(data){

                data=data.data;
                if (!(data.teacherid >0) ) {
                    alert("先设置老师");
                    return;
                }
                var now=new Date();
                now=now.getTime()/1000;

                var check_end =  parseInt(data.lesson_end)+3600 ;
                if ( data.lesson_end >0 && now> check_end  ) {
                    alert("时间结束超过1个小时,不能设置");
                    return;
                }

                var id_start_time=$("<input  /> ");
                var id_end_time=$("<input  /> ");


                if ( data.lesson_status  > 0 ) {
                    id_start_time.css("readonly", "readonly");
                    id_start_time.on("click",function(){
                        alert("课次已开始，不能修改开始时间");
                    });

                }else{
	                //时间插件
	                id_start_time.datetimepicker({
		                datepicker:true,
		                timepicker:true,
		                format:'Y-m-d H:i',
		                step:30,
	                    onChangeDateTime :function(){
                            var end_time= parseInt(strtotime(id_start_time.val() )) + 7200;
                            id_end_time.val(  DateFormat(end_time, "hh:mm"));
                        }

	                });
                }

                
	            id_end_time.datetimepicker({
		            datepicker:false,
		            timepicker:true,
		            format:'H:i',
		            step:30
	            });



                if (data.lesson_start>0) {
                    id_start_time.val(DateFormat (data.lesson_start , "yyyy-MM-dd hh:mm"));

                    id_end_time.val(DateFormat (data.lesson_end, "hh:mm"));
                }
                function get_unix_time(dateStr)
                {
                    var newstr = dateStr.replace(/-/g,'/'); 
                    var date =  new Date(newstr); 
                    var time_str = date.getTime().toString();
                    return time_str.substr(0, 13);
                }
                
                var arr = [
                    [ "课程id", data.lessonid] ,
                    [ "课次", data.lesson_num ] ,
                    [ "开始时间",  id_start_time ] ,
                    [ "结束时间",   id_end_time  ] 
                ];
                show_key_value_table("修改课程时间", arr ,{
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        var timestamp=  (new Date()).valueOf();
                        //alert(get_unix_time(id_start_time.val()));
                        //alert(timestamp);
                        var deal_func=function() {
                            do_ajax(
                                '/user_deal/set_lesson_time',
                                {
				                    'lessonid':  data.lessonid,
                                    'start':  id_start_time.val(),
                                    'end':   id_end_time.val()
			                    },function( ret){
                                    if (ret.ret != 0) {
                                        BootstrapDialog.alert(ret.info ) ;
                                    }else{
                                        alert("设置成功") ;
                                        window.location.reload();
                                    }
                                    
                                }
                            );
                        };
                        if( get_unix_time(id_start_time.val()) < timestamp + 86400  ){
                            if (confirm  ("该时间离当前很近,:" + id_start_time.val() +".是吗？"  )) {
                                deal_func();
                            }else{
                                return ; 
                            }
                        }else{
                            deal_func();
                        }
                        
                    }
                });
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
