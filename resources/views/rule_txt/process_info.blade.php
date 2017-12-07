@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <script>
     var qiniu_pub = '{{$qiniu_pub}}';
    </script>

    <section class="content ">

        <div>
            <div class="row" >
                <div class="col-xs-12" style="text-align:center;">
                    <h2>{{@$pro['name']}}</h2>
                    <div class="col-xs-2 pull-right">
                        <div
                            {!!  \App\Helper\Utils::gen_jquery_data($pro )  !!}
                        >
                            @if($edit_flag)
                                <button class="btn btn-warning opt-edit" >编辑 </button>
                            @endif
                        </div>

                    </div>
                </div>
                <div class="col-xs-12" style="text-align:right;padding-right:100px;">
                    <h5>{{@$pro['create_time']}}</h5>
                </div>

                <div class="col-xs-12" >
                    <h4>职能部门:</h4>
                    <div class="col-xs-10 col-offset-xs-2" >
                        {{@$pro['department_str']}}
                    </div>
                </div>

                <div class="col-xs-12" >
                    <h4>适用范围:</h4>
                    <div class="col-xs-10 col-offset-xs-2" >
                        {{@$pro['fit_range']}}
                    </div>
                </div>

                <div class="col-xs-12" >
                    <h4>流程说明:</h4>
                    <div class="col-xs-10 col-offset-xs-2" >
                        {!!@$pro['pro_explain']!!}
                    </div>
                </div>

                <div class="col-xs-12" >
                    <h4>注意事项:</h4>
                    <div class="col-xs-10 col-offset-xs-2" >
                        {!! @$pro['attention']!!}
                    </div>
                </div>
                <div class="col-xs-12" >
                    <h4>流程图:</h4>
                    <img src="{{@$pro['pro_img']}}" width="100%">
                </div>


            </div>
        </div>
        @include("layouts.page")
    </section>

@endsection
