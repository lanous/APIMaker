<?php
namespace Lanous\APIMaker;
class RestAPI {
    use \Lanous\APIMaker\RestAPI\Results;
    private $dir;
    private $headers;
    public function __construct($project_dir) {
        $this->dir = $project_dir;
        if(!file_exists($this->dir."/RestAPI")) {
            mkdir($this->dir."/RestAPI");
        }
        $this->AutoLoad();
        $this->headers = getallheaders();
    }
    private function IncludePHPFiles($directory) {
        foreach(glob($directory."/*") as $name) {
            if(is_dir($name)) {
                $this->IncludePHPFiles($name);
            } else {
                include_once($name);
            }
        }
    }
    private function AutoLoad() {
        $this->IncludePHPFiles($this->dir."/RestAPI");
    }

    public function Authorization($scheme,callable $callback) {
        $Errors = [401 => "Unauthorized",403 => "Forbidden"];
        $Authorization = $this->headers["Authorization"] ?? $this->ReturnJson($this->falseResult($Errors[401],401));
        $Token = str_replace($scheme.' ','',$Authorization);
        if($Token == '') {
            $this->ReturnJson($this->falseResult($Errors[401],401));
        } elseif (!$callback($Token)) {
            $this->ReturnJson($this->falseResult($Errors[403],403));
        }
    }
    public function Route(string $class_name,string $method) {
        if(!class_exists($class_name)) {
            $this->ReturnJson($this->falseResult("Internal Server Error",500));
        }
        $class = new $class_name();
        if(!method_exists($class_name,$method)){
            $this->ReturnJson($this->falseResult("Not Found",404));
        }
        $result = $class->{$method}();
        print(json_encode($result,128|256));
    }
}