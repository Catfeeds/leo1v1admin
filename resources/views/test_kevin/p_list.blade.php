@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4" >
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">老师</span>
                        <input class="opt-change form-control" type="text" id="id_teacherid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">反馈状态</span>
                        <select class="opt-change form-control" id="id_status" >
                            <option value="-1">[全部]</option>
                            <option value="0">未审核</option>
                            <option value="1">已通过</option>
                            <option value="2">未通过</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">反馈类型</span>
                        <select class="opt-change form-control" id="id_feedback_type">
                            <option value="-2">非扣款项</option>
                        </select>
                    </div>
                </div>
                <div class=" col-xs-6  col-md-2">
                    <div class="input-group col-sm-12">
                       <input type="text" class="form-control for_input" id="id_lesson" placeholder="请输入课程ID 回车查找" />
                       <div class="input-group-btn">
                           <button id="id_search_lesson" type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                       </div> 
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>姓名</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>
                            <div
                                {!! \App\Helper\Utils::gen_jquery_data($var)  !!}
                            >
                                <a class="opt-edit" title="审核">审核</a>
                                <a class="opt-log-list" title="登录日志">登陆日志</a>
                                <a class="opt-lesson_info">课堂详情</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")

        <div style="display:none;" >
            <div id="id_lesson_log"  >
                <div class="row">
                    <div class="col-xs-6 col-md-3">
                        <div class="input-group ">
                            <select class="opt-userid form-control" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="input-group ">
                            <select class="opt-server-type form-control"  >
                                <option value="-1" > 不限 </option>  
                                <option value="1" > webrtc</option>  
                                <option value="2" > xmpp</option>  
                            </select>
                        </div>
                    </div>
                </div>
                
                <hr/>
                <table   class="table table-bordered "   >
                    <tr>  <th> 时间 <th>角色 <th>用户id <th>服务 <th> 进出 <th> ip </tr>
                        <tbody class="data-body">
                        </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
