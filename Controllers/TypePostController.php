<?php

namespace Controllers;

require_once(dirname(__FILE__).'/BaseController.php');
require_once(dirname(__FILE__).'/Models/TypePostModel.php');
require_once(dirname(__FILE__).'/Requests/Request.php');
use \Models\TypePostModel;
use \Requests\Request;
class TypePostController extends BaseController
{
    private $typePostModel;

    private $request;

    function __construct()
    {
        $this->typePostModel = new Models\TypePostModel;
        $this->request = new Requests\Request;
    }
    function index()
    {
        $TypePost = $this->typePostModel->all();
    	return $this->view('Admin.TypePost.List',['TypePost'=>$TypePost]);
    }

    function viewAdd()
    {
    	return $this->view('Admin.TypePost.Add');
    }

    function store()
    {
        $validate = $this->request::make($_POST,
            [
                'type_name'=>'required|unique:type_post,type_post_name'
            ],
            [
                'type_name.required'=>'Chưa nhập dữ liệu',
                'type_name.unique'=>'Tên loại bài viết tồn tại'
            ]
        );
        if(empty($validate))
        {
            $this->typePostModel->create(['type_post_name'=>$_POST['type_name'],'created_at'=>'2020-09-03 22:12:53','updated_at'=>null,'deleted_at'=>null]);
            header("Location: /blog_tintuc/admin/typepost");
        }
        else
        {
            return $this->view('Admin.TypePost.Add',
                [
                    'errors'=>$validate
                ]
            );
        }
        // echo $this->request->unique('type_post','Thể Thao');die;
        // echo $_POST['type_name'];die;
        // $this->typePostModel->create(['type_post_name'=>'Du lịch','created_at'=>'2020-09-03 22:12:53','updated_at'=>null,'deleted_at'=>null]);
    }
}

?>