@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">分享者</span>
                <input class="opt-change form-control" id="id_from_adminid" />
            </div>
        </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>时间</td>
                    <td>分享着 </td>
                    <td>浏览者ip</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["log_time"]}} </td>
                        <td>{{@$var["from_adminid_nick"]}} </td>
                        <td>{{@$var["ip"]}} </td>
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
