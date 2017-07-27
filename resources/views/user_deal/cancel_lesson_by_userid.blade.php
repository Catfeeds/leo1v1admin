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
                    <td>2017-06-18</td>
                    <td>2017-06-19</td>
                    <td>2017-06-25</td>
                    <td>2017-06-26</td>
                    <td>2017-07-02</td>
                    <td>2017-07-03</td>
                    <td>2017-07-09</td>
                    <td>2017-07-10</td>
                    <td>2017-07-16</td>
                    <td>2017-07-17</td>
                    <td>星期一(天)</td>
                    <td>星期一(小时)</td>
                    <td>星期天(天)</td>
                    <td>星期天(小时)</td>
                   
                   
                                                  
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $k=>$var )
                    <tr>                      
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["20170618"]}}</td>
                        <td>{{@$var["20170619"]}}</td>
                        <td>{{@$var["20170625"]}}</td>
                        <td>{{@$var["20170626"]}}</td>
                        <td>{{@$var["20170702"]}}</td>
                        <td>{{@$var["20170703"]}}</td>
                        <td>{{@$var["20170709"]}}</td>
                        <td>{{@$var["20170710"]}}</td>
                        <td>{{@$var["20170716"]}}</td>
                        <td>{{@$var["20170717"]}}</td>
                        <td>{{@$var["one_day"]}}</td>
                        <td>{{@$var["one_hour"]}}</td>
                        <td>{{@$var["seven_day"]}}</td>
                        <td>{{@$var["seven_hour"]}}</td>

                       
                                        
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

