@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>ID</td>
                    <td>添加时间</td>
                    <td>第一次试听课时间</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>pad</td>
                    <td>地区</td>
                    <td>电信商</td>
                    <td>第三级渠道</td>
                    <td>第二级渠道</td>
                    <td>渠道进入数</td>
                    <td>电话接通数</td>
                    <td>回流公海数</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["userid"]}} </td>
                        <td>{{@$var["add_time"]}} </td>
                        <td>{{@$var["lesson_time"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["pad_str"]}} </td>
                        <td>{{@$var["location"]}} </td>
                        <td>{{@$var["cor"]}} </td>
                        <td>{{@$var["three_origin"]}} </td>
                        <td>{{@$var["two_origin"]}} </td>

                        <td>{{@$var["origin_count"]}} </td>
                        <td>{{@$var["cc_called_count"]}} </td>
                        <td>{{@$var["return_publish_count"]}} </td>


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

