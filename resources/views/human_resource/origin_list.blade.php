@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <input type="text" value="" class=" form-control click_on put_name for_input"  data-field="user_name" id="id_user_name"  placeholder="请按姓名,手机,岗位   回车查找" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>渠道</td>
                    <td>报名数</td>
                    <td>录制试讲数</td>
                    <td>试讲率</td>
                    <td>通过试讲数</td>
                    <td>通过率</td>
                    <td>参与培训数</td>
                    <td>通过培训数</td>
                    <td>培训通过率</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $key=>$var )
                    <tr>
                        <td>{{@$var['teacher_ref_type']}} </td>
                        <td>{{@$var["num"]}} </td>
                        <td>{{@$var["lecture_total"]}} </td>
                        <td>{{@$var["lecture_rate"]}} </td>
                        <td>{{@$var["pass_total"]}} </td>
                        <td>{{@$var["pass_rate"]}} </td>
                        <td>{{@$var["train_num"]}} </td>
                        <td>{{@$var["through_num"]}} </td>
                        <td>{{@$var["through_rate"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection

