<!DOCTYPE html> 
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta name=“viewport” content=“width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes”/>
        <title>对学生评价</title>
        <link href="/css/main.css" rel="stylesheet" media="screen" type="text/css">
        <script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="/page_js/teacher_info/tea_comment.js"></script>
    </head>
    <body class="body-div">
        <div class="up">
            <div id="container">
                <div class="iconfont">
                    <div class="group">
                        <div class="icon-tag">
                            <image class="image-tag" src="/images/tea_comment/icon_tag.png" height="15" width="3">
                            </image>
                            <div class="title-tag">
                                <h4>1.试听情况</h4>
                            </div>
                        </div>
                        <div id="pre-listen" class="dowebok" data-finish=""> 
                            <div class="line">
                                <div class="radioOrCheckBox1 listen1">
                                    <input class="radioclass" id="pre-listen-1" type="radio" name="radio-listen" value="顺利完成">
                                </div>
                                <span class="text-4-radio">顺利完成</span>
                            </div>
                            <div class="line">
                                <div class="radioOrCheckBox2 listen2">
                                    <input class="radioclass" id="pre-listen-2" type="radio" name="radio-listen" value="未顺利完成">
                                </div>
                                <span class="text-4-radio">未顺利完成</span>
                            </div>
                            <div id="area1"></div>
                        </div>
                        <span class="error-message1"></span>
                    </div>

                    <div class="group">
                        <div class="icon-tag">
                            <image class="image-tag" src="/images/tea_comment/icon_tag.png" height="12" width="3">
                            </image>
                            <div class="title-tag">
                                <h4>2.学习态度</h4>
                            </div>
                        </div>
                        <div id="learn-attitude" class="dowebok" data-finish="">
                            <div class="line">    
                                <div class="radioOrCheckBox1 attitude1">
                                    <input id="attitude-1" class="radioclass" type="radio" name="radio-attitude" value="积极配合，兴趣浓厚">
                                </div>
                                <span class="text-4-radio">积极配合，兴趣浓厚</span>
                            </div>
                            <div class="line">    
                                <div class="radioOrCheckBox2 attitude2">
                                    <input id="attitude-2" class="radioclass" type="radio" name="radio-attitude" value="较好配合，互动较多">
                                </div>
                                <span class="text-4-radio">较好配合，互动较多</span>
                            </div>
                            <div class="line">    
                                <div class="radioOrCheckBox3 attitude3">
                                    <input id="attitude-3" class="radioclass" type="radio" name="radio-attitude" value="配合度一般，但愿意回答问题">
                                </div>
                                <span class="text-4-radio">配合度一般，但愿意回答问题</span>
                            </div>
                            <div class="line">    
                                <div class="radioOrCheckBox4 attitude4">
                                    <input id="attitude-4" class="radioclass" type="radio" name="radio-attitude" value="不太愿意配合">
                                </div>
                                <span class="text-4-radio">不太愿意配合</span>
                            </div>
                            <span class="error-message2"></span>
                        </div>
                    </div>


                    <div class="group">

                        <div class="icon-tag">
                            <image class="image-tag" src="/images/tea_comment/icon_tag.png" height="12" width="3">
                            </image>
                            <div class="title-tag">
                                <h4>3.学习基础情况</h4>
                            </div>
                        </div>
                        
                        <div id="element" class="dowebok" data-finish="">
                            <div class="line">    
                                <div class="radioOrCheckBox1 element1">
                                    <input id="element-1" class="radioclass" type="radio" name="radio-element" value="较好，紧跟老师节奏，完美消化所学">
                                </div>
                                <span class="text-4-radio">较好，紧跟老师节奏，完美消化所学</span>
                            </div>
                            <div class="line">    
                                <div class="radioOrCheckBox2 element2">
                                    <input id="element-2" class="radioclass" type="radio" name="radio-element" value="中等，但可以较好吸收当堂所学">
                                </div>
                                <span class="text-4-radio">中等，但可以较好吸收当堂所学</span>
                            </div>
                            <div class="line">    
                                <div class="radioOrCheckBox3 element3">
                                    <input id="element-3" class="radioclass" type="radio" name="radio-element" value="一般，部分内容需要再学习">
                                </div>
                                <span class="text-4-radio">一般，部分内容需要再学习</span>
                            </div>
                            <div class="line">    
                                <div class="radioOrCheckBox4 element4">
                                    <input id="element-4" class="radioclass" type="radio" name="radio-element" value="较差，试听内容基本听不懂">
                                </div>
                                <span class="text-4-radio">较差，试听内容基本听不懂</span>
                            </div>
                        </div>
                        <span class="error-message3"></span>
                    </div>
                </div>

                <div class="group">
                    <div class="icon-tag">
                        <image class="image-tag" src="/images/tea_comment/icon_tag.png" height="12" width="3">
                        </image>
                        <div class="title-tag">
                            <h4>4.学生优点 (可多选)</h4>
                        </div>
                    </div>
                    
                    <div id="advantage" class="dowebok" data-finish="">
                        <div class="line">    
                            <div class="radioOrCheckBox1 advantage1">
                                <input id="advantage-1" class="radioclass" type="checkbox" name="checkbox-advantage" value="理解能力强">
                            </div>
                            <span class="text-4-radio">理解能力强</span>
                        </div>
                        <div class="line">    
                            <div class="radioOrCheckBox2 advantage2">
                                <input id="advantage-2" class="radioclass" type="checkbox" name="checkbox-advantage" value="理解能力强">
                            </div>
                            <span class="text-4-radio">表达能力强</span>
                        </div>
                        <div class="line">    
                            <div class="radioOrCheckBox3 advantage3">
                                <input id="advantage-3" class="radioclass" type="checkbox" name="checkbox-advantage" value="理解能力强">
                            </div>
                            <span class="text-4-radio">思路清晰</span>
                        </div>
                        <div class="line">    
                            <div class="radioOrCheckBox4 advantage4">
                                <input id="advantage-4" class="radioclass" type="checkbox" name="checkbox-advantage" value="理解能力强">
                            </div>
                            <span class="text-4-radio">自信十足</span>
                        </div>
                        <div class="line">    
                            <div class="radioOrCheckBox5 advantage5">
                                <input id="advantage-5" class="radioclass" type="checkbox" name="checkbox-advantage" value="理解能力强">
                            </div>
                            <span class="text-4-radio">其他</span>
                        </div>
                        <div id="area2"></div>
                        <span class="error-message4"></span>
                    </div>
                </div>

                <div class="group">
                    <div class="icon-tag">
                        <image class="image-tag" src="/images/tea_comment/icon_tag.png" height="12" width="3">
                        </image>
                        <div class="title-tag">
                            <h4>5.学生有待提高 (可多选)</h4>
                        </div>
                    </div>
                    
                    <div id="disadvantage" class="dowebok" data-finish="">
                        <div class="line">    
                            <div class="radioOrCheckBox1 disadvantage1">
                                <input id="disadvantage-1" class="radioclass" type="checkbox" name="checkbox-disadvantage" value="思维能力的培养">
                            </div>
                            <span class="text-4-radio">思维能力的培养</span>
                        </div>
                        <div class="line">    
                            <div class="radioOrCheckBox2 disadvantage2">
                                <input id="disadvantage-2" class="radioclass" type="checkbox" name="checkbox-disadvantage" value="知识系统化学习">
                            </div>
                            <span class="text-4-radio">知识系统化学习</span>
                        </div>
                        <div class="line">    
                            <div class="radioOrCheckBox3 disadvantage3">
                                <input id="disadvantage-3" class="radioclass" type="checkbox" name="checkbox-disadvantage" value="语言表达能力的提高">
                            </div>
                            <span class="text-4-radio">语言表达能力的提高</span>
                        </div>
                        <div class="line">    
                            <div class="radioOrCheckBox4 disadvantage4">
                                <input id="disadvantage-4" class="radioclass" type="checkbox" name="checkbox-disadvantage" value="举一反三的能力">
                            </div>
                            <span class="text-4-radio">举一反三的能力</span>
                        </div>
                        <div class="line">    
                            <div class="radioOrCheckBox5 disadvantage5">
                                <input id="disadvantage-5" class="radioclass" type="checkbox" name="checkbox-disadvantage" value="其他">
                            </div>
                            <span class="text-4-radio">其他</span>
                        </div>
                        <div id="area3"></div>
                        <span class="error-message5"></span>
                    </div>
                </div>

                <div class="group">

                    <div class="icon-tag">
                        <image class="image-tag"  src="/images/tea_comment/icon_tag.png" height="12" width="3">
                        </image>
                        <div class="title-tag">
                            <h4>6.培训计划</h4>
                        </div>
                    </div>
                    
                    <div class="select-plan">
                        <div class="line">
                            <div class="radioOrCheckBox1 plan1">
                                <input class="radioclass" id="plan-1" type="radio" name="radio-tranplan" value="从基础内容学习">
                            </div>
                            <span class="text-4-radio">从基础内容学习</span>
                        </div>

                        <div>
                            <select id="select1" class="select-style select-comment select1" disabled>
                                <option>一</option>
                                <option>二</option>
                                <option>三</option>
                                <option>四</option>
                                <option>五</option>
                                <option>六</option>
                                <option>七</option>
                                <option>八</option>
                                <option>九</option>
                                <option>高一</option>
                                <option>高二</option>
                                <option>高三</option>
                            </select>  年级
                            <select id="select2" class="select-book select2" disabled>
                                <option>上</option>
                                <option>下</option>
                            </select>  册
                        </div>
                        <span class="error-message6"></span>

                        <div class="line">
                            <div class="radioOrCheckBox2 plan2">
                                <input class="radioclass" id="plan-2" type="radio" name="radio-tranplan" value="系统性巩固">
                            </div>
                            <span class="text-4-radio">系统性巩固</span>
                        </div>
                        <div>
                            <select id="select3" class="select-style select3" disabled>
                                <option>一</option>
                                <option>二</option>
                                <option>三</option>
                                <option>四</option>
                                <option>五</option>
                                <option>六</option>
                                <option>七</option>
                                <option>八</option>
                                <option>九</option>
                                <option>高一</option>
                                <option>高二</option>
                                <option>高三</option>
                            </select>  年级
                            <select id="select4" class="select-book select4" disabled>
                                <option>上</option>
                                <option>下</option>
                            </select>  册
                        </div>
                        <span class="error-message7"></span>

                        <div class="line">
                            <div class="radioOrCheckBox3 plan3">
                                <input class="radioclass" id="plan-3" type="radio" name="radio-tranplan" value="提高学习">
                            </div>
                            <span class="text-4-radio">提高学习</span>
                        </div>
                        <div>
                            <select id="select5" class="select-style select5" disabled>
                                <option value="1">一</option>
                                <option value="2">二</option>
                                <option value="3">三</option>
                                <option value="4">四</option>
                                <option value="5">五</option>
                                <option value="6">六</option>
                                <option value="7">七</option>
                                <option value="8">八</option>
                                <option value="9">九</option>
                                <option value="10">高一</option>
                                <option value="11">高二</option>
                                <option value="12">高三</option>
                            </select>  年级
                            <select id="select6" class="select-book select6" disabled>
                                <option value="1">上</option>
                                <option value="2">下</option>
                            </select>  册
                        </div>
                        <span class="error-message8"></span>

                        <div class="line">
                            <div class="radioOrCheckBox4 plan4">
                                <input class="radioclass" id="plan-4" type="radio" name="radio-tranplan" value="其他">
                            </div>
                            <span class="text-4-radio">其他</span>
                        </div>
                        <div id="area4"><input rows="1" cols="20" id="text_area4" type="text" name="text_area4" style="resize:none;" placeholder="请输入培训计划" >
                        </div>
                        <span class="error-message9"></span>
                    </div>
                </div>

                <div class="group">
                    <div class="icon-tag">
                        <image class="image-tag" src="/images/tea_comment/icon_tag.png" height="12" width="3">
                        </image>
                        <div class="title-tag">
                            <h4>7.教学方向:</h4> 
                        </div>
                    </div>
                    
                    <div class="line">
                        <div class="radioOrCheckBox1 teach1">
                            <input class="radioclass" id="teach-1" type="radio" name="radio-teach" value="课内知识">
                        </div>
                        <span class="text-4-radio">课内知识</span>
                    </div>
                    <select id="select7" class="select-style select7" disabled>
                        <option value="1">一</option>
                        <option value="2">二</option>
                        <option value="3">三</option>
                        <option value="4">四</option>
                        <option value="5">五</option>
                        <option value="6">六</option>
                        <option value="7">七</option>
                        <option value="8">八</option>
                        <option value="9">九</option>
                        <option value="10">高一</option>
                        <option value="11">高二</option>
                        <option value="12">高三</option>
                    </select>  年级
                    <select id="select8" class="select-book select8" disabled>
                        <option value="1">上</option>
                        <option value="2">下</option>
                    </select>  册
                    <span class="error-message10"></span>
                    <div class="line">
                        <div class="radioOrCheckBox2 teach2">
                            <input class="radioclass" id="teach-2" type="radio" name="radio-teach" value="课外知识">
                        </div>
                        <span class="text-4-radio">课外知识</span>
                    </div>
                    <div id="area5">
                        <input rows="1" cols="20" id="text_area5" type="text" name="text_area5" style="resize:none;" placeholder="请输入课外知识" >
                    </div>
                    <span class="error-message11"></span>
                </div>
                
                <div class="group">
                    <div class="icon-tag">
                        <image class="image-tag" src="/images/tea_comment/icon_tag.png" height="12" width="3">
                        <div class="title-tag">
                            <h4>8.意见、建议等:(50字以上)</h4>
                        </div>
                    </div>
                    <div id="area6">
                        <textarea rows="4" cols="20" id="text_area6" name="text_area6" style="resize:none;width:400px;" ></textarea>
                    </div>
                    <span class="error-message12"></span>
                </div>

                <div class="group">
                    <div class="style">
                        <input class="btn-style" type="button" value="提交反馈" id="btn-submit" />
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
