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
                    <td>学生月留存</td>
                    @foreach($data as $val)
                        <td>{{$val["month_str"]}}</td>
                    @endforeach
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                <tr>
                    <td>存量学生数期初</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["begin_stock"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>本月新增学生数</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["increase_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>本月结课学生数</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["end_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>本月退费学生数</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["refund_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>存量学生数期末</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["end_stock"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>本月未排课学生数</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["no_lesson_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>本月在读期末数</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["end_read_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td></td>
                    @foreach ( $data as $k=>$var )
                        <td> </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>本月结课学生数</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["end_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>

              

                <tr>
                    <td>其中两三结课学生数（初三、高三）</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["three_end_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>本月到期续费学生数</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["expiration_renew_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>本月提前续费学生数</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["early_renew_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>本月结课续费学生数</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["end_renew_num"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>本月实际续费率</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["actual_renew_rate"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td>本月实际续费率（扣除两三学员）</td>
                    @foreach ( $data as $k=>$var )
                        <td>{{@$data[$k]["actual_renew_rate_three"]}} </td>                                               
                    @endforeach
                    <td>
                        
                    </td>

                </tr>



            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

