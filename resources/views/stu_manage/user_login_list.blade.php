@extends('layouts.stu_header')
@section('content')
    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-5" data-title="时间段">
                <div id="id_date_range"></div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">使用动态密码</span>
                    <select id="id_dymanic_flag" class="opt-change">
                    </select>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>ip</td>
                    <td>登录时间</td>
                    <td>是否使用临时密码</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["ip"]}} </td>
                        <td>{{@$var["login_time"]}} </td>
                        <td>{{@$var["dymanic_flag_str"]}} </td>
                        <td>
                            <div class="row-data"
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
