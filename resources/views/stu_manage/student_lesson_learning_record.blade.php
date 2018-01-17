@extends('layouts.stu_header')
@section('content')
    <section class="content ">
        <table border="1" bordercolor="#d5d5d5" cellspacing="0" width="100%" height="30px" style="border-collapse:collapse;margin-top:20px"  class="stu_tab04" >
            <tr align="center">
                <td class="current" width="20%" data-id="1"><a href="javascript:;" style="color:#000" >课前预习</a></td>
                <td width="20%" data-id="2"><a href="javascript:;" style="color:#000" >课堂情况</a></td>
                <td width="20%" data-id="3"><a href="javascript:;" style="color:#000">课程评价</a></td>
                <td width="20%" data-id="4"><a href="javascript:;" style="color:#000">作业情况</a></td>
                <td width="20%" data-id="5"><a href="javascript:;" style="color:#000">平日成绩</a></td>
            </tr>
        </table>

        <div class="row" style="margin-top:10px">
            <div class="col-xs-12 col-md-4">
                <div  id="id_date_range" >
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">年级</span>
                    <select id="id_grade" class="opt-change">                      
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">科目</span>
                    <select id="id_subject" class="opt-change">                      
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-1">
                <button class="btn btn-primary" id="id_search" >搜索</button>
            </div>
            <div class="col-xs-6 col-md-2" style="display:none;">
                <button class="btn btn-warning" id="id_add_stu_score" >添加考试成绩</button>
            </div>
            <div class="col-xs-6 col-md-12" >
                <button class="btn " id="id_grade_show" ></button>
                <button class="btn " id="id_subject_show" ></button>
            </div>
            



        </div>
        <hr/>
        <div class="row">
            <div class="col-xs-6 col-md-12 ">
                <button class="btn btn-warning btn-flat" id="id_pre_rate" style="float:right">预习率:{{ @$pre_rate }}%</button>
            </div>
        </div>
        <table class="common-table">
            <thead>
                <tr>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td>
                            <div

                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-list-alt opt-hand-over btn" title="新建交接单"></a>
                                <a class="btn fa fa-th-list opt-hand-over_info" title="交接单详情"></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection
