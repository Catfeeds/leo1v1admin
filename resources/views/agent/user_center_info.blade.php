@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">phone</span>
                        <input class="opt-change form-control" id="id_phone" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>

        <div id=""> </div>
            <div class="col-xs-12 col-md-4">

                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td>项目 </td>
                            <td> 值</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                微信头像url
                            </td>
                            <td > <img
                                      id="id_wx_headimgurl"
                                      src="" style="width:100px; "/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            微信昵称
                            <td id="id_wx_nick"> </td>
                        </tr>
                        <tr>
                            <td>会员等级</td>
                            <td id="id_agent_level_str"> </td>
                        </tr>
                        <tr>
                            <td>我的收入</td>
                            <td id="id_all_money_info"> </td>
                        </tr>
                        <tr>
                            <td>下线人数</td>
                            <td id="id_child_all_count"> </td>
                        </tr>

                    </tbody>

                </table>

                <table     class="common-table"  >
                    <tbody id="id_new_desc_list" >

                    </tbody>
                </table>

               <hr/>
               <div > 我的收入>>收入列表</div>
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td>项目 </td>
                            <td> 值</td>
                            <td> 操作</td>
                        </tr>
                    </thead>

                    <tbody>
                        
                        <tr>
                            <td>总收入金额</td>
                            <td id="id_all_money"></td>
                            <td><a id="id_all_invite">邀请奖励</a> <a id="id_all_commission">佣金奖励</a> <a id="id_all_activity">活动奖励</a></td>
                        </tr>
                        <tr>
                            <td>可提现</td>
                            <td id="id_open_money"></td>
                            <td><a id="id_cash_invite">邀请奖励</a> <a id="id_cash_commission">佣金奖励</a> <a id="id_cash_activity">活动奖励</a></td>
                        </tr>
                        <tr>
                            <td>已提现</td>
                            <td id="id_all_have_cash_money"></td>
                            <td><a id="id_have_cash_list">已提现列表</a></td>
                        </tr>
                        <tr>
                            <td>提现中</td>
                            <td id="id_is_cash_money"></td>
                        </tr>

                    </tbody>
                    
                </table>
            </div>

            <div class="col-xs-12 col-md-4">
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td id="id_title"></td>
                        </tr>
                    </thead>
                    <tbody id="id_detail_info">


                    </tbody>

                </table>
            </div>
            <div class="col-xs-12 col-md-4">
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td id="id_title_two"></td>
                        </tr>
                    </thead>
                    <tbody id="id_detail_info_two">


                    </tbody>

                </table>
            </div>


        </div>
        </div>
    </section>
@endsection
