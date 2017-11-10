@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
  <script type="text/javascript" src="/source/jquery.fancybox.js"></script>
  <link rel="stylesheet" type="text/css" href="/source/jquery.fancybox.css" media="screen" />
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <button id="id-add-wx-record" class="btn btn-warning fa fa-plus fa-lg form-control " >推送新消息</button>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td> 推送时间 </td>
                    <td> 类型  </td>
                    <td> 标题 </td>
                    <td> 开头语 </td>
                    <td> keyword1 </td>
                    <td> keyword2 </td>
                    <td> keyword3 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["send_time_str"]}} </td>
                        <td>{{@$var["template_type_str"]}} </td>
                        <td>{{@$var["title"]}} </td>
                        <td>{{@$var["first_sentence"]}} </td>
                        <td>{{@$var["keyword1"]}} </td>
                        <td>{{@$var["keyword2"]}} </td>
                        <td>{{@$var["keyword3"]}} </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @include("layouts.page")
    </section>

@endsection
