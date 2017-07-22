<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

/*
3. 修改字段的注释，代码如下：

ALTER TABLE `student` MODIFY COLUMN `id` COMMENT '学号';

查看字段的信息，代码如下：

SHOW FULL COLUMNS  FROM `student`;
*/

class pdf_viewer extends Controller
{
    public function index() {
        return $this->pageView(__METHOD__);
    }

}