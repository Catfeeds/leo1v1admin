@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<script type="text/javascript" src="/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/source/jquery.fancybox.css" media="screen" />
<script type="text/javascript" src="/page_js/select_user.js"></script>
<style>
 .dnone{
     display:none;
 }
</style>
   <div class="row">
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">类型</span>
                <select class="stu_sel form-control" id="id_type" >
                </select>
            </div>
        </div>
             <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <button id="opt-add-new-course"  class="btn btn-warning form-control">新增课程包</button>
            </div>
        </div>
    </div>
    <hr/>
    <table class="table table-bordered table-striped"   >
        <thead>
            <tr>
                <td >id</td>
                <td >名称</td>
                <td >科目</td>
                <td class="dnone">年级</td>
                <td class="dnone">价格</td>
                <td >类型</td>
                <td >标签</td>
                <td class="dnone">推荐标签</td>
                <td class="dnone">截止时间</td>
                <td >操作</td>
            </tr>
        </thead>
        <tbody id="opt-package-list">
            @foreach ($table_data_list as $var)
                <tr>
		           @include("layouts.td_xs_opt")
                   <td class="packageid" >{{$var["packageid"]}}</td>
                   <td class="package_type" >{{$var["package_name"]}}</td>
                   <td class="subject" >{{$var["subject"]}}</td>
                   <td class="grade dnone" >{{$var["grade"]}}</td>
                   <td class="current_price dnone" >{{$var["current_price"]}}</td>
                   <td class="package_type" >{{$var["package_type_str"]}}</td>
                   <td class="package_tags" >{{$var["package_tags"]}}</td>
                   <td class="tag_type dnone" >{{$var["tag_type"]}}</td>
                   <td class="package_deadline dnone" >{{$var["package_deadline"]}}</td>
                   <td class="remove-for-xs">
                       <div class="btn-group opt"
                            data-packageid="{{$var["packageid"]}}"
                            data-package_pic="{{$var["package_pic"]}}"
                            data-package_type="{{$var["package_type"]}}"
                       >
                           <a class="btn fa fa-info td-info " href="javascript:;" title="查看信息"></a>
                           <a class="btn fa fa-edit opt-package-edit" href="javascript:;" title="修改信息"></a>
                           <a class="btn fa fa-photo opt-package-pic" href="javascript:;" title="设置图片"></a>
                           <a class="btn fa fa-comment opt-package-intro" href="javascript:;" title="设置简介"></a>
                           <a class="btn fa fa-file-powerpoint-o opt-package-outline" href="javascript:;" title="设置大纲"></a>
                           <a class="btn fa fa-users opt-package-class dnone" href="javascript:;" title="设置班级" ></a>
                           <a class="btn fa fa-cny opt-package-price dnone" href="javascript:;" title="设置价格"></a>
                           <a class="btn fa fa-clock-o opt-package-users dnone" href="javascript:;" title="设置购买的时间和人数"></a>
                           <a class="btn fa fa-slideshare opt-package-open-courseid dnone" href="javascript:;" title="设置公开课id"></a>
                           <a class="btn fa fa-user-plus opt-package-buy dnone" href="javascript:;" title="get package bought"></a>
                           <a class="btn fa fa-trash-o opt-package-delete dnone" href="javascript:;" title="删除记录"></a>
                           <a class="btn fa fa-tag opt-package-tag-type dnone" href="javascript:;" title="设置 标签"></a>
                       </div>
                   </td>
                </tr>
           @endforeach
        </tbody>
    </table>

<div class="dlg-add-new-course" style="display:none">
    <table class="table table-bordered table-striped package_info_add"   >
        <tr>
            <td>课程包名称</td>
            <td>
                <input type="text" class="form-control package_name" >
            </td>
            <td></td>
        </tr>
        <tr>
            <td>课程包类型</td>
            <td>
                <select class="form-control package_type" >
                    <option value="1001">普通公开课</option>
                    <option value="3001">普通小班课</option>
                </select>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>课程标签</td>
            <td>请点击＋添加课程标签</td>
            <td>
                <button class="btn btn-primary fa fa-plus form-control add_package_tag" ></button>
            </td>
        </tr>
    </table>
</div>

<div class="dlg-package-class" style="display:none">
    <table class="table table-bordered table-striped package_class_info"   >
        <tr>
            <td>设置班级</td>
            <td><input type="text" class="form-control small_classes " /></td>
            <td></td>
        </tr>
    </table>
