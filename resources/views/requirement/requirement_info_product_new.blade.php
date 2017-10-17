@extends('layouts.app')
@section('content')
  <style>
     .panel-green {
        background-color: #5cb85c;
     }
     .panel-red {
        background-color: #d9534f;
     }
     .panel-grey {
        background-color: #666666;
     }
    </style>
      <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
      <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
      <script type="text/javascript" src="/js/qiniu/ui.js"></script>
      <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
      <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
      <script type="text/javascript" src="/js/jquery.md5.js"></script>
      <script type="text/javascript" src="/page_js/lib/flow.js"></script>
      <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
      <script type="text/javascript" src="/page_js/select_user.js"></script>
      <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

    <section class="content ">
        
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>
                
               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">优先级</span>
                        <select class="opt-change form-control" id="id_priority" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >产品经理</span> 
                        <select id="id_productid" class="opt-change">
                                <option value="-1">产品经理</option>
                                <option value="448">夏宏东</option>
                                <option value="919">邓晓玲</option>
                                <option value="1118">孙瞿</option>
                                <option value="1167">杨磊</option>
                                <option value="974">付玉文</option>
                                <option value="871">邓春燕</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">处理状态</span>
                        <select class="opt-change form-control " id="id_product_status" >
                        </select>
                    </div>
                </div>
                
                
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>需求编号</td>
                    <td>需求名称</td>
                    <td>优先级</td>
                    <td style="width:320px">需求描述</td>
                    <td style="width:320px">需求来源</td>
                    <td>附件</td>
                    <td>期望完成时间</td>
                    <td>预估完成时间</td>
                    <td>产品经理</td>
                    <td>处理状态</td>
                    <td>备注</td>
                    <td>创建时间</td>
                    <td>创建人</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["product_name"]}} </td>
                        <td>{{@$var["priority_str"]}} </td>
                        <td>{{@$var["statement"]}} </td>
                        <td>{{@$var["notes"]}} </td>
                        <td>
                            @if($var['content_pic'])
                                <a href='{{@$var["content_pic"]}}' target="_blank">下载</a>
                            @else
                            @endif
                        </td>
                        <td>{{@$var["expect_time"]}} </td>
                        <td>{{@$var["forecast_time"]}}</td>
                        <td>{{@$var['operator_nick']}}</td>
                        @if ($var['status'] == 2 && $var['product_status'] == 1)
                            <td >{{@$var["operator_status"]}}<br/>驳回原因:{{@$var['product_reject']}}</td>
                        @else
                            <td>{{@$var['operator_status']}}</td>
                        @endif
                        <td>{{@$var['product_comment']}}</td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var['create_admin_nick']}}</td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            @if ($var['product_status'] == 2 ||$var['product_status'] == 3||$var['product_status'] == 1)
                                 <a class="fa  opt-re-edit"  title="编辑">编辑</a>
                                 <a class="fa  opt-reject"  title="驳回">驳回</a>
                                 <a class="fa  opt-deal"  title="完成">完成</a>
                            @endif

                            @if ($var['product_status'] == 4)
                                 <a class="fa  opt-re-edit"  title="编辑">编辑</a>
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

