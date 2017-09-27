@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/all.js"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">年级</span>
                    <select class="form-control grade" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">科目</span>
                    <select class="form-control subject" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">考试类型</span>
                    <select class="form-control test_type" >
                    </select>
                </div>
            </div>

            <div class="col-xs-2">
                <div class="input-group input-group-btn ">
                    <button class="btn btn-primary add_new">添加</button>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td class="remove-for-not-xs"></td>
                    <td>id</td>
                    <td>标题</td>
                    <td>内容</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>类型</td>
                    <td>自定义标签</td>
                    <td>封面</td>
                    <td>操作人员</td>
                    <td>添加时间</td>
                    {!!\App\Helper\Utils::th_order_gen([

                    ["访问次数" , "visit_num"],
                    ["分享次数" , "share_num"],
                    ]) !!}
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				            <tr>
                        @include('layouts.td_xs_opt')
                        <td>{{$var["id"]}} </td>
                        <td>{{$var["test_title"]}}</td>
                        <td>{{$var["test_des"]}}</td>
                        <td>{{$var["grade_str"]}}</td>
                        <td>{{$var["subject_str"]}}</td>
                        <td>{{$var["test_type_str"]}}</td>
                        <td>
                            @foreach ($var['new_arr'] as $v)
                                <div>{{$v}}</div>
                            @endforeach
                        </td>
                        <td><img src="{{$var["poster"]}}" height="100"></td>
                        <td>{{$var["account"]}}</td>
                        <td>{{$var["create_time"]}}</td>

                        <td>{{$var["visit_num"]}}</td>
                        <td>{{$var["share_num"]}}</td>
                        <td class="remove-for-xs">
                            <div class="btn-group" data-id="{{$var["id"]}}">
                                <a href="javascript:;" class="btn fa fa-edit opt-update-new_info" title="更改"></a>
                                <a href="javascript:;" class="btn fa fa-trash-o opt-del" title="删除"></a>
                            </div>
                        </td>
				            </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    <div class="dlg_add_new" style="display:none">
        <table class="table table-bordered table-striped">
	          <tbody>
                <tr>
			              <td style="text-align:right; width:30%;">标题</td>
			              <td><input value="" class="add_test_title" type="text" style="width:80%"/></td>
		            </tr>
                <tr>
			              <td style="text-align:right; width:30%;">内容</td>
			              <td>
                        <textarea class="add_test_des" cols="5" style ="width:80%;height:150px;"></textarea>
                    </td>
		            </tr>
                <tr>
			              <td style="text-align:right; width:30%;">年级</td>
			              <td>
                        <select class="add_grade" style="width:30%">
                        </select>
                    </td>
		            </tr>
                <tr>
			              <td style="text-align:right; width:30%;">科目</td>
			              <td>
                        <select class="add_subject" style="width:30%">
                        </select>
                    </td>
		            </tr>
                <tr>
			              <td style="text-align:right; width:30%;">类型</td>
			              <td>
                        <select class="add_test_type" style="width:30%">
                        </select>
                    </td>
		            </tr>
                <tr>
			              <td style="text-align:right; width:30%;">自定义标签</td>
			              <td>
                        <div class="row">
                            @foreach ($type_arr as $k => $v)
                                <div class="col-xs-6 col-md-6">
                                    <label>
                                        <input type="checkbox" onclick="set_custom(this)" name="add_custom_type" value="{{$k}}">{{$v}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
		            </tr>

                <tr>
			              <td style="text-align:right; width:30%;">图片</td>
			              <td>
                        <div id="id_container_add">
                            <input id="id_upload_add" value="上传图片" class="btn btn-primary add_pic_img" style="margin-bottom:5px;" type="button"/>
                        </div>
                        <div class="add_header_img"></div>
                        <div class="add_pic" style="display:none"></div>
                    </td>
		            </tr>
		        </tbody>
	      </table>
    </div>
@endsection
