@extends('layouts.stu_header')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content ">
        <div class="row">
           <div class="col-xs-6 col-md-2">
               <button class="btn btn-warning" id="id_add_login_new">测试增加</button>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>nick</td>
                    <td>登录时间</td>
                    <td>ip</td>
                    <td>role</td>
                    <td>登录方式</td>
                    <td>是否使用临时密码</td>
                    <td>附件</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["login_time"]}} </td>
                        <td>{{@$var["ip"]}} </td>
                        <td>{{@$var["role_str"]}} </td>
                        <td>{{@$var["login_type"]}} </td >
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
