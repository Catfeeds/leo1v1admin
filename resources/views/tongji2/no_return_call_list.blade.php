@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row" >
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >销售</span>
                        <input id="id_seller_adminid"  class="opt-change" />
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>uid</td>
                    <td>销售名称</td>
                    <td>未回访数量</td>
                    <td>未回访用户</td>
                    <td>更新时间</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["uid"]}} </td>
                        <td>{{$var["account"]}} </td>
                        <td>{{$var["no_return_call_num"]}} </td>
                        <td>{{$var["no_call_str"]}} </td>
                        <td>{{$var["add_time"]}} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
