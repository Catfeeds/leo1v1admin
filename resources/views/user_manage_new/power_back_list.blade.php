@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-3">
                    <div id="id_date_range">
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>添加时间 </td>
                    <td>权限组id </td>
                    <td>权限组名 </td>
                    <td>权限 </td>
                    <td>角色组id </td>
                    <td>操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["log_date"]}} </td>
                        <td>
                            {{$var["groupid"]}}
                        </td>
                        <td>{{$var["group_name"]}} </td>
                        <td><div style="width: 600px; overflow: hidden; text-overflow:ellipsis; white-space: nowrap;">{{$var["group_authority"]}}</div> </td>
                        <td>{{$var["role_groupid"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="更新权限"> </a>
                                <!-- 
                                <a class="fa fa-times opt-del" title="删除"> </a>
                                -->

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
