@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_edit.js?v={{@$_publish_version}}"></script>
<section class="content " >
        <hr/> 
        <table   class=" common-table "    >
            <thead>
                <tr>
                    <td >月份</td>
                    <td >语文</td>
                    <td >数学</td>
                    <td >英语</td>
                    <td >化学</td>
                    <td >物理</td>
                    <td >生物</td>
                    <td >政治</td>
                    <td >历史</td>
                    <td >地理</td>
                    <td >科学</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td >{{@$var["month"]}}</td>
                        <td >{{@$var[1]}}</td>
                        <td >{{@$var[2]}}</td>
                        <td >{{@$var[3]}}</td>
                        <td >{{@$var[4]}}</td>
                        <td >{{@$var[5]}}</td>
                        <td >{{@$var[6]}}</td>
                        <td >{{@$var[7]}}</td>
                        <td >{{@$var[8]}}</td>
                        <td >{{@$var[9]}}</td>
                        <td >{{@$var[10]}}</td>
                        <td  >
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @include("layouts.page")


   
@endsection


