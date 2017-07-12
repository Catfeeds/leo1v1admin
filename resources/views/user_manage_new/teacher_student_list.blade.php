@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">老师</span>
                        <input class="opt-change form-control" id="id_teacherid" />
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr> <td>学生</td  <td>科目</td> <td>最后一次上课时间 </td> <td> 操作  </td> </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td> {{$var["student_nick"]}} </td>
                        <td> {{$var["subject_str"]}} </td>
                        <td> {{$var["max_lesson_start"]}} </td>
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

