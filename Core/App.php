<?php

require_once(dirname(__FILE__).'/Router.php');
require_once(dirname(__FILE__).'/../Controllers/PostController.php');
require_once(dirname(__FILE__).'/../Controllers/TypePostController.php');
require_once(dirname(__FILE__).'/../Controllers/DashBoardController.php');
class App
{
    private $router;
    function __construct()
    {
        // new Controllers\PostController;

        $this->router = new Router();

        $this->router->get('/','PostController@index');

        $this->router->get('/admin/dashboard','DashBoardController@index');

        $this->router->get('/admin/post','PostController@list');

        $this->router->get('/admin/typepost','TypePostController@index');

        $this->router->get('/admin/typepost/add','TypePostController@viewAdd');

        $this->router->post('/admin/typepost/add','TypePostController@store');

        $this->router->any('*',function(){
            echo "Page 404";
        });
    }

    public function run()
    {
        $this->router->run();
    }
}

?>