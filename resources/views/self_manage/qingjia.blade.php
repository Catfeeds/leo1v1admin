@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <section class="content ">

        <div>
            <div class="row  " >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-3 col-md-2"  data-title="时间段">
                    <button class="btn btn-primary" id="id_add"> 请假申请 </button>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>申请时间</td>
                    <td>请假类型</td>
                    <td>请假开始时间</td>
                    <td>请假结束时间</td>
                    <td>请假时长</td>
                    <td>说明</td>
                    <td>状态</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["add_time"]}} </td>
                        <td>{{$var["type_str"]}} </td>
                        <td>{{$var["start_time"]}} </td>
                        <td>{{$var["end_time"]}} </td>
                        <td>{{$var["hour_count_str"]}} </td>
                        <td>{{$var["msg"]}} </td>
                        <td>{!!$var["flow_status_str"]!!} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa  opt-flow-def-list" >审核预期流程 </a>
                                <a class="fa  opt-flow-node-list">审核进度</a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
