@extends('layouts.app')
@section('content')
    <section class="content ">
        <table class="common-table">
            <thead>
                <tr>
                    <td>id</td>
                    <td>组名</td>
                    <td>email</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["groupid"]}} </td>
                        <td>{{@$var["title"]}} </td>
                        <td>{{@$var["email"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>
                                <a class="fa fa-users opt-show-user-list" title="用户列表"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
