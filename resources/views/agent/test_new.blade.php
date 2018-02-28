@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>
                <button class="btn btn-primary" id="id_upload_xls" > 上传xls </button>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>教材 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["textbook_str"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
        <div class="shop_list" id="shop_list"> 
            <ul> 
                <li> 
                    <img src="img/index/zixun1.jpg"/> 
                    <div class="listItem"> 
                        <h5>小米手机 Note 顶配版</h5> 
                        <p>全网通 香槟金 移动联通<br/>双4G手机 双卡双待</p> 
                        <em>￥2998<i>起</i></em> 
                        <span class="time" data-starttime="1445982375" data-endtime="1446350400"></span> 
                    </div> 
                </li> 
                <li> 
                    <img src="img/index/zixun1.jpg"/> 
                    <div class="listItem"> 
                        <h5>小米手机 Note 顶配版</h5> 
                        <p>全网通 香槟金 移动联通<br/>双4G手机 双卡双待</p> 
                        <em>￥2998<i>起</i></em> 
                        <span class="time" data-starttime='1445982375' data-endtime='1446350400'></span> 
                    </div>
                </li>
            </ul>
        </div>

        <script type="text/javascript"> 
         $(function(){ 
             //找到商品列表以及时间显示span 
             var abj = $("#shop_list"), 
                 timeObj = abj.find('.time'); 
             //获取开始时间 
             var starttime = timeObj.data('starttime'); 
             // 定时器函数 
             function actionDo(){ 
                 return setInterval(function(){ 
                     timeObj.each(function(index, el) { 
                         //surplusTime为活动剩余开始时间 
                         var t = $(this), 
                             surplusTime = t.data('endtime') -starttime; 
                         //若活动剩余开始时间小于0，则说明活动已开始 
                         if (surplusTime <= 0) { 
                             t.html("活动已经开始"); 
                         } else{ 
                             //否则，活动未开始，将剩余的时间转换成年，月，日，时，分，秒的形式 
                             var year = surplusTime/(24*60*60*365), 
                                 showYear = parseInt(year), 
                                 month = (year-showYear)*12, 
                                 showMonth = parseInt(month), 
                                 day = (month-showMonth)*30, 
                                 showDay = parseInt(day), 
                                 hour = (day-showDay)*24, 
                                 showHour = parseInt(hour), 
                                 minute = (hour-showHour)*60, 
                                 showMinute = parseInt(minute), 
                                 seconds = (minute-showMinute)*60, 
                                 showSeconds = parseInt(seconds); 
                             t.html(""); 
                             //设置输出到HTML的格式并输出到HTML 
                             if (showYear>0) { 
                                 t.append("<span>"+showYear+"年</span>") 
                             }; 
                             if (showMonth>0) { 
                                 t.append("<span>"+showMonth+"月</span>") 
                             }; 
                             if (showDay>0) { 
                                 t.append("<span>"+showDay+"天</span>") 
                             }; 
                             if (showHour>=0) { 
                                 t.append("<span>"+showHour+"小时</span>") 
                             }; 
                             if (showMinute>=0) { 
                                 t.append("<span>"+showMinute+"分钟</span>") 
                             }; 
                             if (showSeconds>=0) { 
                                 t.append("<span>"+showSeconds+"秒</span>") 
                             }; 
                         }; 
                     }); 
                     starttime++; 
                 },1000); // 设定执行或延时时间 
             }; 
             // 执行它 
             actionDo(); 
         }); 
        </script> 

    </section>
    
@endsection

