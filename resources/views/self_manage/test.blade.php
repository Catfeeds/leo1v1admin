@extends('layouts.app')
@section('content')

    <style >


    </style>
    <script type="text/javascript" src="/page_ts/lib/admin_new_query.js"></script>

    <section class="content ">

        <div id="id_header_query_info" >
        </div>




        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>字段1 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var[""]}} </td>
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
