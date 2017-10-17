@extends('layouts.app')
@section('content')
    
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <section class="content ">
        
        <div>
            <div class="row " >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
   
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>科目 </td>
                    <td>在读学生人数 </td>
                    <td>授课老师人数 </td>
                    <td>试听课数</td>
                    <td>入职老师人数</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $list as $var )
                    <tr>
                        <td>{{@$var["subject"]}}</td>
                        <td>{{@$var["stu_num"]}} </td>
                        <td>{{@$var["tea_num"]}} </td>
                        <td>{{@$var["test_lesson_num"]}} </td>
                        <td>{{@$var["new_num"]}} </td>
                       
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

