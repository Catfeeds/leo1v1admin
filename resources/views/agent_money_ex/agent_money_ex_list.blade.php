@extends('layouts.app')
@section('content')
   <script type="text/javascript" src="/page_js/lib/flow.js"></script>
   <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
   <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
   
    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>


                <!-- <div class="col-xs-6 col-md-2">
                     <div class="input-group ">
                     <span class="input-group-addon">grade</span>
                     <input class="opt-change form-control" id="id_grade" />
                     </div>
                     </div>

                     <div class="col-xs-6 col-md-2">
                     <div class="input-group ">
                     <span class="input-group-addon">msg</span>
                     <input class="opt-change form-control" id="id_msg" />
                     </div>
                     </div> -->
                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <button id="id_add" class="btn btn-primary"> add </button>
                        <button id="id_download_blade" class="btn btn-primary">下载excel模板</button>
                        <button id="id_add_by_excel" class="btn btn-primary">excel添加</button>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>用户电话 </td>
                    <td>微信昵称</td>
                    <td>说明 </td>
                    <td>添加时间 </td>
                    <td>操作人</td>
                    <td>金额[元] </td>
                    <td>当前状态 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["phone"]}} </td>
                        <td>{{$var["nickname"]}}</td>
                        <td>{{$var["agent_money_ex_type_str"]}} </td>
                        <td>{{$var["add_time"]}} </td>
                        <td>{{$var["account"]}}&#12288;真实姓名：{{$var["name"]}}</td>
                        <td>{{$var["money"]}} </td>
                        <td> {!!  $var["flow_status_str"] !!} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-times opt-del" title="删除"> </a>
                                <a class="opt-require_agent_money_success" title="申请课程成功" >申</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection

