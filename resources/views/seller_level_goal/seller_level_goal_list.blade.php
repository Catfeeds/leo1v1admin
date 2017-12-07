@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript">
     var _KDA = _KDA || [];
     window._KDA = _KDA;
     (function(){
         var _dealProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
         var _sdkURL = _dealProtocol + "deal-admin.kuick.cn/sdk/v1/";
         _KDA.push(['SDK_URL', _dealProtocol + "deal-admin.kuick.cn/sdk/v1/"]);
         _KDA.push(['APP_KEY', '128994ec-ba97-4a28-9ecc-faa1b00eba33']);
         _KDA.push(['APP_SECRET', 'e1888aa6-f527-4477-ae9b-409fca29f44c']);
         (function() {
             var dealAdmin = document.createElement('script');
             dealAdmin.type='text/javascript';
             dealAdmin.async = true;
             dealAdmin.src = _sdkURL + 'kuickdealadmin-pc.min.js';
             var s = document.getElementsByTagName('script')[0];
             s.parentNode.insertBefore(dealAdmin, s);
         })();
     })();

     function onKDAReady(){
         // 客户下拉组件
         KDAJsSdk.widget.createCustomerDropMenuWidget({
             selector: ".kda-customer-widget",
         });
         $(function(){
             var $title=$(".kda-customer-widget .KDA_customerDropMenuName "  );
             $title.text("K");
             $(".kda-customer-widget .KDA_customerDropMenuCon"  ).attr( "style" ,"width:30px;");
         });

     }

     if (typeof KDAJsSdk == "undefined"){
         if(document.addEventListener){
             document.addEventListener('KDAReady', onKDAReady, false);
         } else if (document.attachEvent){
             document.attachEvent('KDAReady', onKDAReady);
             document.attachEvent('onKDAReady', onKDAReady);
         }
     } else {
         onKDAReady();
     }
    </script>
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <button style="" id="id_add"> 增加</button>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>等级序号 </td>
                    <td>销售等级 </td>
                    <td>定级等级目标 </td>
                    <td>资源等级目标 </td>
                    <td>等级头像 </td>
                    <td>等级角标 </td>
                    <td>创建时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["num"]}} </td>
                        <td>{{@$var["seller_level_str"]}} </td>
                        <td>{{@$var["level_goal"]}} </td>
                        <td>{{@$var["seller_level_goal"]}} </td>
                        <td><img src="{{@$var["level_face"]}}" width="100px;" height="100px;" alt="" /></td>
                        <td><img src="{{@$var["level_icon"]}}" width="100px;" height="100px;" alt="" /></td>
                        <td>{{@$var["create_time"]}} </td>
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
    </section>
    
@endsection

