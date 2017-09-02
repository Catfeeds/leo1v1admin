@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

    <section class="content ">
        
        <div>
            <div class="row">
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td> # </td>
                    <td> 老师 </td>
                    <td> 联系方式 </td>
                    <td> 年级段 </td>
                    <td> 第一科目 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["num"]}} </td>
                        <td>{{@$var["teacher_nick"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["grade_part_ex"]}} </td>
                        <td>{{@$var["subject"]}} </td>
                        <td>
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
    </section>
    
@endsection

