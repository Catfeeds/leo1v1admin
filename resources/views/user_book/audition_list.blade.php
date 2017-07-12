@extends('layouts.app')

@section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/js/svg.js"></script>
<script type="text/javascript" src="/js/wb-reply/audio.js"></script>

<link href="/css/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css" />

<section class="content">
    
    <div class="row">

        <div class="col-xs-12 col-md-4">
            <div class="input-group ">
                <span >日期</span>
                <input type="text" id="id_start_date" class="opt-change"/>
                <span >-</span>
                <input type="text" id="id_end_date" class="opt-change"/>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">类型</span>
                <select class="opt-change form-control " id="id_lesson_type" >
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">学科</span>
                    <select class="opt-change form-control " id="id_subject" >
                    </select>
                </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span >老师</span>
                <input id="id_teacherid" class="opt-change"  /> 
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span> 测试用户 </span>
                <select id="id_is_with_test_user" class="opt-change" >
                    <option value="0" >不显示</li>
                    <option value="1" >显示</li>
                </select>
            </div>
        </div>

        <!-- 课程查找 search lesson by lessonid -->
        <div class="col-md-2">
            <div class="input-group col-sm-12">
                <input type="text" value="" class="form-control put_name for_input"  id="id_lesson" placeholder="请输入课程ID 回车查找" />
                <div class=" input-group-btn ">
                    <button id="id_search_lesson" type="submit"  class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div> 
            </div>
        </div>
        <!-- search lesson end -->
        
    </div>
    <hr/>

    <table class="common-table">
        <thead>
            <tr>
                <td class="remove-for-xs">id</td>
                <td class="remove-for-xs" style="min-width:200px" >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
		            @include("layouts.td_xs_opt")
                    <td class="remove-for-xs">
                        <div>
                            <a href="javascript:;" class="btn fa fa-play opt-play " title="回放"  ></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
@endsection
