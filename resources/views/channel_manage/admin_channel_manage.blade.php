@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.base64.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>

    <link href="/css/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        
        <div>
            <div class="row" >

                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_add_channel">新增主渠道</button>
                </div> 
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>主渠道</td>
                    <td>次渠道</td>
                    <td>成员</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="{{$var["level"]}}">
                        <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >
                            {{$var["channel_name"]}}
                        </td>
                        <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >
                            {{$var["group_name"]}}
                        </td>
                        <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >
                            {{@$var["admin_name"]}} {{@$var['admin_phone']}}
                        </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa opt-edit"  title="修改"> 修改</a>
                                <a class="fa opt-assign-channel"  title=""> 分配渠道</a>
                                <a class="fa opt-assign-admin"  title=""> 分配成员</a>
                                <a class="fa opt-add_other_teacher"  title=""> 新增招师代理</a>
                                <a class="fa opt-tea_origin_url" title="">招</a>
                                <a class="fa opt-detail" title="详细">详细</a>
                                <a class="fa opt-edit_other_teacher" title="">修改招师代理信息</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
        
    </section>
    
@endsection

