@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >老师</span>
                        <input id="id_teacherid"  /> 
                    </div>

                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">学员类型</span>
                        <select class="opt-change form-control " id="id_student_type" >
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td> 姓名 </td>
                    <td> 学员类型 </td>
                    <td> 家长姓名 </td>
                    <td> 联系电话 </td>
                    <td> 年级 </td>
                    <td> 签约课时 </td>
                    <td> 剩余课时 </td>
                    <td> 最后一次上课时间</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["type_str"]}} </td>
                        <td>{{@$var["parent_name"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["grade"]}} </td>
                        <td>{{@$var["lesson_count_all"]}} </td>
                        <td>{{@$var["lesson_count_left"]}} </td>
                        <td>{{@$var["last_lesson_time"]}} </td>
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

