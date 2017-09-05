@extends('layouts.app')
@section('content')
  <style>
     .panel-green {
        background-color: #5cb85c;
     }
     .panel-red {
        background-color: #d9534f;
     }
    </style>
      <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
      <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
      <script type="text/javascript" src="/js/qiniu/ui.js"></script>
      <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
      <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
      <script type="text/javascript" src="/js/jquery.md5.js"></script>
      <script type="text/javascript" src="/page_js/lib/flow.js"></script>

    <section class="content ">
        
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">产品名称</span>
                        <select class="opt-change form-control " id="id_name" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">优先级</span>
                        <select class="opt-change form-control" id="id_priority" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">目前影响</span>
                        <select class="opt-change form-control " id="id_significance" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">进度(部门)</span>
                        <select class="opt-change form-control " id="id_status" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">产品状态</span>
                        <select class="opt-change form-control " id="id_product_status" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">研发状态</span>
                        <select class="opt-change form-control " id="id_development_status" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">测试状态</span>
                        <select class="opt-change form-control " id="id_test_status" >
                        </select>
                    </div>
                </div>
               
              
            </div>
             <div class="row  " >
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
                    <td style="width:320px">需求故事</td>
                    <td>需求附件</td>
                    <td style="width:320px">验收标准</td>
                    <td>联系方式</td>
                    <td>进度(部门)</td>
                    <td>进度(状态)</td>
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
                        <td>{{@$var["statement"]}}</td>
                        @if ($var['content_pic'] == '')
                            <td></td>
                        @else
                           <td><a href="{{$var['content_pic']}}" target="_blank">下载</td>
                        @endif       

                       
                        <td> {{@$var["notes"]}} </td>
                        <td>{{@$var['create_phone']}}</td>
                        <td>{{@$var['status_str']}}</td>
                        @if ($var['status'] == 2 && $var['product_status'] == 1)
                            <td class="panel-red">{{@$var["operator_status"]}}<br/>驳回原因:{{@$var['product_reject']}}</td>
                        @elseif($var['status'] == 5 && $var['test_status'] == 4)
                            <td class="panel-green">{{@$var["operator_status"]}}</td>
                        @else
                            <td>{{@$var['operator_status']}}</td>
                        @endif
                        <td>{{@$var["operator_nick"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            @if ($var['flag'])
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del"  title="删除"> </a>
                            @endif
                            @if ($var['product_status'] == 1)
                                <a class="fa  opt-re-edit"  title="重新提交">重新提交</a>
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

