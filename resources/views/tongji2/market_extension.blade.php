@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
      <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
      <script type="text/javascript" src="/js/qiniu/ui.js"></script>
      <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
      <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
      <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2"  >
                    <button type="button" class="btn btn-primary" id="id_add">+ 添加活动</button>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">奖品类型</span>
                        <select class="opt-change form-control" id="id_type" >
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>ID</td>
                    <td>礼品类型</td>
                    <td style="width:120px">标题</td>
                    <td>活动描述</td>
                    <td>活动链接</td>
                    <td>活动状态</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $i => $var )
                    <tr>
                        <td>{{@$var['id']}} </td>
                        <td>{{@$var["gift_type_str"]}} </td>
                        <td>{{@$var["title"]}} </td>
                        <td>{{@$var["act_descr"]}} </td>
                        <td>{{@$var["url"]}} </td>
                        <td>{!!@$var["activity_status_str"]!!} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-file-image-o opt-show" title="活动图片"> </a>
                                @if ($var['activity_status']<2)
                                    <a class="fa fa-trash-o opt-del" title="删除"> </a>
                                    <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                @endif

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
