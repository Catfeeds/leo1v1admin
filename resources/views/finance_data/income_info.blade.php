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
                    <td rowspan="2">月份</td>
                    <td colspan="2">收入</td>
                    <td colspan="2">签单人数</td>
                    <td colspan="2">客单价格</td>
                                                                                        
                    <td>操作 </td>
                </tr>
                <tr>
                    <td>新签收入</td>
                    <td>续费收入</td>
                    <td>新签人数</td>
                    <td>续费人数</td>
                    <td>新签客单价</td>
                    <td>续费客单价</td>
                    <td></td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $k=>$var )
                    <tr>                      
                        <td>{{@$var["month_str"]}} </td>
                        <td>{{@$var["new_order_money"]/100}}</td>
                        <td>{{@$var["renew_order_money"]/100}}</td>
                        <td>{{@$var["new_order_stu"]}}</td>
                        <td>
                            @if(!empty($var["renew_order_stu"]))
                                {{@$var["renew_order_stu"]}}
                            @endif
                        </td>
                        <td>
                            @if(!empty($var["new_signature_price"]))
                                {{@$var["new_signature_price"]/100}}
                            @endif
                        </td>
                        <td>
                            @if(!empty($var["renew_signature_price"]))
                                {{@$var["renew_signature_price"]/100}}
                            @endif
                        </td>
                                                                                      
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

