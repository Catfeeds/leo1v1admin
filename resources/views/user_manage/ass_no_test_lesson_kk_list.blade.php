@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_edit.js?v={{@$_publish_version}}"></script>
<section class="content " >
    <div class="row ">
        <div class="col-xs-12 col-md-5">
            <div id="id_date_range" >
            </div>
        </div> 
    </div>
    <hr/>
        <table   class=" common-table "    >
            <thead>
                <tr>
                   
                    <td >助教</td>
                    <td >未试听扩课数</td>
                   
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td  >{{$var["name"]}} </td>
                        <td >{{$var["hand_kk_num"]}}</td>

                        <td  >
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-edit opt-edit " title="编辑数量" ></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @include("layouts.page")

   
@endsection
