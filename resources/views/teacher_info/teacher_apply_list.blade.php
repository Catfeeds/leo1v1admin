@extends('layouts.teacher_header')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <!-- <div class="col-xs-6 col-md-2">
                     <div class="input-group " >
                     <span >xx</span>
                     <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                     </div>
                     </div> -->
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>课程名称</td>
                    <td>问题类型</td>
                    <td>问题描述</td>
                    <td>课程联系人</td>
                    <td>联系人处理反馈状态</td>
                    <td>讲师处理反馈状态</td>
                    <td>申请时间</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["lesson_name"]}} </td>
                        <td>
                            @if($var["question_type"] == 1)
                               试听需求 
                            @elseif($var["question_type"] == 2)
                                试卷
                            @elseif($var["question_type"] == 3)
                                上课时间调整
                            @elseif($var["question_type"] == 4)
                                是否转化
                            @elseif($var["question_type"] == 5)
                                是否重上
                            @elseif($var["question_type"] == 6)
                                其他
                            @else
                            @endif
                        </td>
                        <td>{{@$var["question_content"]}} </td>
                        <td>{{@$var["ass_nick"]}} </td>
                        <td>
                            @if($var["cc_flag"] == 1)
                                <span style="color:green;">已处理<span/>
                            @else
                                <span style="color:red;">待处理<span/>
                            @endif
                        </td>
                        <td>
                            @if($var["teacher_flag"] == 1)
                                <span style="color:green;">已处理<span/>
                            @else
                                <span style="color:red;">待处理<span/>
                            @endif
                        </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

