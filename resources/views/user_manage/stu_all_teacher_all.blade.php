@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">助教</span>
                        <input class="opt-change form-control" id="id_assistantid" />
                    </div>
                </div>
            </div>


        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td> # </td>
                    <td> 老师 </td>
                    <td> 助教 </td>
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
                        <td><a href="{{url('teacher_info_admin/index?teacherid=').$var['teacherid']}}" target="_blank">{{@$var["teacher_nick"]}}</a> </td>
                        <td>{{@$var['assistant_nick']}}</td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["grade_part_ex_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
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