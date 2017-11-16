@extends('layouts.app')
@section('content')
<section class="content">

    <div class="row">
        <div class="col-xs-6 col-md-8">
            <div class="input-group ">
                <span class="input-group-addon">数据库</span>
                <select class="input-change form-control opt-change" id="id_db_name" >
                    <option value="db_weiyi">db_weiyi</option>
                    <option value="db_weiyi_admin">db_weiyi_admin</option>
                    <option value="db_tool">db_tool</option>
                    <option value="db_account">db_account</option>

                </select>

            </div>
        </div>



    </div>
    <hr/> 

    <table   class=" common-table "   >
        <thead>
            <tr>
                <td style="width:100px;" >表名</td>
                <td style="width:180px;" >说明</td>
                <td style="min-width:100px">操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                        <td> {{$var["table_name"] }} <td>{{$var["table_comment"] }} 
                    <td  >
                        <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a class="fa-list opt-table-info " title="">表结构</a>
                            <a class="fa-edit opt-edit" title=""> 编辑数据 </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
@endsection

