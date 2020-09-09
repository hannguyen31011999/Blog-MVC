<?php

class Router
{
    private $routers = [];

    function __construct()
    {

    }

    // Function lấy url và tách chuỗi url
    private function getRequestURL()
    {
        // lấy url
        $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        // tách chuỗi url
        $url = str_replace('blog_tintuc/','',$url);
        // lấy giá trị url
        $url = $url === '' || empty($url) ? '' : $url;
        return $url;
    }

    // Function lấy method
    private function getRequestMethod()
    {
        // Lấy method
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "GET";
    }

    // function thêm 1 route trong mảng router
    private function addRouter($method,$url,$action)
    {
        $this->routers[] = [$method,$url,$action];
    }

    public function get($url,$action)
    {
        $this->addRouter('GET',$url,$action);
    }

    public function post($url,$action)
    {
        $this->addRouter('POST',$url,$action);
    }

    public function any($url,$action)
    {
        $this->addRouter('GET|POST',$url,$action);
    }

    // Function xử lí route
    public function map()
    {
        $requestURL = $this->getRequestURL();
        $requestMethod = $this->getRequestMethod();
        $routers = $this->routers;
        $checkRoute = false;
        $params = [];
        foreach($routers as $route)
        {
            list($method,$url,$action) = $route;
            //kiểm tra method request lên với route
            if(strpos($method,$requestMethod)===false)
            {
                continue;
            }
            if($url === "*")
            {
                $checkRoute = true;
            }
            
            elseif(strpos($url,'{')===false){
                
                if(strcmp(strtolower($url),strtolower($requestURL)) ===0)
                {
                    $checkRoute = true;
                }
                else
                {
                    continue;
                }
            }
            elseif(strpos($url,'}')===false)
            {
                continue;
            }
            else
            {
                $routeParams = explode('/',$url);
                $requestParams = explode('/',$requestURL);

                if(count($routeParams)!==count($requestParams))
                {
                    continue;
                }
                foreach( $routeParams as $key => $value )
                {
                    if( preg_match('/^{\w+}$/',$value))
                    {
                        $params[] = $requestParams[$key];
                    }
                }
                $checkRoute = true;
            }
            // if(strpos($method,$requestMethod)!==false)
            // {
            //     // kiểm tra url request lên
            //     // hàm strcmp kiểm tra 2 chuỗi bằng nhau
            //     if(strcmp(strtolower($url),strtolower($requestURL)) ===0)
            //     {
            //         $checkRoute = true;
            //     }
            // }
            if($checkRoute === true)
            {
                // hàm is_callable kiểm tra biểu thức lamda route object
                if(is_callable($action))
                {
                    // hàm call_user_func_array truyền mảng vào thành tham số của hàm
                    call_user_func_array($action,$params);
                    return;
                }elseif(is_string($action)){
                    $this->compieRoute($action,$params);
                    return;
                }
            }
        }
        return;
    }

    // Function xử lí controller và method trong route
    private function compieRoute($action,$params)
    {
        if(count(explode('@',$action))!==2)
        {
            die('Route error');
        }
        else
        {
            $className = explode('@',$action)[0];
            $methodName = explode('@',$action)[1];
            $classNamespace = 'Controllers\\'.$className;
            // kiểm tra class trong namespace Controllers có tồn tại
            if(class_exists($classNamespace))
            {
                $object = new $classNamespace;
                // kiểm tra method có tồn tại trong class
                if(method_exists($classNamespace,$methodName))
                {
                    call_user_func_array([$object,$methodName],$params);
                }
                else
                {
                    die('Method '.$methodName.' not found');
                }
            }
            else
            {
                die('Class '.$classNamespace.' not found');
            }
        }
        
    }

    function run()
    {
        $this->map();
    }
}

?>