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
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <button id="opt-add-gift" class="btn btn-warning fa fa-plus fa-lg form-control " >新增商品</button>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td> 礼品id </td>
                    <td> 礼品类型  </td>
                    <td> 名称 </td>
                    <td> 图片 </td>
                    <td> 消耗赞  </td>
                    <td> 商品简介 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["giftid"]}} </td>
                        <td>{{@$var["gift_type_str"]}} </td>
                        <td>{{@$var["gift_name"]}} </td>
                        <td class="gift_url">
                            <a class="fancybox-effects-a" href="{{@$var["gift_pic"]}}">查看</a>
                        </td> 
                        <td>{{@$var["current_praise"]}} </td>
                        <td>{{@$var["gift_intro"]}} </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

	                            <a href="javascript:;" title="删除" class="fa fa-trash-o fa-lg  opt-gift-delete"></a>
                                <a href="javascript:;" title="修改" class="fa fa-gavel fa-lg  opt-gift-modify"></a>
                                <a href="javascript:;" title="查看图片" class="fa fa-picture-o fa-lg  opt-gift-desc"></a>
                            </div> 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mesg_alert dlg_modify_gift" style="display:none">
            <div class="row" >
                <p style="height:5px;width:100%;background:#eee;font-size:5px;position:absolute;left:0;top:0;"></p>
                <p class="upload_process_info" style="height:5px;width:0;background:#0bceff;font-size:5px;position:absolute;left:0;top:0;z-index:2;"></p>
            </div>
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">礼品名称</span>
                    <input type="text" class="gift_name" >
                </div>
            </div>
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">礼品类型</span>
                    <select class="gift_type" >
                        <option value="0">系统礼包</option>
                        <option value="1">实物</option>
                        <option value="2">虚拟物品（手机）</option>
                        <option value="3">虚拟物品（qq）</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="input-group" >
                    <span class="input-group-addon">图片地址</span>
                    <input class="gift_url" type="text">
                    <span class="input-group-addon">
                        <div>
                            <a class="upload_gift_pic" href="javascript:;">上传</a>
                            <a class="preview_gift_pic" href="javascript:;">预览</a>
                        </div>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="input-group" >
                    <span class="input-group-addon">所需赞数</span>
                    <input class="gift_praise" type="text">
                </div>
            </div>
            <div class="row">
                <div class="input-group" >
                    <span class="input-group-addon">商品简介</span>
                    <textarea class="gift_intro" style="width:100%;height:100px;"></textarea>
                </div>
            </div>
        </div>

        <div class="mesg_alert dlg_add_gift" style="display:none">
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">礼品名称</span>
                    <input class="gift_name" type="text" >
                </div>
            </div>
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">礼品类型</span>
                    <select class="gift_type">
                        <option value="0">系统礼包</option>
                        <option value="1">实物</option>
                        <option value="2">虚拟物品（手机）</option>
                        <option value="3">虚拟物品（qq）</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="input-group" >
                    <span class="input-group-addon">图片地址</span>
                    <input class="gift_url" type="text">
                </div>
            </div>
            <div class="row">
                <div class="input-group" >
                    <span class="input-group-addon">所需赞数</span>
                    <input class="gift_praise" type="text">
                </div>
            </div>
            <div class="row">
                <div class="input-group" >
                    <span class="input-group-addon">商品简介</span>
                    <textarea class="gift_intro" style="width:100%;height:100px;"></textarea>
                </div>
            </div>
        </div>

        <div class="mesg_alert dlg_modify_gift_desc" style="display:none">
            <div>
                <div class="show_pic row" style="overflow:hidden">
                </div>
                <div class="row" style="overflow:hidden">
                    <div class="add_pic " style="float:left; margin-right:10px;">
                        <a class="opt_add_pic" href="javascript:;">
                            <img src="/images/add_img.png" style="width:100px; height:100px;"> </a>
                    </div>
                    <div class="del_pic " style="float:left; margin-right:10px;">
                        <a class="opt_del_pic" href="javascript:;"> <img src="/images/min_img.png" style="width:100px; height:100px;"> </a>
                    </div>
                </div>
            </div>
        </div>

        @include("layouts.page")
    </section>
    
@endsection

