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
                    <td >1科目</td>
                    <td >2科目</td>
                    <td >3科目</td>
                    <td >4科目及以上</td>                   
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