</div>

<div class="dlg-package-feature" style="display:none">
    <table class="table table-bordered table-striped package_feature_info"   >
        <tr>
            <td>课程特色</td>
            <td>请点击＋添加课程特色条目</td>
            <td>
                <button class="btn btn-primary fa fa-plus form-control add_package_feature " ></button>
            </td>
        </tr>
        <tr>
            <td>师资介绍</td>
            <td>请点击＋添加老师</td>
            <td>
                <button class="btn btn-primary fa fa-plus form-control add_package_teacher" ></button>
            </td>
        </tr>
    </table>
</div>

<div class="dlg-package-outline" style="display:none">
    <table class="table table-bordered table-striped package_outline_set"   >
        <tr>
            <td>课程大纲</td>
            <td>请点击＋添加课程大纲条目</td>
            <td>
                <button class="btn btn-primary fa fa-plus form-control add_package_outline" ></button>
            </td>
        </tr>
    </table>
</div>

<div class="dlg-package-sc" style="display:none">
    <table class="table table-bordered table-striped package_small_class"   >
        <tr>
            <td>课程小班</td>
            <td>请点击＋添加课程大纲条目</td>
            <td>
                <button class="btn btn-primary fa fa-plus form-control add_package_sc" ></button>
            </td>
        </tr>
    </table>
</div>

<div class="dlg-package-simple-info" style="display:none">
    <table class="table table-bordered table-striped package_info_edit"   >
        <tr>
            <td>课程包名称</td>
            <td>
                <input type="text" class="form-control package_name" >
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>课程简介</td>
            <td><textarea class="form-control package_intro" ></textarea></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>教学目标</td>
            <td><input type="text" class="form-control package_target "></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>适合学员</td>
            <td><input type="text" class="form-control suit_student "></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>适合科目</td>
            <td>
                <select class="subject form-control" >
                    <option value="0">全部</option>
                    <option value="1">语文</option>
                    <option value="2">数学</option>
                    <option value="3">英语</option>
                    <option value="4">化学</option>
                    <option value="5">物理</option>
                    <option value="6">生物</option>
                    <option value="7">政治</option>
                    <option value="8">历史</option>
                    <option value="9">地理</option>
                </select>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>课程次数</td>
            <td><input type="text" class="form-control lesson_total" /></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>适合年级</td>
            <td class="grade">
            </td>
            <td>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>课程标签</td>
            <td>请点击＋添加课程标签</td>
            <td>
                <button class="btn btn-primary fa fa-plus form-control add_package_tag" ></button>
            </td>
            <td></td>
        </tr>
        
    </table>
</div>


<div class="dlg_modify_package_pic" style="display:none">
    <div>
        <div >
            <img width="300" class="package_pic" src="" />
        </div>
        <div>
            <a class="upload_package_pic" href="javascript;">上传图片</a>
        </div>
    </div>
</div>

<div class="dlg-choose-small-class" style="display:none">
    <table class="table table-bordered table-striped small_class_list"   >
        <tr>
            <td>课程id</td>
            <td>课程名称</td>
            <td>课程老师</td>
            <td>课程时间</td>
            <td>开课时间</td>
        </tr>
    </table>
</div>

<div class="dlg-choose-open-class" style="display:none">
    <table class="table table-bordered table-striped open_class_list"   >
        <tr>
            <td>课程id</td>
            <td>课程名称</td>
            <td>课程老师</td>
        </tr>
    </table>
</div>

<div style="display:none;" >
    <select id="id_enter_type">
        <option value="1">逐个加入</option>
        <option value="2">条件判断</option>
    </select>

    <select id="id_can_set">
        <option value="1">不可设置</option>
        <option value="2">可设置</option>
    </select>
    
    <select id="id_lesson_type">
        <option value="1001">可举手</option>
        <option value="1002">不可举手</option>
        <option value="1003">1v1</option>
        <option value="2001">答疑</option>
        <option value="4001">机器课程</option>
    </select>

    <select id="id_subject_list">
    </select>
    
    <select id="id_grade_list">
    </select>

    <select id="id_tea_list">
        <option value="-1">选择老师</option>
        @foreach ($tea_list as $var)
            <option value="{{$var["teacherid"]}}">{{$var["nick"]}}</option>
        @endforeach
    </select>
    <input ="id_courseid" />
</div>
@endsection

