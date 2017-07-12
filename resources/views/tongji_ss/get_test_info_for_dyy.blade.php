@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>

    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-4" >
                    <div id="id_date_range"> </div>
                </div>

            </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                                      
                    <td>用户名</td>
                    <td>用户进入时间</td>
                    <td>用户电话</td>
                    <td>广告渠道</td>
                    <td>地区</td>
                    <td>年级</td>
                    <td>咨询师</td>
                    <td>试听课老师</td>
                    <td>签约时间</td>
                    <td>签约金额</td>
                    <td>是否转化</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>

                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["add_time"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["origin"]}} </td>
                        <td>{{@$var["phone_location"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["order_time"]}} </td>
                        <td>{{@$var["price"]/100}} </td>
                        <td>{{@$var["is_order"]}} </td>
                        <td> 
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!} >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

