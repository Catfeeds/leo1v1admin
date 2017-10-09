@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">account</span>
                        <input class="opt-change form-control" id="id_account" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <button id="id_add" class="btn btn-primary"> add </button>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>字段11 </td>
                    <td>字段11 </td>
                    <td>字段11 </td>
                    <td>字段11 </td>
                    <td>字段11 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td>{{@$var["serverip"]}} </td>
                        <td>{{@$var["type"]}} </td>
                        <td>{{@$var["login_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

