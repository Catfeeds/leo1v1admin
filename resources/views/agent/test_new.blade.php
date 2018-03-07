@extends('layouts.app')
@section('content')
    <style>
     header, nav, aside, menu, figure, article, footer { display:block; }
     body, div, form, textarea, label, input, ul, ol, li, dl, dt, dd, p, span, a, img, h1, h2, h3, h4, h5, h6, tbody, tfoot, tr, th, td, pre, code, form, fieldset, legend, font { margin:0; padding:0; }
     table { border-collapse:collapse; border-spacing:0; }
     caption, th { text-align:left; }
     sup { vertical-align:text-top; }
     sub { vertical-align:text-bottom; }
     li { list-style:none; }
     fieldset, img { border:none; }
     input, textarea, select { font-family:inherit; font-size:inherit; font-weight:inherit; *font-size:100%;
         color:inherit; _color:#333; *color:#333;
         outline:none; }
     /*BASE CLASS*/
     .cfix { *display:inline-block;*zoom:1}
     .cfix:after { content:"."; display:block; height:0; clear:both; visibility:hidden; }
     /*公告栏滚动CSS*/
     #callboard { width:600px; margin:100px auto 0; height:24px; line-height:24px; overflow:hidden; font-size:12px; background-color:#f5f5f5;}
     #callboard ul { padding:0; }
     #callboard li { padding:0; } 
    </style>
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
        <table class="common-table"  > 
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

        <div id="callboard">
            <ul>
                <li>
                    <span style="color:red;">公告[2]：前端组上线一个月零八天，PR升为3，BD权重1</span>
                </li>
                <li>
                    <span style="color:red;">公告[3]：撤下了BloggerAds，原因为收入太少，打开速度慢！</span>
                </li>
                <li style="margin-top: 0px;">
                    <a href="http://www.jb51.net">公告[1]：前端组主题正在整理中..有需要用的朋友请留个言，以方便及时通知！</a>
                </li>
            </ul>
        </div> 

        <script type="text/javascript">
         (function (win){
             var callboarTimer;
             var callboard = $('#callboard');
             var callboardUl = callboard.find('ul');
             var callboardLi = callboard.find('li');
             var liLen = callboard.find('li').length;
             var initHeight = callboardLi.first().outerHeight(true);
             win.autoAnimation = function (){
                 if (liLen <= 1) return;
                 var self = arguments.callee;
                 var callboardLiFirst = callboard.find('li').first();
                 callboardLiFirst.animate({
                     marginTop:-initHeight
                 }, 500, function (){
                     clearTimeout(callboarTimer);
                     callboardLiFirst.appendTo(callboardUl).css({marginTop:0});
                     callboarTimer = setTimeout(self, 5000);
                 });
             }
             callboard.mouseenter(
                 function (){
                     clearTimeout(callboarTimer);
                 }).mouseleave(function (){
                     callboarTimer = setTimeout(win.autoAnimation, 5000);
                 });
         }(window));
         setTimeout(window.autoAnimation, 5000);
        </script> 
    </section>
    
@endsection

