@extends('layouts.app')
@section('content')

    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <input class="opt-change form-control" id="id_nick_phone" placeholder="输入电话查询"/>
                        <ul id="cur_ret"> </ul>
                    </div>
                </div>
            </div>
            <select>
                <option value="">[全部]</option>
                <option value="540">市场-QC-施文斌</option>
                <option value="968">市场-QC-李珉劼</option>
                <option value="1024">市场-QC-王浩鸣</option>
                <option value="">老师反馈处理-老师薪资及反馈-苏佩云</option>
                <option value="1040">老师反馈处理-老师管理运营-郭东</option>
                <option value="967">老师反馈处理-老师管理运营-傅文莉</option>
                <option value="379">教研-语文组-许琼文</option>
                <option value="868">教研-语文组-黄灼文</option>
                <option value="849">教研-语文组-张敏</option>
                <option value="913">教研-语文组-潘艳亭</option>
                <option value="404">教研-语文组-唐灵莉</option>
                <option value="310">教研-数学组-彭标</option>
                <option value="480">教研-数学组-徐格格</option>
                <option value="866">教研-数学组-王海</option>
                <option value="890">教研-数学组-夏劲松</option>
                <option value="892">教研-数学组-梁立玉</option>
                <option value="329">教研-英语组-许千千</option>
                <option value="372">教研-英语组-赖国芬</option>
                <option value="923">教研-英语组-王芳</option>
                <option value="770">教研-物理组-展慧东</option>
                <option value="793">教研-化学组-李红涛</option>
                <option value="1118">产品-产品-孙瞿</option>
            </select>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>id</td>
                    <td>姓名</td>
                    <td>电话</td>
                    <td>角色</td>
                    <td>时间</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["uid"]}} </td>
                        <td>{{@$var["name"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["account_role_str"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
