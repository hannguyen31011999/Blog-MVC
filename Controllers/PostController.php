<?php
namespace Controllers;
require_once(dirname(__FILE__).'/../Controllers/BaseController.php');

class PostController extends BaseController
{
    function index()
    {
        $post = [
            [
                "id"=>1,
                "title"=>"Anh yêu em"
            ],
            [
                "id"=>2,
                "title"=>"Hành động",
            ],
            [
                "id"=>3,
                "title"=>"Thể thao"
            ]
        ];
        return $this->view('User.index',['post'=>$post]);
    }
}

?>