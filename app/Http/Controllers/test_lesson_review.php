<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class test_lesson_review extends Controller
{
    use CacheNick;
    public function test_lesson_review_list() {
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_test_lesson_subject_require_review->get_all_list($page_info);
        return $this->pageView(__METHOD__,$ret_info);
    }

}
