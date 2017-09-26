@extends('layouts.app')
@section('content')
      <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
      <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
      <script type="text/javascript" src="/js/qiniu/ui.js"></script>
      <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
      <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
      <script type="text/javascript" src="/js/jquery.md5.js"></script>
      <script type="text/javascript" src="/js/jquery.md5.js"></script>
      <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
      <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
      <script type="text/javascript" src="/js/qiniu/ui.js"></script>
      <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
      <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
      <script type="text/javascript" src="/page_js/lib/flow.js"></script>
      <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
      <script type="text/javascript" src="/page_js/lib/select_dlg_edit.js?v={{@$_publish_version}}"></script>
      <script type="text/javascript" src="/page_js/lib/select_date_time_range.js?v={{@$_publish_version}}"></script>

      <section class="content">
          <div class="row row-query-list">
              <div class="col-xs-12 col-md-4"  data-title="时间段">
                  <div  id="id_date_range" >
                  </div>
              </div>
              <div class="col-xs-6 col-md-2">
                  <div class="input-group ">
                      <span class="input-group-addon">类型</span>
                      <select class="opt-change form-control " id="id_contract_type" >
                          <option value="-2">正式1v1课程</option>
                      </select>
                  </div>
              </div>
              

              <div class="col-xs-6 col-md-2">
                  <div class="input-group ">
                      <span class="input-group-addon">主合同状态</span>
                      <select class="opt-change form-control" id="id_contract_status">
                          <option value="-2" >有效合同</option>
                          <option value="-1" >全部</option>
                          <option value="0" >未付款</option>
                          <option value="1" >执行中</option>
                          <option value="2" >已结束</option>
                          <option value="3" >提前终止</option>
                      </select>
                  </div>
              </div>
              <div class="col-xs-6 col-md-2">
                  <div class="input-group ">
                      <span class="input-group-addon">分期合同状态</span>
                      <select class="opt-change form-control" id="id_pay_status">                        
                          <option value="-1" >全部</option>
                          <option value="0" >未付款</option>
                          <option value="1" >已付款</option>
                      </select>
                  </div>
              </div>

          </div>
          <hr/>
          <table class="common-table">
              <thead>
                <tr>
                    <td style="display:none">userid</td>
                    <td style="display:none">主合同orderid</td>
                    <td >学员姓名</td>                   
                    <td >年级</td>
                    <td >科目</td>
                    <td style="display:none;" >联系电话</td>
                    <td >合同状态</td>
                    <td >合同类型</td>
                    <td style="display:none;">生效日期</td>
                    <td style="display:none;">下单日期</td>
                    <td >总课时</td>
                    <td >剩余课时</td>
                    <td style="display:none;">每次课课时数</td>
                    <td >主合同金额</td>
                    <td >分期金额</td>
                    <td >分期付款状态</td>
                    <td >分期期数</td>                 
                    <td >分期付款时间</td>                 
                    <td  style="display:none;">分期付款订单号</td>                 
                    <td >下单人</td>                  
                    <td style="display:none;">助教</td>
                 
                    <td >个人总课时</td>
                    <td class="remove-for-xs">操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
            <tr>
                        <td >{{$var["userid"]}}</td>
                        <td >{{$var["parent_orderid"]}}</td>
                      
                        <td class="stu_nick" >{{$var["nick"]}}</td>
                      
                        <td >{{$var["grade_str"]}}</td>
                        <td >{{@$var["subject_ex"]}}</td>
                        <td >{{$var["phone"]}}</td>
                        <td style="{{$var['status_color']}}" >{{$var["contract_status_str"]}}</td>
                        <td >
                            {{$var["contract_type_str"]}}
                        </td>
                        <td>{{$var["order_pay_time_str"]}}</td>
                        <td >{{$var["order_time_str"]}}</td>
                        <td >{{$var["lesson_total"]}}</td>
                        <td >{{$var["order_left"]}}</td>
                        <td >{{$var["default_lesson_count"]/100}}</td>
                        <td class="order_price">{{$var["order_price"]}}</td>
                        <td class="price">{{$var["price"]}}</td>
                        <td>{{$var["pay_status_str"]}}</td>
                        <td>{{$var["period_num"]}}</td>
                        <td>{{$var["pay_time_str"]}}</td>
                        <td>{{$var["from_orderno"]}}</td>
                       
                        <td >{{$var["sys_operator"]}}</td>
                        <td >{{$var["assistant_nick"]}}</td>
                        <td >{{$var["lesson_count_all"]/100}}</td>
                        <td >
                            <div class="btn-group"
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-user opt-user" title="个人信息" ></a>
                            </div>
                        </td>
            </tr>
                @endforeach
            </tbody>
          </table>
          @include("layouts.page")
          


          <script type="text/javascript" src="/page_js/select_course.js"></script>
          <script type="text/javascript" src="/page_js/select_user.js"></script>
          <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
@endsection
