@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >是否设置过教材</span>
                    <select id="id_textbook_check_flag" class ="opt-change" ></select>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td >姓名</td>
                    <td>手机号</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!} >
                                <a class="opt-teacher-info" title="老师信息">老师信息</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
