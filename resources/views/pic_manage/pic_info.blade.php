@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/all.js"></script>
    <style>
     .modal-content {
         width: 800px;
     }
    </style>
    <script type="text/javascript">
        var min_date = '<?php echo $min_date; ?>';
    </script>
    <section class="content">
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">图片类型</span>
                    <select class="form-control pic_type opt-change" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">用途类型</span>
                    <select class="form-control pic_usage_type opt-change">
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">活动状态</span>
                    <select id="active_status" class="opt-change">>
                        <option value="0">[全部]</option>
                        <option value="1">待开始</option>
                        <option value="2">已发布</option>
                        <option value="3">已结束</option>
                        <option value="4">已删除</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-2">
                <div class="input-group input-group-btn ">
                    <button class="btn btn-primary add_pic_info">添加数据</button>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td class="remove-for-not-xs"></td>
                    <td>id</td>
                    <td>类型</td>
                    <td>图片预览</td>
                    <td>图片备注</td>
                    <td>用途</td>
                    <td>活动状态</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				            <tr>
                        @include('layouts.td_xs_opt')
                        <td>{{$var["id"]}} </td>
                        <td>{{$var["type_str"]}}</td>
                        <td><img src="{{$var["img_url"]}}" height="100"></td>
                        <td>{{$var["name"]}}</td>
                        <td>{{$var["usage_type_str"]}}</td>
                        <td>{{$var['active_status']}}</td>
                        <td class="remove-for-xs">
                            <div class="btn-group" data-id="{{$var["id"]}}">
                                @if($var['del_flag'] == 0)
                                <a href="javascript:;" class="btn fa fa-edit opt-update-pic_info" title="更改"></a>
                                <a href="javascript:;" class="btn fa fa-trash-o opt-del" title="删除"></a>
                                @endif
                            </div>
                        </td>
				            </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    <div class="dlg_add_pic_info" style="display:none">
        <table class="table table-bordered table-striped">
	        <tbody>
                <tr>
			        <td style="text-align:right; width:150px;">图片类型</td>
			        <td>
                  <select class="add_pic_type ">
                      <option value="0">请选择</option>
                        </select>
                    </td>
		        </tr>
                <tr>
			        <td style="text-align:right; width:150px;">用途类型</td>
			        <td>
                  <select class="add_pic_usage_type">
                      <option value="0">请选择</option>
                  </select>
              </td>
		            </tr>
                <tr>
			              <td style="text-align:right; width:150px;">图片名称</td>
			              <td><input value="" class="add_pic_name" type="text" size="30" /></td>
		            </tr>
                <tr class="time_s">
			              <td style="text-align:right; width:150px;">开始时间</td>
			              <td><input class="add_start_date" type="text"/></td>
                </tr>
                <tr class="time_s">
			              <td style="text-align:right; width:150px;">结束时间</td>
			              <td><input class="add_end_date" type="text"/></td>
                </tr>

                <tr class="icon_s class_icon_s">
			              <td style="text-align:right; width:30%;">年级</td>
			              <td>
                        <select class="add_pic_grade">
                        </select>
                    </td>
		            </tr>
                <tr class="icon_s class_icon_s">
			              <td style="text-align:right; width:30%;">科目</td>
			              <td>
                        <select class="add_pic_subject">
                        </select>
                    </td>
		            </tr>
                <tr>
			        <td style="text-align:right; width:150px;">图片上传</td>
			        <td>
                        <div id="id_container_add">
                            <input id="id_upload_add" value="上传图片" class="btn btn-primary add_pic_img" style="margin-bottom:5px;" type="button"/>
                        </div>
                        <div class="add_header_img"></div>
                        <div class="pic_url"></div>
                    </td>
		        </tr>
		        <!-- <tr>
			           <td style="text-align:right;width:30%;">tag/分享图片上传</td>
			           <td>
                 <div id="id_container_tag_add">
                 <input id="id_upload_tag_add" value="上传图片" class="btn btn-primary add_tag_img" style="margin-bottom:5px;" type="button"/>
                 </div>
                 <div class="add_header_tag_img"></div>
                 </td>
		             </tr>
            -->

                <tr class="icon_s">
			        <td style="text-align:right; width:150px;">图标顺序/消息类型</td>
			        <td><!-- <input value="" class="add_pic_order_by" type="text"/> -->
                  <select class="add_pic_order_by">
                      <option value="1" selected>1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                  </select>
              </td>
		        </tr>
                <tr>
			        <td style="text-align:right; width:150px;">图片点击状态</td>
			        <td>
                  <select class="add_pic_click_status">
                  </select>
              </td>
		            </tr>
                <tr class="share_s">
			              <td style="text-align:right; width:150px;">跳转目标类型</td>
			              <td><select class="add_jump_type"></select></td>
                </tr>
            <tr class="share_s">
			          <td style="text-align:right; width:150px;">跳转地址</td>
			          <td><input class="add_jump_url" type="text"/></td>
            </tr>
            <!-- <tr class="share_s">
			           <td style="text-align:right; width:30%;">分享标题</td>
			           <td><input class="add_title_share" type="text"/></td>
                 </tr>
                 <tr class="share_s">
			           <td style="text-align:right; width:30%;">分享内容</td>
			           <td><textarea class="add_info_share"></textarea></td>
                 </tr> -->
		    </tbody>
	    </table>
    </div>
@endsection
