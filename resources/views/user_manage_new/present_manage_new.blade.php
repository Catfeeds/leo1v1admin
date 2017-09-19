@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/source/jquery.fancybox.js"></script>
    <script>
        var pub_domain = <?php echo json_encode($pub_domain); ?>
    </script>
    <link rel="stylesheet" type="text/css" href="/source/jquery.fancybox.css" media="screen" />
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <button id="opt-add-gift" class="btn btn-warning fa fa-plus fa-lg form-control " >添加礼品</button>
                    </div>
                </div>
                <div class="col-xs-4 pull-right" style="text-align:right"><h4>人民币：赞 = 1：<span id="ratio">{{ $cur_ratio }}</span></h4></div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td> 礼品id </td>
                    <td> 礼品类型  </td>
                    <td> 名称 </td>
                    <td> 封面 </td>
                    <td> 原价 </td>
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
                        <td>{{@$var["cost_price"]}} </td>
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
