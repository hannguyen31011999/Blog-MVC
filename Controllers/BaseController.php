<?php
namespace Controllers;

class BaseController
{
    const VIEW_FOLDER_NAME = "Views";
    
    protected function view($viewPath , array $data = [])
	{
        foreach($data as $key => $value)
        {
            // Biến = $key
            $$key = $value;
        }
		$viewPath = self::VIEW_FOLDER_NAME . '/' . str_replace('.','/',$viewPath) .'.php';
        return require($viewPath);
	}
}
?>