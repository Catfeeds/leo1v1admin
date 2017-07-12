@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" >
    </script>

    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >科目</span>
                        <select id="id_subject" class ="opt-change" ></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >面试老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacher_account"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >老师分类</span>
                        <select id="id_identity" class ="opt-change" ></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >渠道</span>
                        <input id="id_reference_teacherid">
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    2017-1-5日之后数据准确,之前不看.
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>渠道  </td>
                    <td>姓名  </td>
                                  
                    {!!\App\Helper\Utils::th_order_gen([                        
                        ["提交视频数","all_count" ],                     
                        ["入职人数","suc_count" ],
                        ["通过率","pass_per"],
                        ["平均审核时长(天)","ave_time"],
                        ["试听成功数","all_lesson"],
                        ["签单数","have_order"],
                        ["签单率","order_per"]
                       ])  !!}
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["reference"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td >{{@$var["all_count"]}} </td>
                        <td >{{@$var["suc_count"]}} </td>
                        <td >{{@$var["pass_per"]}}% </td>                        
                        <td >{{@$var["ave_time"]}}</td>                        
                        <td >{{@$var["all_lesson"]}}</td>                        
                        <td >{{@$var["have_order"]}}</td>                        
                        <td >{{@$var["order_per"]}}%</td>                        

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


