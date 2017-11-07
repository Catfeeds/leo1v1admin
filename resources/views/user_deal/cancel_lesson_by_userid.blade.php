@extends('layouts.app')
@section('content')
    <script src='/page_js/select_teacher_free_time.js?{{@$_publish_version}}'></script>
    <script src='/page_js/set_lesson_time.js?{{@$_publish_version}}'></script>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <script type="text/javascript" src="/page_js/seller_student/common.js?v=121"></script>

    <script type="text/javascript" src="/page_js/lib/select_dlg.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
       
  
    <section class="content ">
        
        <div>
            <div class="row">
                
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead >
                <tr>
                    <td>老师</td>
                    <td>第一科目</td>
                    <td>第一年级</td>
                    <td>类型</td>
                    <td>课的科目</td>
                    <td>课的年级</td>
                    <td>上课时间</td>
                                                                    
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $k=>$var )
                    <tr>                      
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["t_subject_str"]}} </td>
                        <td>
                            @if($var["grade_start"]>0)
                                {{$var["grade_start_str"]}}--{{$var["grade_end_str"]}}
                            @else
                                {{$var["grade_part_ex_str"]}}
                            @endif
                        </td>
                        <td>{{@$var["lesson_type_str"]}}</td>
                        <td>{{@$var["subject_str"]}}</td>
                        <td>{{@$var["grade_str"]}}</td>
                        <td>{{@$var["lesson_start_str"]}}</td>
                       
                       
                                        
                        <td>
                            <div class="row-data"
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

