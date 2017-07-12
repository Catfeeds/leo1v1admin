@extends('layouts.app')
@section('content')


    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-6">
                    <div id="id_date_range"> </div>
                </div>

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span >电话</span>
                        <input type="text" value=""   id="id_phone" class="opt-change"  placeholder="" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >学生</span>
                        <input type="text" value=""   id="id_userid" class="opt-change"  placeholder="" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师</span>
                        <input type="text" value=""   id="id_teacherid" class="opt-change"  placeholder="" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >销售</span>
                        <select    id="id_st_application_id"   class="opt-change" >
                            <option value="-1">[全部]</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >科目</span>
                        <select id="id_subject"   class="opt-change" > </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >状态</span>
                        <select id="id_test_lesson_status"   class="opt-change" >
                            <option value="-2">全部取消排课</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >记录状态</span>
                        <select id="id_del_flag"   class="opt-change" >
                            <option value="-1">全部</option>
                            <option value="0">有效</option>
                            <option value="1">多余记录</option>
                        </select>
                    </div>
                </div>


            </div>

        </div>
        
        <hr/>
        <table   class="common-table"   > 
            <thead>
                <tr>
                    <td>序号</td>
                    <td>记录时间 </td>
                    <td style="display:none;">userid</td>
                    <td>电话</td>
                    <td>学生</td>
                    <td>老师</td>
                    <td>科目</td>
                    <td>预期上课时间</td>
                    <td>上课时间</td>
                    <td>销售</td>
                    <td>排课人</td>
                    <td>课程状态</td>
                    <td>说明</td>
                    <td>记录状态</td>
                    <td> 操作  </td> </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["index"]}} </td>
                        <td>  {{$var["log_time"]}} </td>
                        <td>{{$var["userid"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td>{{$var["student_nick"]}} </td>
                        <td>{{$var["teacher_nick"]}} </td>
                        <td>{{$var["subject_str"]}} </td>
                        <td>{{$var["st_class_time"]}} </td>
                        <td>{{$var["lesson_time"]}} </td>
                        <td>{{$var["st_application_nick"]}} </td>
                        <td>{{$var["test_lesson_bind_admin_nick"]}} </td>
                        <td>{{$var["test_lesson_status_str"]}} </td>
                        <td>{{$var["reason"]}} </td>
                        <td>{!!  $var["del_flag"]==0?"有效":"<font color=red>多余</font>"!!} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a  class="opt-change-test_lesson_status"  title="修改状态">状态   </a>
                                <a  class="opt-show-user-opt-list"  title="查看">用户历史记录</a>
                                <a href="javascript:;" title="查看回访" class=" fa-comments opt-return-back-list  "></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")

    </section>
    
@endsection

