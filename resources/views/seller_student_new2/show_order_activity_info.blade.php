@extends('layouts.app')
@section('content')

    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-8">
                    <div class="input-group ">
                        <span class="input-group-addon">活动类型</span>
                        <select class="opt-change form-control" id="id_order_activity_type" >
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>项目</td>
                    <td>说明</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var[0]}} </td>
                        <td>{!! @$var[1] !!} </td>
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
