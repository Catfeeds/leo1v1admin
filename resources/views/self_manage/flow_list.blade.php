@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <section class="content ">

        <div>

            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">状态</span>
                        <select class="opt-change form-control" id="id_flow_check_flag" >
                        </select>
                    </div>
                </div>


                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人</span>
                        <input class="opt-change form-control" id="id_post_adminid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">类型</span>
                        <select class="opt-change form-control" id="id_flow_type" >
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>时间</td>
                    <td>审批分类</td>
                    <td>阶段</td>
                    <td>申请人</td>
                    <td>申请时间</td>
                    <td>信息</td>
                    <td>附加信息</td>
                    <td>当前状态</td>
                    <td>我审批</td>
                    <td>审批时间</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["add_time"]}} </td>
                        <td>{{$var["flow_type_str"]}} </td>
                        <td>{{$var["node_name"]}} </td>
                        <td>{{$var["post_admin_nick"]}} </td>
                        <td>{{$var["post_time"]}} </td>
                        <td>{!! $var["line_data"] !!} </td>
                        <td>{{$var["post_msg"]}} </td>
                        <td> {!!  $var["flow_status_str"] !!} </td>
                        <td> {{$var["flow_check_flag_str"]}} </td>
                        <td>{{$var["check_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-gavel opt-check"  title="审批"> </a>

                                <a class="fa  opt-flow-def-list fa-facebook-square" title="审核预期流程" >   </a>
                                <a class="fa  opt-flow-node-list fa-facebook " title="审核进度"></a>

                            </iv>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
