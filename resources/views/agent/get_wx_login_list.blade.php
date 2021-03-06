@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">to_agentid</span>
                        <input class="opt-change form-control" id="id_to_agentid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">类型</span>
                        <input class="opt-change form-control" id="id_agent_wx_msg_type" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>时间 </td>
                    <td>发送给 </td>
                    <td>分类</td>
                    <td>内容 </td>
                    <td>发送成功</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["log_time"]}} </td>
                        <td>{{@$var["phone"]}}/{{@$var["nickname"]}} </td>
                        <td>{{@$var["agent_wx_msg_type_str"]}} </td>
                        <td>{{@$var["msg"]}} </td>
                        <td>{{@$var["succ_flag_str"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
