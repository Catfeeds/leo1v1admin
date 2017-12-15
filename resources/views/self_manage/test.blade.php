@extends('layouts.app')
@section('content')

    <style >

     .header-query-info .query-list .select-list-item {

         margin-right : 8pt;
         margin-top: 5px;
     }


     .header-query-info .used-query-list >a {
         margin-top: 5px;
         margin-right : 8pt;
     }

     .header-query-info .used-query-list >div {
         margin-top: 5px;
         margin-right : 8pt;
     }

     .header-query-info .query-list .query-title   {
         margin-right : 15pt;

     }


     .header-query-info .select-menu-list .select {
         background-color :  #f39c12;
     }
     .header-query-info .select-menu-list .select:hover {
         background-color :  #f39c12;
     }

     .admin-multi-select  .dropdown-menu  .select {
         background-color :  #f39c12;
     }


     .admin-multi-select .dropdown-menu  .select:hover {
         background-color :  #f39c12;
     }

     .header-query-info .select-menu-list {
         font-size:18px;
     }

    </style>
    <script type="text/javascript" src="/js/bootstrap-multiselect.js"></script>
    <script type="text/javascript" src="/page_ts/self_manage/admin_new_query.js"></script>
    <link rel="stylesheet" href="/css/bootstrap-multiselect.css" type="text/css"/>

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
