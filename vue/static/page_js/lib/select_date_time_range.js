(function($, window, document,undefined) {
    //定义构造函数
    var Cset_date_time= function(ele, opt) {
        this.$element = ele;
        this.defaults = {
            'start_time':0,
            'end_time':0,
            "default_interval": 7200,
            "onSelect":null
        };
        this.options = $.extend({}, this.defaults, opt);

        var me=this;
        me.$element.on("click",function( ){

            var id_start_time=$("<input  /> ");
            var id_end_time=$("<input  /> ");

	        //时间插件
	        id_start_time.datetimepicker({
                lang: "zh",
		        datepicker:true,
		        timepicker:true,
		        format:'Y-m-d H:i',
		        step:30,
	            onChangeDateTime :function(){
                    var end_time= parseInt(strtotime(id_start_time.val() )) + me.options.default_interval ;
                    id_end_time.val(  DateFormat(end_time, "hh:mm"));
                }

	        });

            
	        id_end_time.datetimepicker({
                lang: "zh",
		        datepicker:false,
		        timepicker:true,
		        format:'H:i',
		        step:30
	        });



            if (me.options.start_time >0) {
                id_start_time.val(DateFormat (me.options.start_time, "yyyy-MM-dd hh:mm"));

                id_end_time.val(DateFormat (me.options.end_time, "hh:mm"));
            }
            
            var arr = [
                [ "开始时间",  id_start_time ] ,
                [ "结束时间",   id_end_time  ] 
            ];
            show_key_value_table("时间段", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var start_time=id_start_time.val();
                    var end_time=id_end_time.val();
                    if (start_time.length ==16 ) {
                        end_time=start_time.substr(0,10)+" "+end_time;
                        start_time= $.strtotime(start_time);
                        end_time= $.strtotime(end_time);
                        if (end_time<start_time) {
                            alert("时间段不对") ;
                            return ;
                        }
                        me.options.onSelect(start_time ,end_time);
                        dialog.close();
                    }else{
                        alert("时间段还没选") ;
                    }
                }
            });
        });
    };


    //定义方法
    Cset_date_time.prototype = {
    };

    //在插件中使用对象
    $.fn.admin_select_date_time_range = function(options) {
        //创建的实体
        var set_date_time  = new Cset_date_time(this, options);
        //调用其方法
        return  set_date_time;
    };
})(jQuery, window, document);
