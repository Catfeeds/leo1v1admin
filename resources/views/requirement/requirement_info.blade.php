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
            <div class="row" >
                
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-warning" id="id_add_requirement_info">添加需求信息</button>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>#</td>
                    <td>需求编号</td>
                    <td>产品名称</td>
                    <td>优先级</td>
                    <td>目前影响</td>
                    <td>提交人</td>
                    <td>提交时间</td>
                    <td>期待交付时间</td>
                    <td>需求说明</td>
                    <td>内容截图</td>
                    <td>备注</td>
                    <td>部门</td>
                    <td>状态</td>
                    <td>处理人</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["num"]}} </td>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["name_str"]}} </td>
                        <td>{{@$var["priority_str"]}} </td>
                        <td>{{@$var["significance_str"]}} </td>
                        <td>{{@$var["create_admin_nick"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["expect_time"]}} </td>
                        <td>{{@$var["statement"]}} </td>
                        @if ($var['content_pic'] == '')
                            <td></td>
                        @else
                           <td><a href="{{$var['content_pic']}}" target="_blank">下载</td>
                        @endif       
                        <td>{{@$var["notes"]}} </td>
                        <td>{{@$var["status_str"]}} </td>
                        <td>{{@$var["operator_status"]}} </td>
                        <td>{{@$var["operator"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            @if ($var['flag'])
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del"  title="删除"> </a>
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

