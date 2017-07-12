@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>

    <section class="content">
      <div class="right">
        <div class=" helper_teach">
			<div class="teacher_list">
                <div class="cont_box">
                    <div class="row">
                        <div class="col-md-2 col-xs-3 ">
                            <div class="input-group">
                                <span class="input-group-addon">兼职</span>
                                <select id="id_is_part_time" class="hr_change form-control">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-5 ">
                            <div class="input-group  ">
                                <span class="input-group-addon">评分</span>
                                <select id="id_rate_score" class=" hr_change form-control">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-10 ">
                            <div class="input-group col-sm-12">
                                <span class="input-group-addon">姓名</span>
                        	    <input type="text" value="" class="for_input form-control opt-change" data-field="tea_name" id="id_ass_name" placeholder="请输入助教姓名"  />
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-10 ">
                            <div class="input-group col-sm-12">
                                <span class="input-group-addon">手机</span>
                        	    <input type="text" value="" class="put_name for_input form-control opt-change" data-field="ass_name" id="id_ass_phone" placeholder="请输入手机号"  />
                            </div>
                        </div>
                        <div class="col-md-1 col-xs-5 ">
                            <div class="input-group input-group-btn">
                                <button class="btn btn-warning done_s fa fa-plus form-control">新增助教</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="cont_box">
                    <!-- <h3>助教查找结果<a href="javascript:;" class="done_s">新增助教</a></h3> -->
                    <div class="cont">
                        <table   class="table table-bordered table-striped"   >
                            <thead>
                                <tr>
                                    <td class="remove-for-not-xs" ></td>
                                    <td class="remove-for-xs">序号</td>
                                    <td >id</td>
                                    <td >助教姓名</td>
                                    <td class="remove-for-xs">兼职/全职</td>
                                    <td >性别</td>
                                    <td class="remove-for-xs">年龄</td>
                                    <td class="remove-for-xs">学校</td>
                                    <td class="remove-for-xs">手机</td>
                                    <td >邮箱</td>
                                    <td class="remove-for-xs">评价</td>
                                    <td class="remove-for-xs">操作</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($table_data_list as $var)
                                    <tr>
                                    @include('layouts.td_xs_opt')
                                    <td class="remove-for-xs">{{$var["number"]}}</td>
                                    <td>{{$var["assistantid"]}}</td>
                                    <td>{{$var["ass_nick"]}}</td>
                                    <td class="remove-for-xs">{{$var["is_part_time"]}}</td>
                                    <td>{{$var["gender"]}}</td>
                                    <td class="remove-for-xs">{{$var["age"]}}</td>
                                    <td class="remove-for-xs">{{$var["school"]}}</td>
                                    <td class="remove-for-xs">{{$var["phone"]}}</td>
                                    <td>{{$var["email"]}}</td>
                                    <td class="remove-for-xs">{{$var["rate_score"]}}</td>
                                    <td class="remove-for-xs done_icon wid02">
                                        <div class="opt">
                                            <span data-assistantid="{{$var["assistantid"]}}" >
                                                <a href="javascript:;" title="查看详情" class="btn fa fa-info done_o"></a>
                                                <a href="javascript:;" title="修改助教信息" class="btn fa fa-edit opt-update-news"></a>
                                                <a href="javascript:;" title="修改passwd" class="btn fa fa-key opt-update-passwd"></a>
                                                <a href="javascript:;" title="删除" class="btn fa fa-trash-o fa-lg done_t"></a>
                                            </span>
                                        </div>
                                    </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @include("layouts.page")
            </div>

            <div class="teach_mesg" style="display:none">
                <div class="row">
                    <div class="col-xs-9 col-md-10">
                    </div>
                    <div class="col-xs-3 col-md-2">
            	        <p class="back_p"><button id="id_back_to_main" class="back btn btn-primary">返回</button></p>
                    </div>
                </div>
                <div class="row">
                <div class="col-xs-12 col-md-2" style="text-align:center">
                    <div class="header_img"><img src="/images/header_img.jpg" /></div>
                    <div id="id_container">
                        <p class="upload_btn">
                            <input id="id_upload" class="btn btn-primary" type="button" value="上传头像" >
                            <input id="id_modify" class="btn btn-primary" type="button" value="更改信息" >
                        </p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-9">
                    <a href="javascript:;" class="load_editor" id="id_save_info"></a>
                    <table border="1" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td width="10%">姓名：</td>
                            <td>
                                <span class="put_mesag" id="id_detail_name"></span>
                            </td>
                            <td width="8%">性别：</td>
                            <td>
                                <span class="put_mesag02" id="id_ass_gender"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>生日</td>
                            <td width="50%">
                                <span class="put_mesag" id="id_ass_birth"></span>
                            </td>
                            <td width="9%" >工龄</td>
                            <td>
                                <span class="put_mesag" id="id_ass_work_year"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>手机</td>
                            <td colspan="3">
                                <span class="put_mesag" id="id_detail_phone"></span>
                            </td>
                            
                        </tr>
                        <tr>
                            <td>邮箱</td>
                            <td colspan="3">
                                <span class="put_mesag" id="id_ass_email"></span>
                            </td>
                            
                        </tr>
                        <tr>
                            <td>学校</td>
                            <td colspan="3">
                                <span class="put_mesag" id="id_ass_school"></span>
                            </td>
                            
                        </tr>
                        <tr>
                            <td>兼/全</td>
                            <td colspan="3">
                                <span class="put_mesag03" id="id_ass_type"></span>
                            </td>
                            
                        </tr>
                        <tr>
                            <td width="8%">评价</td>
                            <td>
                                <span id="id_ass_score"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>证书：</td>
                            <td colspan="3">
                            	<div class="put_mesag" id="id_ass_prize"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>带教风格：</td>
                            <td colspan="3">
                            	<div class="put_mesag" id="id_ass_style"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>成功案例：</td>
                            <td colspan="3">
                            	<div class="put_mesag" id="id_ass_achievement"></div>
                            </td>
                        </tr>
                        <tr>
                        	<td>自我介绍：</td>
                            <td colspan="3">
                            	<div class="put_mesag" id="id_ass_base_intro"></div>
                            </td>
                        </tr>
                        
                    </table>
                    
                </div>
                </div>
            </div>
        </div>
	</div>
    
    <div style="display:none;" >
        <select id="id_sex">
            <option value="1">男</option>
            <option value="2">女</option>
        </select>

        <select id="id_job">
            <option value="1">全职</option>
            <option value="2">兼职</option>
        </select>
    </div>
    <div class="dlg_modify_assistant" style="display:none">
        <div class="row">
            <div class="input-group">
                <span class="input-group-addon">助教姓名 </span>
                <input type="text" class="edi edit_b form-control" id="id_edit_name"/>
            </div>
            <div class="input-group">
                <span class="input-group-addon">性别</span>
                <select class="edi" id="tea_sexy" >
                    <option value="1">男</option>
                    <option value="2">女</option>
                </select>
            </div>
            <div class="input-group">
                <span class="input-group-addon">出生日期</span>
                <input type="text" class="edi edit_b dlg_datetimepicker" id="id_edit_birth"/>
                <span class="input-group-addon">从业年龄</span>
                <input type="text" class="edi edit_b" id="id_edit_work_year"/>
            </div>
            <div class="input-group">
                <span class="input-group-addon">邮箱</span>
                <input type="text" class="edi edit_b" id="id_edit_email"/>
            </div>
            <div class="input-group">
                <span class="input-group-addon">学校</span>
                <input type="text" class="edi edit_b" id="id_edit_school"/>
            </div>
            <div class="input-group">
                <span class="input-group-addon">兼职|全职</span>
                <select class="edi" id="tea_job" >
                    <option value="1">兼职</option>
                    <option value="0">全职</option>
                </select>
            </div>
            <div class="input-group">
                <span class="input-group-addon">证书</span>
                <textarea class="edi edit_b" id="id_edit_prize"></textarea>
            </div>
            <div class="input-group">
                <span class="input-group-addon">带教风格</span>
                <textarea class="edi edit_b" id="id_edit_style"></textarea>
            </div>
            <div class="input-group">
                <span class="input-group-addon">成功案例</span>
                <textarea class="edi edit_b" id="id_edit_achievement"></textarea>
            </div>
            <div class="input-group">
                <span class="input-group-addon">自我介绍</span>
                <textarea class="edi edit_b" id="id_edit_base_intro"></textarea>
            </div>
        </div>
    </div>


</body>
@endsection
