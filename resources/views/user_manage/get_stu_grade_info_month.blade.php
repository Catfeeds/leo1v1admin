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
                    <td >小一</td>                   
                    <td >小二</td>                   
                    <td >小三</td>                   
                    <td >小四</td>                   
                    <td >小五</td>                   
                    <td >小六</td>                   
                    <td >初一</td>                   
                    <td >初二</td>                   
                    <td >初三</td>                   
                    <td >高一</td>                   
                    <td >高二</td>                   
                    <td >高三</td>                   
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $k=>$var)
                    @if($k<9)
                        <tr>
                            <td >{{@$k}}</td>
                            <td >{{@$var[102]["num"]}}</td>
                            <td >{{@$var[103]["num"]}}</td>
                            <td >{{@$var[104]["num"]}}</td>
                            <td >{{@$var[105]["num"]}}</td>
                            <td >{{@$var[106]["num"]}}</td>
                            <td >{{@$var[201]["num"]}}</td>
                            <td >{{@$var[202]["num"]}}</td>
                            <td >{{@$var[203]["num"]}}</td>
                            <td >{{@$var[301]["num"]}}</td>
                            <td >{{@$var[302]["num"]}}</td>
                            <td >{{@$var[303]["num"]}}</td>
                            <td >{{@$var[401]["num"]}}</td>

                            
                            
                            

                            <td  >
                                <div
                                    {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                </div>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td >{{@$k}}</td>
                            <td >{{@$var[101]["num"]}}</td>
                            <td >{{@$var[102]["num"]}}</td>
                            <td >{{@$var[103]["num"]}}</td>
                            <td >{{@$var[104]["num"]}}</td>
                            <td >{{@$var[105]["num"]}}</td>
                            <td >{{@$var[106]["num"]}}</td>
                            <td >{{@$var[201]["num"]}}</td>
                            <td >{{@$var[202]["num"]}}</td>
                            <td >{{@$var[203]["num"]}}</td>
                            <td >{{@$var[301]["num"]}}</td>
                            <td >{{@$var[302]["num"]}}</td>
                            <td >{{@$var[303]["num"]}}</td>

                            
                            
                            

                            <td  >
                                <div
                                    {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                </div>
                            </td>
                        </tr>
                    @endif

                    
                @endforeach
            </tbody>
        </table>
    @include("layouts.page")


   
@endsection


