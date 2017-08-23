@extends('layouts.app')
@section('content')

    <section class="content ">
        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">phone</span>
                        <input class="opt-change form-control" id="id_phone" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td> 一级</td>
                    <td> 二级 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["p_nick"]}}/{{@$var["p_phone"]}} </td>
                        <td> {{@$var["nick"]}}/{{@$var["phone"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                                <a style="display:none;"  class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection


