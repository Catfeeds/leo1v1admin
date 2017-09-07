@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" > 
     var g_uid   = "{{@$ret_info["uid"]}}";
    </script>  

    
    <style type="text/css">
     .row-td-field-name {
         padding-right: 0px; 
     }

     .row-td-field-value {
         padding-left:0px;
         padding-right: 0px;
     }

     .row-td-field-name >  span {
         background-color: #eee;
         border: 1px solid #ccc;
         border-collapse: separate;
         color: #555;
         display: table-cell;
         font-size: 14px;
         font-weight: normal;
         line-height: 1;
         padding: 6px 12px;
         text-align: right;
         vertical-align: middle;
         width: 1%;
         height: 26pt;
     }

     .row-td-field-value >  span {
         border: 1px solid #ccc;
         border-collapse: separate;
         color: #555;
         display: table-cell;
         font-size: 14px;
         font-weight: normal;
         line-height: 1;
         padding: 6px 12px;
         vertical-align: middle;
         height: 26pt;
         width: 1%;
         text-align:left ;
         background-color: #FFF;
     }

     p {margin-left: 20px}
    </style>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/jquery.query.js"></script>
    <script src="/page_js/enum_map.js" type="text/javascript"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-2">
                <div class="row" >
                    <div class="col-xs-6 col-md-12" style="text-align:center;" >
                        <div class="header_img">
                            @if ($ret_info["face_pic"])
                            <img style="border-radius:200px;width:200px;height: 200px;"  src="{{@$ret_info["face_pic"]}}" />
                            @else
                            <img style="border-radius:200px;width:200px;height: 200px;"  src="http://7u2f5q.com2.z0.glb.qiniucdn.com/fdc4c3830ce59d611028f24fced65f321504755368876.png"
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-10"   >
                <div style="width:98%" id="id_tea_info"
                    <div class="row">
                        <div class="col-xs-6 col-md-10  row-td-field-value"  data-teacherid="">
                        <button style="margin-left:10px"  id="id_upload_face" type="button" class="btn btn-primary" >上传头像</button> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>


@endsection


