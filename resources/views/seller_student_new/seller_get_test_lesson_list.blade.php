@extends('layouts.app')
@section('content')

    <section class="content ">

        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>userid </td>
                    <td>昵称</td>
                    <td> 电话</td>
                    <td>上课时间</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["userid"]}} </td>
                        <td>{{@$var["student_nick"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["lesson_start"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="btn  fa-chevron-left  opt-set_user_free" title="回流公海">回流公海 </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
