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
</style>
<section class="content">

    <div class="row">
         <div class="col-xs-12 col-md-2">
            <div class="input-group">
                <span >团队名称</span>
                <select id="id_group" class="opt-change">
                    <option value="-1">[全部]</option>
                    @foreach($group_list as $val)
                        <option value="{{$val['group_id']}}">{{$val['group_name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>   
    </div>
    <hr/>

    <table class="common-table">
        <thead>
            <tr>
                <td style="min-width:100px">团队名称</td>
                <td style="min-width:100px">团员信息</td>
                <td style="min-width:100px">学员量</td>
                <td style="min-width:100px">试听量</td>
                <td style="min-width:100px">签单金额</td>
                <td style="min-width:100px">会员量</td>
                <td style="min-width:100px">签单量</td>
            </tr>
        </thead>
        <tbody>
            @foreach ( $table_data_list as $var )
                <tr>
                    <td>{{$var["group_name"]}} </td>
                    <td>{{$var["phone"]}}/{{$var["nickname"]}} </td>
                    <td>{{$var["cycle_student_count"]}} </td>
                    <td>{{$var["cycle_test_lesson_count"]}} </td>
                    <td>{{$var["cycle_order_money"]}} </td>
                    <td>{{$var["cycle_member_count"]}} </td>
                    <td>{{$var["cycle_order_count"]}} </td>
                </tr>
            @endforeach
        </tbody>
     </table>
        @include("layouts.page")    
</section>

@endsection
