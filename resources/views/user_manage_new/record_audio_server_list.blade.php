@extends('layouts.app')
@section('content')

    <section class="content ">

        <div >
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_clear" >清空无效</button>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr> <td>ip </td> <td>优先级</td> <td>同时最大记录数</td>  <td>声网userid</td>  <td>上报时间</td> <td>说明</td> <td> 操作  </td> </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="{{$var["status_class"]}}">
                        <td>{{$var["ip"]}} </td>
                        <td>{{$var["priority"]}} </td>
                        <td>{{$var["max_record_count"]}} </td>
                        <td>{{$var["config_userid"]}} </td>
                        <td>{{$var["last_active_time"]}} </td>
                        <td>{{$var["desc"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
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
