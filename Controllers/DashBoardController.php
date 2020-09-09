<?php

namespace Controllers;
require_once(dirname(__FILE__).'/../Controllers/BaseController.php');

class DashBoardController extends BaseController
{
    function __construct()
    {

    }
    function index()
    {
        return $this->view('Admin.dashboard');
    }
}

?>