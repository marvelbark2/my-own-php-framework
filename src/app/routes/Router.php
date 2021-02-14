<?php

class Router
{
  private $request;
  private $supportedHttpMethods = array(
    "GET",
    "POST"
  );

  function __construct(IRequest $request)
  {
   $this->request = $request;
  }

  function __call($name, $args)
  {
    list($route, $method) = $args;
    
    if(!in_array(strtoupper($name), $this->supportedHttpMethods))
    {
      $this->invalidMethodHandler();
    }

    $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
  }

  /**
   * Removes trailing forward slashes from the right of the route.
   * @param route (string)
   */
  private function formatRoute($route)
  {
    $result = rtrim($route, '/');
    if ($result === '')
    {
      return '/';
    }
    return $result;
  }

  private function invalidMethodHandler()
  {
    header("{$this->request->serverProtocol} 405 Method Not Allowed");
  }

  private function defaultRequestHandler()
  {
    header("{$this->request->serverProtocol} 404 Not Found");
    // die("Not found");
    $notFound = new ViewsEngine("");
    die($notFound->render404());

  }

  /**
   * Resolves a route
   */
  function resolve()
  {
    $methodDictionary = $this->{strtolower($this->request->requestMethod)};
    $formatedRoute = $this->formatRoute($this->request->requestUri);
    $method = null;
    $params = [];
    if (array_key_exists($formatedRoute,$methodDictionary)) {
        $method = $methodDictionary[$formatedRoute];
        
    } else {
      
      $urlRequested = explode("/", $formatedRoute);
      if(count($urlRequested) > 0){
        foreach ($methodDictionary as $key => $value) {
          $withParam = explode(":", $key);
          var_dump($withParam)."<br/";
          if(count($withParam) > 0){
            $urlwithoutp = "/".$urlRequested[1]."/";
            if($withParam[0] == $urlwithoutp){
              $method = $value;
              array_push($params, $urlRequested[2]);
            }
          }
        }
      }
    }
    
    if(is_null($method))
    {
      $this->defaultRequestHandler();
      return;
    }
    if (gettype($method) == 'object') {
      echo call_user_func_array($method, array($this->request));
    }elseif(gettype($method) == 'string'){
      $controller_class = explode("@",$method)[0];
      $controller_method = explode("@",$method)[1];
      include_once $_SERVER['DOCUMENT_ROOT'].'/src/app/controllers/'.$controller_class.".php";
      $instance = new $controller_class();
      try {
        if($params == null)
          echo $instance->$controller_method();
        else if(count($params) == 1)
          echo $instance->$controller_method($params[0]);
        else
          echo $instance->$controller_method($params[0], $params[1]);
      } catch (\Throwable $th) {
        throw $th;
      }
      
      
    }
//    
  }

  function __destruct()
  {
    $this->resolve();
  }
}