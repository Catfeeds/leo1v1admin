@extends('layouts.agent_header')
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
 .false{
     color:red;
 }
 .bg_train_lesson{
     background-color:#ccc;
 }
 .btn{
     padding:9px 13px;
 }
</style>
<section class="content li-section">
    <div>
        <div class="row" >
            <div class="col-xs-12 col-md-5"  data-title="时间段">
                <div  id="id_date_range" >
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td style="min-width:100px">团长名字</td>
                <td style="min-width:100px">团队名字</td>
                <td style="min-width:100px">学员量</td>
                <td style="min-width:100px">试听量</td>
                <td style="min-width:100px">签单量</td>
                <td style="min-width:100px">签单金额</td>
                <td style="min-width:100px">会员量</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$colconel_statistics['colconel_name']}}</td>
                <td></td>
                <td>{{$colconel_statistics['student_count']}}</td>
                <td>{{$colconel_statistics['test_lesson_count']}}</td>
                <td>{{$colconel_statistics['order_count']}}</td>
                <td>{{$colconel_statistics['order_money']}}</td>
                <td>{{$colconel_statistics['member_count']}}</td>
                <td>注：整体统计</td>
            </tr>
            <tr>
                <td>{{$colconel_info['colconel_name']}}</td>
                <td></td>
                <td>{{$colconel_info['student_count']}}</td>
                <td>{{$colconel_info['test_lesson_count']}}</td>
                <td>{{$colconel_info['order_count']}}</td>
                <td>{{$colconel_info['order_money']}}</td>
                <td>{{$colconel_info['member_count']}}</td>
                <td>注：团长业绩</td>
            </tr>
            @foreach ( $group_info as $var )
                <tr>
                    <td>{{$var["colconel_name"]}}</td>
                    <td>{{$var["group_name"]}} </td>
                    <td>{{$var["student_count"]}} </td>
                    <td>{{$var["test_lesson_count"]}} </td>
                    <td>{{$var["order_count"]}} </td>
                    <td>{{$var["order_money"]}} </td>
                    <td>{{$var["member_count"]}} </td>
                    <td>
                        <div
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a class="fa-user opt-members" title="团队明细" ></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
     </table>
</section>

@endsection

<div class="row">
    <div class="col-xs-6 col-md-2">
    </div>
</div>
