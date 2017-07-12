@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">离职</span>
                <select class="opt-change form-control" id="id_del_flag" >
                </select>
            </div>
        </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>账号</td>
                    <td>离职</td>
                    <td>例子个数</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["account"]}} </td>
                        <td>{{$var["del_flag_str"]}} </td>
                        <td>{{$var["count"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                                    <a  title="清空例子" class=" fa-undo  opt-clean-seller-student">清空例子</a>
                                    <a  title="明细" class=" fa-list opt-show"></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
