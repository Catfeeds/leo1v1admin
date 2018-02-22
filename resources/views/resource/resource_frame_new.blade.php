@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/js/jquery.contextify.js"></script>
    <script type="text/javascript" src="/js/area/distpicker.data.js"></script>
    <section class="content ">

        <hr/>
        <table class="table table-hover table-bordered">
            <tr level="1" info_str="1" >
                <th>1对1精品课程</th>
                <th>科目</th>
                <th>年级</th>
                <th>教材版本</th>
                <th>春暑秋寒</th>
                <th>学科化标签</th>
                <th></th>
                <th></th>
            </tr>
            <tr level="1" info_str="2" >
                <th>1对1特色课程</th>
                <th>科目</th>
                <th>年级</th>
                <th>教材版本</th>
                <th>春暑秋寒</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr level="1" info_str="3" >
                <th>标准试听课</th>
                <th>科目</th>
                <th>年级</th>
                <th>教材版本</th>
                <th>试听类型</th>
                <th>难度类型</th>
                <th>学科化标签</th>
                <th></th>
            </tr>
            <tr level="1" info_str="4" >
                <th>测评库</th>
                <th>科目</th>
                <th>年级</th>
                <th>教材版本</th>
                <th>上下册</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr level="1" info_str="5" >
                <th>电子教材</th>
                <th>科目</th>
                <th>年级</th>
                <th>教材版本</th>
                <th>上下册</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr level="1" info_str="6" >
                <th>试卷库</th>
                <th>科目</th>
                <th>年级</th>
                <th>上下册</th>
                <th>分类标准</th>
                <th>教材版本/省份</th>
                <th>城市</th>
                <th></th>
            </tr>
            <tr level="1" info_str="7" >
                <th>知识图谱</th>
                <th>科目</th>
                <th>年级段</th>
                <th>一级知识点</th>
                <th>二级知识点</th>
                <th>三级知识点</th>
                <th></th>
                <th></th>
            </tr>
            <tr level="1" info_str="9">
                <th>培训库</th>
                <th>科目</th>
                <th>年级</th>
                <th>教材版本</th>
                <th>培训资料</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </table>
        <table     class="common-table"  >
        </table>
        @include("layouts.page")
    </section>

@endsection
