(function($, window, document,undefined) {
    //定义构造函数
    var Cdiv= function(ele, opt) {


        //this.$element.attr("readonly","readonly");

        this.options     = $.extend({
            date_type_config :null,
            opt_date_type :null,
            date_type :null,
            start_time :null,
            end_time:null,
            field_index:0,
            timepicker :false,
        }, this.defaults, opt);

        var field_index=  this.options.field_index;

        var date_type_str="id_date_type";
        var opt_date_type_str="id_opt_date_type";
        var start_time_str="id_start_time";
        var end_time_str="id_end_time";


        if ( field_index>0 ) {
            date_type_str="id_date_type_"+ field_index;
            opt_date_type_str="id_opt_date_type_"+ field_index;
            start_time_str="id_start_time_"+ field_index;
            end_time_str="id_end_time_"+ field_index;
        }
        var input_width= 100;

        var date_format= 'Y-m-d';
        if (this.options.timepicker ) {
            input_width= 140;
            date_format= 'Y-m-d H:i';
        }
        if ($.get_action_str( )=="todo_list") {alert("xxxx");}

        var me =this;
        var html='                    <div class="input-group ">'+
            '                        <select id="'+date_type_str+'" class="" style="width:100px;">'+
'                        </select><span style="width:0px; padding:0px;border:0px none;"/>' +

'                        <select id="'+opt_date_type_str +'" class="" style="width:100px;">'+
'                            <option value="0"> 按时段</option>'+
'                            <option value="1"> 按天</option>'+
'                            <option value="2"> 按周</option>'+
'                            <option value="3"> 按月</option>'+
'                            <option value="7">最近7天 </option>'+
'                            <option value="15">最近15天 </option>'+
'                            <option value="30">最近30天 </option>'+
''+
'                        </select>'+
''+
'                        <span id="id_date_span1">:</span>'+
'                        <div class=" input-group-btn ">'+
'                            <button  id="id_date_pre" type="submit" class="btn  btn-primary "><i class="fa    fa-chevron-left" ></i></button>'+
'                        </div>'+

            '                        <input type="text" id="'+start_time_str+'" style="width:'+input_width +'px;" />'+
'                        <div class=" input-group-btn ">'+
'                            <button  type="submit" class="btn  btn-primary " id="id_date_next"><i class="fa    fa-chevron-right "></i></button>'+
'                        </div>'+
'                        <span id="id_date_span2" >-</span>'+
            '                        <input type="text" id="'+end_time_str+'" style="width:'+input_width +'px;" class=""/>'+
'                        <span  style="width:100% ;opacity:0;"></span>'+
                '                    </div>';


        var $ele=$(ele);
        $ele.html(html);
        var id_start_time=$ele.find("#"+start_time_str) ;
        var id_end_time=$ele.find("#"+end_time_str) ;
        var id_opt_date_type=$ele.find("#"+opt_date_type_str);
        var id_date_type=$ele.find("#"+date_type_str);

        if (this.options.date_type_config ) {
            var str="";
            $.each(this.options.date_type_config, function(k,v){
                str+="<option value=\""+k+"\" >"+v[1] +"</option>";
            });
            if (str=="") {
                id_date_type.hide();
            }else{
                id_date_type.html(str);
            }
        }else{
            id_date_type.hide();
        }



        id_start_time.datetimepicker({
            lang:'zh-cn',
            timepicker:this.options.timepicker  ,
            format: date_format,
            "onChangeDateTime" : function() {
                var opt_date_type=id_opt_date_type.val();
                set_date(opt_date_type,true);
            }
        });

      id_end_time.datetimepicker({
          timepicker:this.options.timepicker  ,
          format: date_format,
            "onChangeDateTime" : function() {
                var opt_date_type=id_opt_date_type.val();
                set_date(opt_date_type,true);
            }
      });

        id_start_time.val(this.options.start_time);
        id_end_time.val(this.options.end_time);
        id_date_type.val(this.options.date_type);

        var set_date=function(opt_date_type , do_query_flag) {
            var start_time=$.strtotime(id_start_time.val() );
            var start_date="";
            var end_date="";
            if (opt_date_type==0) {
            }else if ( opt_date_type==1) {//当天

                start_date=$.DateFormat(start_time,"yyyy-MM-dd");
                id_start_time.val(start_date);
                id_end_time.val(start_date);
            }else if ( opt_date_type==2) {//当周
                var opt_date=new Date(start_time*1000);
                var week=opt_date.getDay();
                if (week==0) {
                    week=7;
                }
                start_time=(start_time-(week-1)*86400);
                start_date=$.DateFormat(start_time,"yyyy-MM-dd");
                end_date=$.DateFormat(start_time+6*86400,"yyyy-MM-dd");
                id_start_time.val(start_date);
                id_end_time.val(end_date);

            }else if ( opt_date_type==3) {//当月
                start_date=$.DateFormat(start_time,"yyyy-MM-01");
                start_time=$.strtotime(start_date);
                var date_v=new Date(start_time*1000);
                var year=date_v.getFullYear();
                var month=date_v.getMonth()+1;
                var d = new Date(year,month,0);
                end_date=$.DateFormat(start_time+(d.getDate()-1)*86400,"yyyy-MM-dd");
                id_start_time.val(start_date);
                id_end_time.val(end_date);

            }else if ( opt_date_type==7 || opt_date_type==15 ||  opt_date_type==30  ) {//最近
                var now=(new Date()).getTime()/1000;
                end_date=$.DateFormat(now, "yyyy-MM-dd");
                start_time=now-opt_date_type*86400;

                start_date=$.DateFormat(start_time,"yyyy-MM-dd");
                id_start_time.val(start_date);
                id_end_time.val(end_date);

            }
            //show div
            opt_date_type=opt_date_type*1;
            if ($.inArray ( opt_date_type, [0]  ) !=-1) {  //
                id_end_time.show();
                $ele.find("#id_date_span1").show();
                $ele.find("#id_date_span2").show();
                $ele.find("#id_date_pre").hide();
                $ele.find("#id_date_next").hide();
            } else if ($.inArray ( opt_date_type, [1,2,3]  ) !=-1) {  //
                id_end_time.hide();
                $ele.find("#id_date_span1").hide();
                $ele.find("#id_date_span2").hide();
                $ele.find("#id_date_pre").show();
                $ele.find("#id_date_next").show();
            } else if ($.inArray ( opt_date_type, [7,15,30]  ) !=-1) {  //
                id_end_time.hide();
                $ele.find("#id_date_span1").hide();
                $ele.find("#id_date_span2").hide();
                $ele.find("#id_date_pre").show();
                $ele.find("#id_date_next").show();
            }

            if ( do_query_flag) {
                me.options.onQuery();
            }
        };

        id_opt_date_type.on("change",function(){
            var opt_date_type=$(this).val();
            set_date(opt_date_type,true);
        });
        id_date_type.on("change",function(){
            var opt_date_type= id_opt_date_type.val();
            set_date(opt_date_type,true);
        });



        set_date(this.options.opt_date_type);


        id_opt_date_type.val(this.options.opt_date_type);

        $ele.find("#id_date_pre,#id_date_next").on("click",function(){

            var flag=-1;
            if( $(this).attr("id" )=="id_date_pre" ) {
                flag=1;
            }

            var opt_date_type= id_opt_date_type.val();
            var start_time=$.strtotime(id_start_time.val() );
            var start_date='';
            if (opt_date_type==1) { //天
                start_time=(start_time-86400*flag);
                start_date=$.DateFormat(start_time,"yyyy-MM-dd");
                id_start_time.val(start_date);
            }else if ( opt_date_type == 2) { //周
                start_time=(start_time-7*86400*flag);
                start_date=$.DateFormat(start_time,"yyyy-MM-dd");
                id_start_time.val(start_date);
            }else if ( opt_date_type == 3) { //月
                if (flag==1) { //pre
                    start_time=(start_time-86400*flag);
                }else{
                    start_time=(start_time+32*86400);
                }
                start_date=$.DateFormat(start_time,"yyyy-MM-01");
                id_start_time.val(start_date);
            }

            set_date(opt_date_type,true);
        });

        //
    };


    //在插件中使用对象
    $.fn.select_date_range  = function(options) {
        //创建的实体
        var select_dlg  = new Cdiv(this, options);

        return  select_dlg;
    };
})(jQuery, window, document);
