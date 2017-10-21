@extends('layouts.agent_header')
@section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<style>
 textarea{
     resize:both;
 }
 table {
     font-size :14px;
 }
 .false{
     color:red;
 }
 .bg_train_lesson{
     background-color:#ccc;
 }
</style>
<section class="content li-section">

    <div class="row">

        <div class="col-xs-12 col-md-4">
            <div class="input-group "  >
                <span >日期</span>
                <input type="text" id="id_start_date" class="opt-change form-control input-group-addon  "/>
                <span >-</span>
                <input type="text" id="id_end_date" class="opt-change form-control input-group-addon  "/>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">课程类型</span>
                <select class="opt-change form-control" id="id_lesson_type" >
                    <option value="-1">[全部]</option>
                    <option value="0">1对1</option>
                    <option value="2">试听课 </option>
                    <option value="1001">公开课 </option>
                    <option value="3001">小班课</option>
                    <option value="1100">培训课程</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-md-2">
            <div class="input-group">
                <span >学生</span>
                <select id="id_student" class="opt-change">
                    <option value="-1">[全部]</option>
                    @foreach($student_list as $val)
                        <option value="{{$val['userid']}}">{{$val['nick']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td style="min-width:100px">团队名称</td>
                <td style="min-width:100px">人数</td>
                <td style="min-width:100px">建立时间</td>
                <td style="min-width:100px">团长信息</td>
                <td style="min-width:300px">管理</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{@$table_data_list["group_name"]}}<br/>
                    {{@$table_data_list["member_num"]}}<br/>
                    {{@$table_data_list["create_time"]}}<br/>
                    {{@$table_data_list["colonel_info"]}}<br/>
                </td>
                
            </tr>
        </tbody>
    </table>
    
</section>

@endsection

<div class="row">
    <div class="col-xs-6 col-md-2">
    </div>
</div>
