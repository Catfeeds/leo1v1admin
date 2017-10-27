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
<section class="content li-section">

    <div class="row">
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <button id="id_add" class="btn btn-primary"> 建立优学优享团</button>
            </div>
        </div>
            
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td style="min-width:100px">团队名称</td>
                <td style="min-width:100px">人数</td>
                <td style="min-width:100px">建立时间</td>
                <td style="min-width:100px">团长信息</td>
                <td style="min-width:300px">管理</td>
            </tr>
        </thead>
        <tbody>
            @foreach ( $table_data_list as $var )
                <tr>
                    <td>{{$var["group_name"]}} </td>
                    <td>{{$var["member_num"]}} </td>
                    <td>{{$var["create_time"]}} </td>
                    <td>{{$var["phone"]}}/{{$var["nickname"]}} </td>
                    <td>
                        <div
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a class="fa fa-edit opt-edit"  title="编辑团队名称"> </a>

                            <a class="fa-user opt-user " title="添加团队成员" ></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
     </table>
        @include("layouts.page")    
</section>

@endsection
