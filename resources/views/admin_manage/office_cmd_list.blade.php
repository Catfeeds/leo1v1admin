@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-2">
                    <button id="id_add" class="btn btn-primary" >新增命令</button>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>申请时间 </td>
                    <td>设备类型 </td>
                    <td>设备id </td>
                    <td>开启</td>
                    <td>其他信息 </td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["office_device_type_str"]}} </td>
                        <td>{{@$var["device_id"]}} </td>
                        <td>{{@$var["device_opt_type_str"]}}</td>
                        <td>{{@$var["value"]}}</td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
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
