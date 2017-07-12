@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content ">
        
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>grouid</td>
                    <td>名称</td>
                    <td>主管</td>
                    <td>分配比例</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["groupid"]}} </td>
                        <td>{{@$var["group_name"]}} </td>
                        <td>{{@$var["master_nick"]}} </td>
                        <td>{{@$var["main_assign_percent"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit" title="配置比例"> </a>
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

