@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">电话</span>
                        <input class="opt-change form-control" id="id_phone" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td> 资源时间 </td>
                    <td> userid </td>
                    <td> 电话 </td>
                    <td> 昵称 </td>
                    <td> 科目 </td>
                    <td> 分类 </td>
                    <td> 负责人 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["add_time"]}} </td>
                        <td>{{$var["userid"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td>{{$var["nick"]}} </td>
                        <td>{{$var["subject_str"]}} </td>
                        <td>{{$var["seller_resource_type_str"]}} </td>
                        <td>
                            {{$var["sub_assign_admin_2_nick"]}} / {{$var["admin_revisiter_nick"]}}
                        </td>

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
