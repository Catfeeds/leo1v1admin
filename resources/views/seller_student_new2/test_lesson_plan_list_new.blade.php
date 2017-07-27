@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <style>
     .input-group{
         width:100%;
     }
     .input-group-w145{
         width:145px !important;
     }
    </style>
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
            </div>
        </div>
        <hr/>
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td style="">客户电话</td>
                        <td style="">年级</td>
                        <td style="">科目</td>
                        <td style="">申请人</td>
                        <td style="">申请时间</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >客户电话:{{$var["phone"]}} </td>
                            <td >年级：{{$var["grade_str"]}} </td>
                            <td >科目: {{$var["subject_str"]}} </td>
                            <td >申请人：{{$var["require_admin_nick"]}}</td>
                            <td >申请时间：{{$var["require_time"]}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>
    </section>
@endsection
