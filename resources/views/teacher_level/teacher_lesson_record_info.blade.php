@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <div>
            <div class="row" >              
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>



            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>老师</td>
                    <td>科目</td>
                    <td>年级</td>
                    
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>
                            @if(@$var["grade_start"]>0)
                                {{@$var["grade_start_str"]}} 至 {{@$var["grade_end_str"]}}
                            @else
                                {{@$var["grade_part_ex_str"]}}
                            @endif
                        </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                               
                                    <a class="opt-first-lesson-video" >第一次视频</a>
                                    <a class="opt-first-lesson-record" >第一次反馈</a>
                                    <a class="opt-fifth-lesson-video" >第五次视频</a>
                                    <a class="opt-ffith-lesson-record" >第五次反馈</a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

