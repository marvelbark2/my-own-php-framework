<?php

class RouteController{

   
    public function index()
    {
        $view = new ViewsEngine("tst");
        return $view->render(['data' => 123]);
    }
    public function indexPar($id, $p)
    {
        return "Id ".$id." p ".$p;
    }
}