<?php

require_once '../../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT']."/src/app/views.php";
include_once $app_dir."/route.php";

class Bootstrap
{
    public function __construct() {
       
    }
   

    public function notfound()
    {
        header('HTTP/1.1 404 Not Found');
        $viewEngine = new ViewsEngine("404");
        return die($viewEngine->render());

    }
    public function content()
    {
        # code...
    }
  

}

?>