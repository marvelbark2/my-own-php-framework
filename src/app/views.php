<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ViewsEngine{
    private $file;
    private $twig;
    private $variables = [];
    public function __construct($file) {
        $this->file = $file.".prospeak";
        try {
            $loader = new FilesystemLoader(__DIR__.'/views/templates');
            $twig = new Environment($loader);
            $this->twig = $twig;
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function render($variables = [null]){
        $this->variables = $variables;
        echo $this->twig->render($this->file, $this->variables);

    }

    public function render404(){
        $this->file = '404.prospeak';
        return $this->render(['msg' => "if you're sure about the link, please contact us"]);
    }
}
