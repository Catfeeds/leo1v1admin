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
                    <td>月份</td>
                    <td>渠道</td>
                    <td>Leads数</td>
                    <td>试听数</td>
                    <td>试听转化率</td>
                    <td>下单学生数</td>
                    <td>签单转化率</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $data as $k=>$item )
                    @foreach($item as $key=>$var)
                        @if(is_numeric($key) && $key==1)
                            <tr>
                                <td rowspan="{{@$item["num"]}}" style="text-align:center;vertical-align:middle;">{{@$item["month_str"]}}</td>
                                <td>{{@$var["origin"]}}</td>
                                <td>{{@$var["leads_num"]}}</td>
                                <td>{{@$var["test_num"]}}</td>
                                <td>{{@$var["test_transfor_per"]}}</td>
                                <td>{{@$var["order_stu_num"]}}</td>
                                <td>{{@$var["order_transfor_per"]}}</td>
                                
                            </tr>
                        @endif
                        @if(is_numeric($key) && $key>1)
                            <tr>                               
                                <td>{{@$var["origin"]}}</td>
                                <td>{{@$var["leads_num"]}}</td>
                                <td>{{@$var["test_num"]}}</td>
                                <td>{{@$var["test_transfor_per"]}}</td>
                                <td>{{@$var["order_stu_num"]}}</td>
                                <td>{{@$var["order_transfor_per"]}}</td>
                                
                            </tr>
                        @endif


                    @endforeach
                @endforeach
                   
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

