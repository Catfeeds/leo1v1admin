<?php
namespace App\Http\Controllers;
class v extends Controller
{
    public function index()  {
        return $this->pageView(__METHOD__ );
    }
}