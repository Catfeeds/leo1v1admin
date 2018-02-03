@extends('layouts.app')
@section('content')
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
              <div class="col-xs-12 col-md-5"  data-title="时间段">
                  <div  id="id_date_range" >
                  </div>
              </div>
              <div class="col-xs-6 col-md-2">
                  <div class="input-group ">
                      <span class="input-group-addon">订单号</span>
                      <input class="opt-change form-control" id="id_orderid" />
                  </div>
              </div>
              <div class="col-xs-6 col-md-2">
                  <div class="input-group ">
                      <span class="input-group-addon">下单人</span>
                      <input class="opt-change form-control" id="id_adminid" />
                  </div>
              </div>
              <div class="col-xs-6 col-md-2">
                  <div class="input-group ">
                      <span class="input-group-addon">类型</span>
                      <input class="opt-change form-control " id="id_contract_type" /> 
                  </div>
              </div>
              <div class="col-xs-6 col-md-2">
                  <div class="input-group ">
                      <span class="input-group-addon">详细分类</span>
                      <select class="opt-change form-control " id="id_stu_from_type" >
                      </select>
                  </div>
              </div>


        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">特殊申请</span>
                <select class="opt-change form-control" id="id_spec_flag" >
                </select>
            </div>
        </div>
              <div class="col-xs-6 col-md-2">
                  <div class="input-group ">
                      <span class="input-group-addon">测试学员</span>
                      <select class="opt-change form-control " id="id_test_user" >
                      </select>
                  </div>
              </div>
              <div class="col-xs-6 col-md-2">
                  <div class="input-group ">
                      <span class="input-group-addon">转介绍</span>
                      <input class="opt-change form-control" id="id_origin_userid" />
                  </div>
              </div>
              <div  class="col-xs-6 col-md-4">
                  <div class="input-group ">
                      <span class="input-group-addon">申请人选择</span>
                      <input class="opt-change form-control" id="id_seller_groupid_ex" />
                  </div>
              </div>
              <div class="col-xs-6 col-md-2">
                  <div class="input-group ">
                      <span >学生</span>
                      <input id="id_studentid"  />
                  </div>
              </div>
              <div class="col-xs-6 col-md-2">
                  <div class="input-group ">
                      <span class="input-group-addon">转介绍_组员</span>
                      <input class="opt-change form-control" id="id_referral_adminid" />
                  </div>
              </div>
              <div class="col-xs-6 col-md-2">
                  <div class="input-group ">
                      <span class="input-group-addon">助教</span>
                      <input class="opt-change form-control" id="id_assistantid" />
                  </div>
              </div>


            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">状态</span>
                    <input class="opt-change form-control" id="id_contract_status">  </input>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">是否有金额</span>
                    <select class="opt-change form-control" id="id_has_money">
                        <option value="-1" >全部</option>
                        <option value="0" >无</option>
                        <option value="1" >有</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">角色</span>

                    <select class="opt-change form-control" id="id_account_role">
                    </select>

                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">年级</span>
                    <input class="opt-change form-control" id="id_grade" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">科目</span>
                    <input class="opt-change form-control" id="id_subject" />
                </div>
            </div>

            <div class="col-xs-6 col-md-2" data-always_hide="1">
                <div class="input-group ">
                    <input id="id_sys_operator"  class="opt-change" placeholder="下单人,回车搜索" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">TMK</span>
                    <input class="opt-change form-control" id="id_tmk_adminid" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">老师</span>
                    <input class="opt-change form-control" id="id_teacherid" />
                </div>
            </div>

            <div class="col-xs-6 col-md-3" >
                <div class="input-group ">
                    <span class="input-group-addon">活动</span>
                    <input class="opt-change form-control" id="id_order_activity_type" />
                </div>
            </div>
            <div class="col-xs-4 col-md-3">
                <div class="input-group ">
                    <div class=" input-group-btn ">
                        <button id="id_add_contract" type="submit"  class="btn  btn-warning" >
                            <i class="fa fa-plus"></i>旧版
                        </button>
                        <button id="id_add_seller_contract" type="submit"  class="btn  btn-warning">
                            <i class="fa fa-plus"></i>合同
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td style="display:none">userid</td>
                    <td style="display:none">orderid</td>
                    <td class="td-origin" >渠道</td>

                    {!!\App\Helper\Utils::th_order_gen([["学生", "", "" ]])!!}
                    <td style="display:none;" >家长姓名</td>
                    <td style="display:none;" >地区</td>
                    <td style="display:none;" >1v1详细分类</td>
                    <td style="display:none;">是否新增 </td>
                    <td >试听课时间</td>
                    {!!\App\Helper\Utils::th_order_gen([["年级", "grade", "" ]])!!}
                    {!!\App\Helper\Utils::th_order_gen([["科目", "", "" ]])!!}
                    {!!\App\Helper\Utils::th_order_gen([["合同状态", "contract_status", "" ]])!!}
                    {!!\App\Helper\Utils::th_order_gen([["合同类型", "contract_type", "" ]])!!}
                    <td style="display:none;">生效日期</td>
                    <td style="display:none;">下单日期</td>
                    <td >总课时</td>
                    <td >剩余课时</td>
                    <td style="display:none;">每次课课时数</td>
                    <td >实付金额</td>
                    <td >定金</td>
                    <td >原始金额</td>
                    <td >实付/原始单价</td>
                    <td style="display:none;" >优惠原因</td>
                    <td style="display:none;" >包类型</td>
                    {!!\App\Helper\Utils::th_order_gen([["下单人", "", "" ]])!!}
                    <td style="display:none;">淘宝订单号</td>
                    <td style="display:none;">courseid</td>
                    <td style="display:none;">助教</td>
                    <td style="display:none;" >竞赛合同</td>
                    <td style="display:none;" >设备信息</td>
                    <td >TMK负责人</td>
                    <td >试听课老师</td>
                    <td >财务说明</td>
                    {!!\App\Helper\Utils::th_order_gen([["特殊折扣申请状态", "", "" ]])!!}
                    <td >发放礼拜时间</td>
                    <td >个人总课时</td>
                    <td >是否分期</td>
                    <td >家长查看状态</td>
                    <td >查看时间</td>
                    <td class="remove-for-xs">操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td >{{$var["userid"]}}</td>
                        <td >{{$var["orderid"]}}</td>
                        <td class="td-origin" >{{$var["origin"]}}<br> {{$var["origin_assistant_nick"]}} </td>
                        <td class="stu_nick" >
                            <a href="javascript:;" class="show_phone" data-phone="{{$var["phone"]}}" >
                                {{@$var["phone_hide"]}}
                            </a>
                            <br/>
                            {{$var["stu_nick"]}}
                        </td>
                        <td >{{$var["parent_nick"]}}</td>
                        <td >{{substr($var["phone_location"],0,-6)}}</td>
                        <td >{{$var["stu_from_type_str"]}}</td>
                        <td >{{$var["is_new_stu_str"]}}</td>
                        <td >{{$var["lesson_start"]}}-{{$var["lesson_end"]}}</td>
                        <td >{{$var["grade_str"]}}</td>
                        <td >{{$var["subject_str"]}}</td>
                        <td style="{{$var['status_color']}}" >{{$var["contract_status_str"]}}</td>
                        <td >
                            @if ($var["contract_type"]==1)
                                {{$var["from_parent_order_type_str"]}}
                            @else
                                {{$var["contract_type_str"]}}
                            @endif
                        </td>
                        <td class="contract_starttime">{{$var["contract_starttime"]}}</td>
                        <td >{{$var["order_time"]}}</td>
                        <td >{{$var["lesson_total"]}}</td>
                        <td >{{$var["order_left"]}}</td>
                        <td >{{$var["default_lesson_count"]/100}}</td>
                        <td class="price">{{$var["price"]}}</td>
                        <td >{{$var["pre_money_info"]}}</td>
                        <td >{{$var["discount_price"]}}</td>
                        <td >
                            {{ $var["lesson_total"] ? intval($var["price"]/$var["lesson_total"]):"-" }}/
                            {{ $var["lesson_total"] ? @intval(@$var["discount_price"]/@$var["lesson_total"]):"-" }}
                        </td>
                        <td >{{$var["discount_reason"]}}</td>
                        <td >{{$var["from_type_str"]}}</td>
                        <td >{{$var["sys_operator"]}}</td>
                        <td >{{$var["taobao_orderid"]}}</td>
                        <td >{{$var["config_courseid"]}}</td>
                        <td >{{$var["assistant_nick"]}}</td>
                        <td >{{$var["competition_flag_str"]}}</td>
                        <td >{{$var["user_agent"]}}</td>
                        <td >{{$var["tmk_admin_nick"]}}</td>
                        <td >{{$var["teacher_nick"]}}</td>
                        <td >{{$var["check_money_desc"]}}</td>
                        <td>{!!$var["flow_status_str"]!!} <br/>
                            <br/>价值: {!!$var["promotion_spec_diff_money"]!!}
                            <br/>{!!$var["promotion_spec_is_not_spec_flag_str"]!!}
                        </td>
                        <td >{{$var["get_packge_time"]}}</td>
                        <td >{{$var["lesson_count_all"]/100}}</td>
                        <td >{!! $var["is_staged_flag_str"] !!}</td>
                        <td >{!! $var["hasCheck"] !!}</td>
                        <td >{{ $var["first_check_time_str"] }}</td>

                        <td >
                            <div class="btn-group"
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-user opt-user" title="个人信息" ></a>
                                <a style="display:none;" class="fa-edit opt-change-state" title="更改付款状态"> </a>
                                <a class="fa-trash-o opt-del" title="删除合同"> </a>
                                <a class=" fa-unlock  opt-is-not-spec-flag " title="设置为正常订单,非特殊"> </a>
                                @if(in_array($_account_role,[12,13]))
                                    <a class="fa-clock-o opt-change-default_lesson_count" title="修改课时数"> </a>
                                    <a class="fa-cny opt-change-money" title="更改金额"> </a>
                                @endif
                                <a class="fa-arrow-right  opt-next " title="课时包详情"> </a>
                                <a class="fa-link opt-relation-order" title="关联的合同信息"> </a>
                                <a class="opt-set-origin" title="设置对应的试听课">试听</a>
                                <a class="fa opt-flow-node-list fa-facebook" title="审核进度"></a>
                                <a class="fa opt-get_package_time fa-gift" title="发放礼包时间"></a>
                                <a class="opt-from-data" title="外部订单信息">外</a>
                                <a class="opt-build-contrat" title="合同pdf">pdf</a>
                                <a class="opt-mail-contrat" title="合同运单">运单</a>
                                <a class="opt-merge_order" title="合并合同">合并</a>
                                <a class="opt-price_desc fa-list" title="价格生成说明"></a>
                                @if(in_array($_account,[$var["sys_operator"],"jim","jack"])  && $var["price"] >0)
                                    <a class="opt-order-partition" title="拆分合同">拆分</a>
                                    <a class="opt-update-parent-name" title="修改家长姓名">家</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="input-group ">
                    <span >总课时数:{{$all_lesson_count}}</span>
                    <span >特殊申请总价值:{{$all_promotion_spec_diff_money}}</span>
                </div>
            </div>
        </div>
        <div style="display:none;" id="id_dlg_change_state">
            <div class="mesg_alertCont">
              <table border="0" cellspacing="0" width="100%" style="border-collapse:collapse;" class="stu_tab02">
                    <tr>
                        <td>合同编号：</td>
                        <td class="align_l orderid"></td>
                    </tr>
                    <tr>
                        <td>合同状态：</td>
                        <td class="align_l contract_status">未付款</td>
                    </tr>
                    <tr>
                        <td width="30%">付款方式：</td>
                        <td width="70%" class="align_l">
                            <select id="id_pay_channel"><option value="2">银行转账</option></select>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">付款账号：</td>
                        <td width="70%" class="align_l"><input type="text" id="id_pay_number" class="put_why"></td>
                    </tr>
                    <tr>
                        <td>金额</td>
                        <td class="align_l money"></td>
                    </tr>

                </table>

            </div>
        </div>




        <div style =" display:none; " id="id_dlg_back_money">
            <table   class="table table-bordered table-striped"   >
                <tr>
                    <td>合同编号：</td>
                    <td colspan="3" class="underline" id="id_conid2"></td>
                </tr>
                <tr>
                    <td width="25%">学员姓名：</td>
                    <td width="25%" class="underline" id="id_stu_name2"></td>
                    <td width="25">年级：</td>
                    <td width="25%" class="underline" id="id_grade2"></td>
                </tr>
                <tr>
                    <td>家长姓名：</td>
                    <td class="underline" id="id_parent_name2"></td>
                    <td>电话：</td>
                    <td class="underline" id="id_parent_phone2"></td>
                </tr>
                <tr>
                    <td>购买课次：</td>
                    <td class="underline" id="id_lesson_total2"></td>
                    <td>剩余课次：</td>
                    <td class="underline" id="id_lesson_reduce2"></td>
                </tr>
                <tr>
                    <td>是否开具发票：</td>
                    <td colspan="3" class="underline" id="id_need_receipt2" ></td>
                </tr>
                <tr>
                    <td>支付金额：</td>
                    <td class="underline" id="id_should_refund_value"></td>
                    <td>实退金额：</td>
                    <td class="underline" class="price_change"><input id="id_refund" type="text" class="input_mony form-control" /></td>
                </tr>
                <tr>
                    <td>退费理由：</td>
                    <td colspan="3"><input type="text" class="put_why form-control" id="id_refund_reson"/></td>
                </tr>
            </table>
        </div>

        <div style="display:none" id="id_dlg_query_user">
            <div class="row">
                <div class="col-xs-12  col-md-8 ">
                    <div class="input-group ">
                        <span class="input-group-addon">注册账号：</span>
                        <input type="text" class=" form-control "  id="id_query_phone"  />
                        <div class=" input-group-btn ">
                            <button id="id_query_student" type="submit"  class="btn btn-primary  ">
                                <i class="fa fa-search"> </i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <ul id="id_add_contrat_user_info" style="text-align:left;">
                <li>账号：<span id="id_user_acc">    </span></li>
                <li>年级：<span id="id_user_grade">     </span></li>
                <li>地区：<span id="id_user_region">    </span></li>
                <li>教材：<span id="id_user_textbook">  </span></li>
            </ul>
        </div>
        </div>
    </section>

    <div style="display:none;" id="id_dlg_add_contract">
        <div class="row">
            <div class="col-xs-12 col-md-6  ">
                <div class="input-group ">
                    <span class="input-group-addon">学员姓名：</span>
                    <input type="text" class=" form-control "  id="id_user_nick"  />
                </div>
            </div>
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group ">
                    <span class="input-group-addon">家长姓名：</span>
                    <input type="text" class=" form-control "  id="id_parent_nick"  />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="input-group ">
                    <span class="input-group-addon">联系电话：</span>
                    <input type="text" id="id_contact_phone"   class=" form-control "  />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 ">
                <div class="input-group ">
                    <span class="input-group-addon">家庭住址：</span>
                    <input type="text" id="id_user_addr"  class="form-control" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group ">
                    <span class="input-group-addon">学生年级：</span>
                    <select id="id_stu_grade" class=" form-control "   >
                        <option value="101">小一</option>
                        <option value="102">小二</option>
                        <option value="103">小三</option>
                        <option value="104">小四</option>
                        <option value="105">小五</option>
                        <option value="106">小六</option>
                        <option value="201">初一</option>
                        <option value="202">初二</option>
                        <option value="203">初三</option>
                        <option value="301">高一</option>
                        <option value="302">高二</option>
                        <option value="303">高三</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group ">
                    <span class="input-group-addon">所选科目：</span>
                    <select id="id_stu_subject" class=" form-control "   >
                        <!-- 科目 0未设定 1语文 2数学 3英语 4化学 5物理 6生物 7政治 8历史 9地理 -->
                        <option value="-1">[请选择]</option>
                        <option value="1">语文</option>
                        <option value="2">数学</option>
                        <option value="3">英语</option>
                        <option value="4">化学</option>
                        <option value="5">物理</option>
                        <option value="6">生物</option>
                        <option value="7">政治</option>
                        <option value="8">历史</option>
                        <option value="9">地理</option>
                        <option value="10">科学</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group  ">
                    <span class="input-group-addon">合同类型：</span>
                    <select id="id_con_type" class=" form-control "  >
                        <option value="-1">[请选择]</option>
                        <option value="3">续费</option>
                        <option value="3001">小班课</option>
                        <option value="1001">公开课</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group opt-con-type-div  count_block " style="display:none;">
                    <span class="input-group-addon">购买课次：</span>
                    <input type="text"  id="id_lesson_count"  class=" form-control " />
                </div>
                <div class="input-group opt-con-type-div small-class-div" style="display:none;">
                    <span class="input-group-addon">小班课:</span>
                    <input type="text"  id="id_small_class" class=" form-control " />
                </div>
                <div class="input-group opt-con-type-div open-class-div" style="display:none;">
                    <span class="input-group-addon">公开课:</span>
                    <input type="text"  id="id_open_class" class=" form-control " />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group  ">
                    <span class="input-group-addon">竞赛合同：</span>
                    <select id="id_competition_flag" class="form-control">
                        <option value="0">否</option>
                        <option value="1">是</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group bind_order" style="display:none">
                    <span class="input-group-addon">绑定合同：</span>
                    <input type="text"  id="id_bind_order" class=" form-control " />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group  ">
                    <span class="input-group-addon">渠道方式：</span>
                    <select id="id_select_origin" class=" form-control "  >
                        <option value="0">选择已有渠道</option>
                        <option value="1">手动输入</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group origin_select" >
                    <span class="input-group-addon">渠道选择：</span>
                    <input type="text" id="id_origin_select"  class=" form-control " />
                </div>
                <div class="input-group origin_input" style="display:none;">
                    <span class="input-group-addon">渠道选择：</span>
                    <input type="text" id="id_origin_input"  class=" form-control " />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group ">
                    <span class="input-group-addon">开具发票：</span>
                    <select id="id_need_receipt" class="form-control" >
                        <option value="0">否</option>
                        <option value="1">是</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 ">
                <div class="input-group ">
                    <span class="input-group-addon">抬头：</span>
                    <input type="text" id="id_receipt_title"  class="form-control"  />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 ">
                <div class="input-group ">
                    <span class="input-group-addon">排课要求：</span>
                    <input type="text" id="id_lesson_requirement"  class="form-control"  />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 ">
                <div class="input-group ">
                    <span class="input-group-addon">淘宝订单号：</span>
                    <input type="text" class="taobao_orderid"  class="form-control"  />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 ">
                <div class="input-group test-listen" style="display:none;">
                    <span class="input-group-addon">是否退款：</span>
                    <select id="id_should_refund" class="form-control" >
                        <option value="0">不退款</option>
                        <option value="1">退款</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 ">
                <div class="input-group test-listen" style="display:none;">
                    <span class="input-group-addon">赠送原因：</span>
                    <textarea id="id_presented_reason" class="form-control" >
                    </textarea>
                </div>
            </div>
        </div>
    </div>
    <div style="display:none;" id="id_dlg_add_contract_new">
        <div class="row">
            <div class="col-xs-12 col-md-6  ">
                <div class="input-group ">
                    <span class="input-group-addon">学员姓名：</span>
                    <input type="text" class=" form-control field-nick "      />
                </div>
            </div>
            <div class="col-xs-12 col-md-6  ">
                <div class="input-group ">
                    <span class="input-group-addon">联系电话：</span>
                    <input type="text"   class=" form-control  field-phone"   />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group ">
                    <span class="input-group-addon">学生年级：</span>
                    <select  class=" form-control field-grade  "    >
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group ">
                    <span class="input-group-addon">所选科目：</span>
                    <select  class=" form-control field-subject "    >
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group  ">
                    <span class="input-group-addon">奥赛合同：</span>
                    <select  class="form-control field-competition_flag">
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-md-6 " >
                <div class="input-group  ">
                    <span class="input-group-addon" >适用促销：</span>
                    <select  class="form-control field-order_promotion_type">
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group  ">
                    <span class="input-group-addon">　总课时：</span>
                    <input class="form-control field-lesson_count"/>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group  ">
                    <span class="input-group-addon" style="color:blue;">启用分期：</span>
                    <select  class="form-control field-period_flag">
                        <option value="0" >否</option>
                        <option value="1" >是</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group  ">
                    <span class="input-group-addon">　　原价：</span>
                    <input class="form-control field-discount_price"/>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group  ">
                    <span class="input-group-addon">优惠说明：</span>
                    <input class="form-control field-order_promotion_desc"/>
                </div>
            </div>
        </div>
        <div class="row" >
            <div class="col-xs-12 col-md-12   field-order_desc_list ">
            </div>
        </div>
        <div class="row" >
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group  ">
                    <span class="input-group-addon" style="color:red;">特殊申请(或未用活动)：</span>
                    <select  class="form-control field-order_require_flag">
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group  ">
                    <span class="input-group-addon" style="color:red;">是否分享：</span>
                    <select  class="form-control field-has_share_activity">
                    </select>
                </div>
            </div>
        </div>
        <div class="row div-spec" >
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group  ">
                    <span class="input-group-addon" style="color:red;">特殊赠送：</span>
                    <input class="form-control field-promotion_spec_present_lesson" placeholder="请输入课时数"   />
                </div>
            </div>
            <div class="col-xs-12 col-md-6 ">
                <div class="input-group  ">
                    <span class="input-group-addon" style="color:red;">特殊折扣：</span>
                    <input class="form-control field-promotion_spec_discount_price"   placeholder="请输入金额" />
                </div>
            </div>
        </div>
        <div class="row div-spec" >
            <div class="col-xs-12 col-md-12 ">
                <div class="input-group  ">
                    <span class="input-group-addon" style="color:red;">申请原因：</span>
                    <input class="form-control field-discount_reason" placeholder=""   />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12 ">
                <div class="input-group ">
                    <span class="input-group-addon">发票抬头：</span>
                    <input type="text"  class="form-control field-receipt_title"  />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6 " style="display:none;">
                <div class="input-group ">
                    <span class="input-group-addon">是否新签：</span>
                    <select  class="form-control field-is_new_stu">
                        <option value="0" >否</option>
                        <option value="1" >是</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
@endsection
