@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary fa fa-plus" id="id_add">  增加  </button>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>id  </td>
                    <td>名称  </td>
                    <td>说明  </td>
                    <td>ip</td>
                    <td>权值</td>
                    <td>xmpp prot </td>
                    <td>webrtc port </td>
                    <td>websocket xmpp prot </td>

                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["id"]}} </td>
                        <td>{{$var["server_name"]}} </td>
                        <td>{{$var["server_desc"]}} </td>
                        <td>{{$var["ip"]}} </td>
                        <td>{{$var["weights"]}} </td>
                        <td>{{$var["xmpp_port"]}} </td>
                        <td>{{$var["webrtc_port"]}} </td>
                        <td>{{$var["websocket_port"]}} </td>
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
