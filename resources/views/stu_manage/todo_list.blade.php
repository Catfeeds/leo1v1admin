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
                    <td>学生ID</td>
                    <td>登录时间</td>
                    <td>昵称</td>
                    <td>ip</td>
                    <td>role</td>
                    <td>登录方式</td>
                    <td>方式</td>
                    <td>附件</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["uerid"]}} </td>
                        <td>{{@$var["login_time"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["ip"]}} </td>
                        <td>{{@$var["role"]}} </td>
                        <td>{{@$var["login_type"]}} </td >
                        <td>{{@$var["dymanic_flag"]}} </td>

                        <td>
                            <div class="row-data"
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="btn fa fa-link opt-out-link" title="对外视频发布链接"></a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
