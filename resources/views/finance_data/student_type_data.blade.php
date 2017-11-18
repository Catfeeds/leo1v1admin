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
                    <td>学生类型及科目</td>
                    <td>统计类型</td>
                    @foreach($data as $val)
                        <td>{{$val["month_str"]}}</td>
                    @endforeach
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                <tr>
                    <td>试听学生-语文</td>
                    <td>单科</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["test_chinese_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>试听学生-数学</td>
                    <td>单科</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["test_math_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>试听学生-英语</td>
                    <td>单科</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["test_english_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>试听学生-小学科</td>
                    <td>单科</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["test_minor_subject_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>试听学生-新增去重</td>
                    <td>合计</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["test_all_subject_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>


                <tr>
                    <td></td>
                    <td></td>
                    @foreach ( $data as $k=>$var )
                        <td></td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>

                <tr>
                    <td>新增学生-语文</td>
                    <td>单科</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["increase_chinese_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>新增学生-数学</td>
                    <td>单科</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["increase_math_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>新增学生-英语</td>
                    <td>单科</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["increase_english_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>新增学生-小学科</td>
                    <td>单科</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["increase_minor_subject_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>新增学生-新增去重</td>
                    <td>合计</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["increase_all_subject_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    @foreach ( $data as $k=>$var )
                        <td></td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>


                <tr>
                    <td>试听-新增转化率</td>
                    <td>合计</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["increase_test_rate"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    @foreach ( $data as $k=>$var )
                        <td></td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>在读学生-语文</td>
                    <td>单科</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["read_chinese_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>在读学生-数学</td>
                    <td>单科</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["read_math_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>在读学生-英语</td>
                    <td>单科</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["read_english_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>在读学生-小学科</td>
                    <td>单科</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["read_minor_subject_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>在读学生-新增去重</td>
                    <td>合计</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["read_all_subject_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>


               



            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

