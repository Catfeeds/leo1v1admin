@extends('layouts.app') @section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<script type="text/javascript" src="/js/all.js"></script>
<style>
 .share_s{display:none;}
</style>
<section class="content">
    <div class="row">
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">点击状态</span>
                <select id="id_ad_status" class="opt-change form-control status" >
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <span >日期</span>
                <input type="text" id="id_start_date" class="opt-change"/>
                <span >-</span>
                <input type="text" id="id_end_date" class="opt-change"/>
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group col-sm-12">
                <input type="text" value="" class="form-control"  id="id_ad_info" placeholder="输入关键词，回车查找"/>
                <div class=" input-group-btn ">
                    <button id="id_search_ad" type="submit"  class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div> 
            </div>
        </div>
        <div class="col-xs-2">
            <div class="input-group input-group-btn">
                <button class="btn btn-primary form-control add_new_ad_info" >添加数据</button>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td class="remove-for-not-xs"></td>
                <td>id</td>
                <td>闪屏图片浏览</td>
                <td>有效时间</td>
                <td>状态</td> 
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
				<tr>
                    @include('layouts.td_xs_opt')
                    <td>{{$var["id"]}} </td>
                    <td><img width="75px" src="{{$var["ad_url"]}}"/></td>
                    <td>{{$var["time_str"]}}</td>
                    <td>{{$var["status_str"]}}</td>
                    <td class="remove-for-xs">
                        <div class="btn-group" data-id="{{$var["id"]}}">
                            <a class="fa-edit opt-update-ad_info" title="更改"></a>
                            <a class="fa-trash-o opt-del" title="删除"></a>
                        </div>
                    </td>
				</tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
</section>
<div class="dlg_add_ad_info" style="display:none">
    <table class="table table-bordered table-striped">
	    <tbody>
		    <tr>
			    <td style="text-align:right; width:30%;">闪屏图片上传</td>
			    <td>
                    <div id="id_container_add">
                        <input id="id_upload_add" value="上传图片" class="btn btn-primary add_ad_url" style="margin-bottom:5px;" type="button"/>
                    </div>
                    <div class="add_ad_url"></div>
                </td>
		    </tr>
            <tr>
			    <td style="text-align:right; width:30%;">开始时间</td>
			    <td><input class="add_ad_start_date" type="text"/></td>
            </tr>
            <tr>
			    <td style="text-align:right; width:30%;">结束时间</td>
			    <td><input class="add_ad_end_date" type="text"/></td>
            </tr>
            <tr>
			    <td style="text-align:right; width:30%;">点击状态</td>
			    <td><select class="add_ad_status"></select></td>
            </tr>
            <tr class="share_s">
			    <td style="text-align:right; width:30%;">跳转地址</td>
			    <td><input class="add_url" type="text"/></td>
            </tr>
            <tr class="share_s">
			    <td style="text-align:right; width:30%;">分享标题</td>
			    <td><input class="add_ad_title" type="text"/></td>
            </tr>
            <tr class="share_s">
			    <td style="text-align:right; width:30%;">分享内容</td>
			    <td><input class="add_ad_info" type="text"/></td>
            </tr>
		    <tr class="share_s">
			    <td style="text-align:right; width:30%;">分享图标</td>
			    <td>
                    <div id="id_container_add_img">
                        <input id="id_upload_add_img" value="上传图片" class="btn btn-primary add_img_url" style="margin-bottom:5px;" type="button"/>
                    </div>
                    <div class="add_img_url"></div>
                </td>
		    </tr>
		</tbody>
	</table>
</div>
@endsection
