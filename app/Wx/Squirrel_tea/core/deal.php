<?php
namespace App\Wx\Squirrel_tea\Core;

use LaneWeChat\Core\ResponsePassive;


class deal {
    public static function deal_autoreplay($msg,&$request){
        \App\Helper\Utils::logger('ssss1');

        if (
            $msg == '如何成为理优老师' ||
            $msg == '怎么当老师' ||
            $msg == '怎么申请成为老师' ||
            $msg == '成为理优老师' ||
            $msg == '推荐朋友' ||
            $msg == '推荐老师' ||
            $msg == '报名'
        )
        {
            \App\Helper\Utils::logger('haode');

            //规则1 如何成为理优老师？
            $content = "http://www.leo1v1.com/tea.html
                         你好
                         填写您的基本信息
                         后续会有教务老师联系您哈";

            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        } elseif (
            $msg == '学生没来' ||
            $msg == '没有进课堂' ||
            $msg == '没进课堂' ||
            $msg == '学生未到' ||
            $msg == '不来上课'
        )
          {
              //规则2 试听课学生未到怎么办？
              $content = '试听课学生如果没来，销售老师会第一时间收到消息去联系家长，老师只需要在课堂等待40分钟就好，如果学生中途进来，老师能上多少是多少哈，这节课算老师课时费。';

              return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
          } elseif (
              $msg == '下载' ||
              $msg == '网址' ||
              $msg == '作业' ||
              $msg == '登录' ||
              $msg == '不信任' ||
              $msg == '批改作业' ||
              $msg == '回看视频' ||
              $msg == '改密码' ||
              $msg == '课后评价' ||
              $msg == '怎么用'
          )
            {
                //规则3 如何使用老师端？
                $tuwenList[] = array(

                    'title' => '理优老师完全手册',

                    'description' => '理优老师完全手册',

                    'pic_url' => 'http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%AE%8C%E5%85%A8%E6%89%8B%E5%86%8C-%E4%B8%80%E9%83%A8%E5%88%86/%E7%90%86%E7%94%B1%E8%80%81%E5%B8%88%E6%89%8B%E5%86%8C%E5%B0%81%E9%9D%A2.png',

                    'url' => 'http://admin.yb1v1.com/article_wx/leo_teacher_manual',
                );
                $item = array();
                foreach($tuwenList as $tuwen){
                    $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
                }
                return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);
            } elseif (
                $msg == '薪资问题扣款' ||
                $msg == '工资' ||
                $msg == '扣钱' ||
                $msg == '薪资'
            ) {
            //规则4 薪资相关问题？
            $content = '
理优有专门反馈薪资/扣款的申诉渠道

具体操作如下：

1、点击【查工资】

2、进入【工资汇总情况】

3、点击【总薪资】

4、找到有疑问的条目

5、点击【添加申诉】即可';

            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);

        } elseif (
            $msg == '讲义' ||
            $msg == '上传'
        )
        {
              // 规则5 如何上传讲义
              $content = '
1、如何上传 请参考群共享-理优讲师上传讲义流程；

2、讲义分学生版和教师版 务必全部上传 不然会被扣钱哟~
技巧分享：可以全部上传学生版讲义 提高备课效率；

3、讲义内容可以根据学生个性化情况自行准备，也可以咨询各科目教研老师获得备课帮助';

              return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);

        } elseif ( $msg == '下载试卷' ) {
            // 规则6 如何下载试听课试卷

            $content = '
请参考群共享文件—理优教师 如何下载试听课试卷

通过试卷可以了解到学员最全面的学习近况，务必提前下载试卷，进行针对性备课，千万不要只讲解试卷错题，可以安排一些类似题型进行巩固训练。';
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        } elseif ( $msg == '试讲视频' ) {
            // 规则7 如何上传试讲视频

            $content = '请参考qq群共享文件-理优老师自助试讲流程

注：试讲视频赞不支持ipad端，只能通过电脑端进行操作；';

            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);

        } elseif (
            $msg == '教材' ||
            $msg == '版本' ||
            $msg == '电子课本' ||
            $msg == '备课'
        ) {
            // 规则8 教学及备课
            $content = '老师您好！教学及备课方面内容可以直接进入 理优-数学备课交流群 群号:536484566，会在该群中不断更新各种版本教材及教案';
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        } elseif (
            $msg == '评价' ||
            $msg == '课后反馈' ||
            $msg == '课后评价'
        ) {
            // 规则9 什么是课后评价
            $content = '课后评价是老师对于学生课后的专业反馈，包含了学生的表现、对于知识点的掌握情况、课堂中产生了哪些问题以及老师专业的解决方案。';

            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);

        } elseif ($msg == '什么时候评价') {
            // 规则10 什么时候评价
            $content = '
试听课：课后45分钟内完成评价

常规课：课后48小时内完成评价

评价对于学生和家长是非常重要的，不评价会被小优老师处罚哟~

每次课后只需要评价一次即可，无需多次评价~

想了解理优的奖惩制度，可以关注-理优1对1老师帮 获得更多帮助';

            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);

        } elseif (
            $msg == '尽快排课' ||
            $msg == '什么时候排课' ||
            $msg == '排课'
        ) {
            // 规则11 如何才能尽快被排课？
            $content = '
1、及时微信关注—理优1对1老师帮 进行空闲上课时间设置；

2、及时联系对应科目排课老师，积极响应排课老师群内安排；

3、试听课充分准备，课前务必跟学生进行几分钟教学内容方面的了解，增加学生好感；

4、提高试听课转化率，才能让排课老师更加放心的把学生交给大家；';

            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);

        } elseif (
            $msg == '看不到图片' ||
            $msg == '没图' ||
            $msg == '图片上传失败' ||
            $msg == '图片失败'
        ) {

            // 规则12 上课过程中对方看不到图片怎么办？
            $content='
1、先确认自己图片是否上传成功，软件右上角就会上传成功提示；

2、若提示失败，请检查本地网络 重启设备操作；

3、若提示成功，但对方仍然看不到 请建议对方重启设备 重新连接网络；

4、以上均无效
试听课对接排课老师切换白板服务器
常规课对接助教老师切换白板服务器。

为了确保服务体验，烦请老师安抚一下家长和学生情绪
理优后期服务部门竭诚为大家创造一个优良的教学环境 \ue032 \ue032';

            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        } elseif (
            $msg == '听不到' ||
            $msg == '不能听见' ||
            $msg == '没有声音'
        ) {
            // 规则13 上课中听不到声音（断断续续）怎么办？？
            $content = '
1、双方重启设备，在白板上写字通知学生重启。

2、临时解决方案：通过QQ语音或者微信语音确保课程进程，可通过白板联系加学生QQ或者微信；

3、重启无效请联系对应负责人，试听课对接教务老师；常规课对接助教老师。
';
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);

        } elseif (
            $msg == '学生请假' ||
            $msg == '请假'
        ) {
            //规则14 试听课学生请假怎么办？
            $content = '
课前4小时内 试听课学生请假，试听课课时费正常结算。

若发生迟到或者未评价扣款通知，可以进入公众号“理优1对1老师帮”工资明细中针对此次扣款进行申诉即可，小优会及时跟进处理';
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        } elseif (
            $msg == '怎么办' ||
            $msg == '咋办' ||
            $msg == '怎么处理'
        ) {

            // 规则15 万能回复
            $content = '重新登录、重启软件、更新版本';
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        } elseif (
            $msg == '后台' ||
            $msg == '讲义在哪儿上传' ||
            $msg == '去哪儿下载' ||
            $msg == '链接'
        ) {
            // 规则16 后台链接
            $content = '
理优老师后台
http://www.leo1v1.com/login/teacher';

            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        } elseif (
            $msg == '怎么绑定'||
            $msg == '如何绑定'||
            $msg == '绑定'||
            $msg == '如何注册'

        ){
            // 规则17 绑定公众号
            $content = '
【绑定地址】
http://t.cn/RcQGnPX';

            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        } elseif ($msg == '培训') {
            // 规则18 如何参加培训
            $content = '
培训是在老师的PC或iPad客户端进行的
需要登录自己的账号密码
开始培训前，培训老师会发出培训邀请
a、收到邀请后，点击“我的”中的“培训课程”
b、找到相应的课程并点击“进入课堂”
c、培训课堂可以通过点击“举手”按钮向培训老师申请上麦
d、培训结束后，点击左上角的“返回”按钮即可退出培训课程';

            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);

        } elseif ($msg == '申诉') {
            // 规则19 如何添加申诉
            $content = '对薪资有任何疑问都可以在【查工资】-【工资汇总情况】-【总薪资】对应的课程项目展开后，添加申诉';
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        } elseif ($msg == '理优学生') {
            // 规则20 【学生】常见问题处理方法

            $tuwenList[] = array(

                'title' => '【学生】常见问题处理方法',

                'description' => '',

                'pic_url' => 'http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E8%80%81%E5%B8%88%E7%AB%AF/%E5%AD%A6%E7%94%9F%E9%97%AE%E9%A2%98%E5%B0%81%E9%9D%A2.png',

                'url' => 'http://admin.yb1v1.com/article_wx/leo_teacher_student_question',
            );
            $item = array();
            foreach($tuwenList as $tuwen){
                $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
            }
            return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);
        } elseif ($msg == '理优咨询师') {
            // 规则20 【学生】常见问题处理方法

            $tuwenList[] = array(
                'title' => '【咨询】常见问题处理方法',

                'description' => '',

                'pic_url' => 'http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE/%E7%90%86%E4%BC%98%E8%80%81%E5%B8%88%E5%B8%AE-%E8%80%81%E5%B8%88%E7%AB%AF/%E5%92%A8%E8%AF%A2%E9%97%AE%E9%A2%98%E5%B0%81%E9%9D%A2.png',

                'url' => 'http://admin.yb1v1.com/article_wx/leo_teacher_consult_question',
            );
            $item = array();
            foreach($tuwenList as $tuwen){
                $item[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
            }
            return  ResponsePassive::news($request['fromusername'], $request['tousername'], $item);
        }

    }
}