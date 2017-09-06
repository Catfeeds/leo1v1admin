@extends('layouts.app')
@section('content')
<script src='/js/moment.js'></script>
<link rel='stylesheet' href='/css/fullcalendar.css' />
<script src='/js/fullcalendar.js'></script>
<script src='/js/lang-all.js'></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>

<style>
 .fc-event {
     border-radius:0px;
 }
</style>
<section class="content">
    <div class="row" >
               

        <div class="col-md-1 remove-for-xs col-xs-6 " >
            <div>
                <button class="btn btn-primary" id="id_upload_xls"> 上传xls </button>
            </div>
        </div>
    </div>
  </section>
@endsection
