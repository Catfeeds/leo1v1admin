@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
    <script type="text/javascript" >
     var dateArr= <?php  echo json_encode($dateArr); ?> ;
     var rateArr= <?php  echo json_encode($rateArr); ?> ;
    </script>

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>

            </div>
        </div>
        <hr/>
        <div id="container" style="min-width:400px;height:400px"></div>
    </section>

@endsection
