@extends('layouts.app')
@section('content')

    <section class="content ">

        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>顺序</td>
                    <td>标题</td>
                    <td>url</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["order_index"]}} </td>
                        <td>{{@$var["title"]}} </td>
                        <td>{{@$var["url"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-arrow-up opt-up"  title=""></a>
                                <a class="fa fa-arrow-down opt-down"  title=""></a>
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
