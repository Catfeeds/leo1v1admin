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
                            <td>会员等级</td>
                            <td id="id_agent_level_str"> </td>
                        </tr>

                        <tr>
                            <td>昵称</td>
                            <td id="id_usernick"> </td>
                        </tr>
                        <tr>
                            <td>
                                微信昵称
                            <td id="id_wx_nick"> </td>
                        <tr>
                            <td>
                                微信头像url
                            </td>

                            <td > <img
                                id="id_wx_headimgurl"
                                      src="" style="width:100px; "/>  </td>
                        </tr>

                        <tr>
                            <td>
                                星星个数
                            </td>
                            <td id="id_star_count"> </td>
                        </tr>


                    </tbody>

                </table>


                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td>项目 </td>
                            <td> 值</td>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>会员等级</td>
                            <td id="id_f_agent_level"> </td>
                        </tr>


                        <tr>
                            <td>昵称</td>
                            <td id="id_f_nick"> </td>
                        </tr>

                        <tr>
                            <td>
                                收入
                            </td>
                            <td id="id_f_pay"> </td>
                        </tr>


                        <tr>
                            <td>
                                可提现收入
                            </td>
                            <td id="id_f_cash"> </td>
                        </tr>

                        <tr>
                            <td>
                                已提现
                            </td>
                            <td id="id_f_have_cash"> </td>
                        </tr>


                        <tr>
                            <td>
                                成功邀请购课数
                            </td>
                            <td id="id_f_num"> </td>
                        </tr>

                        <tr>
                            <td>
                                我邀请的
                            </td>
                            <td id="id_f_my_num"> </td>
                        </tr>

                        <tr>
                            <td>
                                微信昵称
                            <td id="id_f_nickname"> </td>
                        <tr>
                            <td>
                                微信头像url
                            </td>

                            <td > <img
                                id="id_f_headimgurl"
                                      src="" style="width:100px; "/>  </td>
                        </tr>


                        <tr>
                            <td>
                                星星个数
                            </td>
                            <td id="id_f_count"> </td>
                        </tr>


                    </tbody>

                </table>

               <hr/>
               <div > 提现列表： 可提现  <span style="font-color:red;"  id="id_f_cash_2"> </span> </div>
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td>提现信息 </td>
                        </tr>
                    </thead>
                    <tbody id="id_cash_list">
                    </tbody>

                </table>
            </div>

            <div class="col-xs-12 col-md-4">
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td>一级列表</td>
                        </tr>
                    </thead>
                    <tbody id="id_level1_list">


                    </tbody>

                </table>
            </div>

            <div class="col-xs-12 col-md-4">
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td>二级列表</td>
                        </tr>
                    </thead>
                    <tbody id="id_level2_list">


                    </tbody>

                </table>
            </div>
        </div>
        </div>
    </section>
@endsection
