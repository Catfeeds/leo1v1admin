<?php
return array(
//学程状态（0 未联系 1 待付款 2 待分配老师 3 待排课 4 正常上课 5 提出退费申请 6 退费成功）
// a 个人主页 b 录入回访 c 安排老师 d 安排学管师 e 处理退费申请 f 寄送礼包 g 排课
        array(0,"","a,b"),
        array(1,"","a,b"),
        array(2,"","a,c,d"),
        array(3,"","a,f,g"),
        array(4,"","a,b,g"),
        array(5,"","a,e"),
        array(6,"","a,b"),
);

