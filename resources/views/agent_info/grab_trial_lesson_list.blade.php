@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<style>
 textarea{
     resize:both;
 }
 table {
     font-size :14px;
 }
 .false {
     color:red;
 }
 .notice {
     font-size:28px;
     font-weight:blod;
     color:red;
 }
</style>
<script>
 var grabid = '{{$grabid}}';
</script>
<section class="content">
    <div class="row">
        @if(isset($err_info) && $err_info!="")
            <div align="center" class="notice">
                {{$err_info}}
            </div>
        @endif
    </div>
    <hr/>
    @if($err_info=="")
    <table class="common-table">
        <thead>
            <tr>
                <td style="min-width:200px">学生信息</td>
                <td style="min-width:300px">试听需求</td>
                <td style="min-width:300px">操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $key =>$var)
                <tr>
                    <td >
                        学生：{{$var["nick"]}}<br/>
                        科目：{{$var["subject_str"]}}<br/>
                        年级：{{$var["grade_str"]}}<br/>
                        教材版本：{{$var["editionid_str"]}}<br/>
                        期待上课时间：{{$var["stu_request_test_lesson_time_str"]}}<br/>
                        排课老师：{{$var["accept_account"]}}<br/>
                        试卷：{!!$var["stu_paper_str"]!!}<br/>
                    </td>
                    <td >{{$var["stu_request_test_lesson_demand"]}}</td>
                    <td >
                        <div class="lesson_data"
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a class="opt-grab_trial_lesson" title="点击抢课" >抢课</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @include("layouts.page")
</section>
@endsection
