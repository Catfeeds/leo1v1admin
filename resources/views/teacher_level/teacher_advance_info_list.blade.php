@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <div>
            <div class="row" >                              
               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">老师类型</span>
                        <select class="opt-change form-control " id="id_fulltime_flag" >
                            <option value="-1">全部</option>
                            <option value="0">兼职老师</option>
                            <option value="1">上海全职老师</option>
                            <option value="2">武汉全职老师</option>
                        </select>
                    </div>
                </div>               

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">工资类型</span>
                        <select class="opt-change form-control " id="id_teacher_money_type" >
                        </select>
                    </div>
                </div>               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">结果</span>
                        <select class="opt-change form-control " id="id_accept_flag" >
                            <option value="-1">全部</option>
                            <option value="0">未审核</option>
                            <option value="1">通过</option>
                            <option value="2">驳回</option>
                        </select>
                    </div>
                </div>               




            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>老师</td>
                    <td>当前等级</td>
                   <td>入职时间</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["level_before_str"]}} </td>
                        <td>{{@$var["become_member_time_str"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <!-- @if(empty($var["accept_time"]) || $var["teacherid"]==50158 || $var["accept_flag"]==2)
                                     <a class="opt-accept" >同意</a>
                                     <a class="opt-no-accept" >驳回</a>
                                     @endif
                                   -->
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

