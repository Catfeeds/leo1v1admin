@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script src='/page_js/select_teacher_free_time.js?{{@$_publish_version}}'></script>
    <script src='/page_js/set_lesson_time.js?{{@$_publish_version}}'></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>


    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div  id="id_date_range" >
                    </div>
                </div>
               

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">助教</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>                            
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">合同类型</span>
                        <select class="opt-change form-control" id="id_contract_type" >
                            <option value="-1">全部</option>
                            <option value="0">新签</option>
                            <option value="3">续费</option>
                        </select >
                    </div>
                </div>                            

               
                <div class="col-xs-6 col-md-7">
                    <div class="input-group ">
                        <button btn="btn-warning"   title="续费总金额" >{{ $all_money/100 }}</button>
                        <button btn="btn-warning"  title="续费签单金额" >{{ @$renew_list["order_money"]/100 }}</button>
                        <button btn="btn-warning"  title="续费退费金额" >{{ @$renew_list["refund_money"]/100 }}</button>
                        <button btn="btn-warning"   title="续费人数" >{{ @$renew_list["num"]/1 }}</button>
                        <button btn="btn-warning"   title="新签签单金额" >{{ @$new_list["order_money"]/100 }}</button>
                        <button btn="btn-warning"   title="新签退费金额" >{{ @$new_list["refund_money"]/100 }}</button>
                        <button btn="btn-warning"   title="新签人数" >{{ @$new_list["num"]/1 }}</button>
                    </div>
                </div>                            




            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>userid </td>
                    <td>学生名字 </td>
                    <td>orderid</td>
                    <td>下单时间 </td>                  
                    <td>合同金额 </td>                  
                    <td>退费申请时间 </td>                  
                    <td>退费金额 </td>                  
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["userid"]}} </td>
                        <td>{{@$var["stu_nick"]}} </td>                        
                        <td>{{@$var["orderid"]}} </td>                        
                        <td>{{@$var["order_time_str"]}} </td>                        
                        <td>{{@$var["price"]/100}} </td>                        
                        <td>{{@$var["apply_time_str"]}} </td>                        
                        <td>{{@$var["real_refund"]}} </td>                        

                       
                       

                        <td>
                            <div class="row-data"  
                                 
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >                               
                                <a class="fa-user opt-user " title="个人信息" ></a>
                                
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

