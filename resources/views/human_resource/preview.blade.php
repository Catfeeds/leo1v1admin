@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
      <div class="right">
        <div class=" helper_teach">
			<div class="teacher_list">
                <div class="cont_box">
                    <div class="row">
                        <div class="col-xs-6 col-md-2" >
                            <div class="input-group ">
                                <span >老师</span>
                                <input id="id_teacherid"  /> 
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
                                    <td >teacherid</td>
                                    <td >教师姓名</td>
                                    <td >年级</td>
                                    <td >科目</td>
                                    <td class="remove-for-xs">操作</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ret_info as $var)
                                    <tr>
                                    @include('layouts.td_xs_opt')
                                    <td>{{$var["teacherid"]}}</td>
                                    <td>{{$var["nick"]}}</td>
                                    <td>{{$var["grade"]}}</td>
                                    <td>{{$var["subject"]}}</td>
                                    <td  >
                                        <div   data-teacherid="{{$var["teacherid"]}}"
                                        >
                                            <a href="javascript:;" title="编辑教师信息" class="btn fa fa-edit opt-edit-info"></a>
                                            <a href="javascript:;" title="删除" class="btn fa fa-trash-o fa-lg done_t"></a>
                                        </div>
                                    </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
	</div>

   
  
</body>
@endsection
