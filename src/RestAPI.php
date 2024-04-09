<?php
namespace Lanous\APIMaker;
class RestAPI {
    use RestAPI\Results;
    use RestAPI\MethodManagment;
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
            error_log("class not found. class: ".$class_name);
            $this->ReturnJson($this->falseResult("Internal Server Error",500));
        }
        $class = new $class_name();
        if(!method_exists($class_name,$method)){
            $this->ReturnJson($this->falseResult("Not Found",404));
        }
        $document = $class->__document[$method];
        $class_request_mehod = array_unique(array_column($document,'method'));
        if(count($class_request_mehod) > 1) {
            error_log("You cannot use multiple request methods at the same time. class: ".$class_name);
            $this->ReturnJson($this->falseResult("Internal Server Error",500));
        }
        if(strtolower($_SERVER['REQUEST_METHOD']) != $class_request_mehod[0]) {
            $this->ReturnJson($this->falseResult("request method error",400));
        }
        $args = array_map(function ($data) {
            $data_type = $data['data_type'];
            $field = $data['field'];
            if($data['requirement'] == true) {
                if($data['method'] == self::GET) {
                    $method_data = $_GET[$field] ?? $this->ReturnJson($this->falseResult("Missing ".$field." parameter.",400));
                } elseif($data['method'] == self::POST) {
                    $method_data = $_POST[$field] ?? $this->ReturnJson($this->falseResult("Missing ".$field." parameter.",400));
                }
                if(!$this->Validate($method_data,$data_type)) {
                    $this->ReturnJson($this->falseResult("The data type of the ".$field." field is not correct. It must be ".$data_type,400));
                }
            } else {
                if($data['method'] == self::GET) {
                    $method_data = $_GET[$field] ?? null;
                } elseif($data['method'] == self::POST) {
                    $method_data = $_POST[$field] ?? null;
                }
            }
            return ["field" => $field,"value" => $method_data];
        },$document);
        $args = array_column($args,'value','field');
        $result = call_user_func_array([$class,$method],$args);
        self::ReturnJson($result);
    }

    private function Validate($method_data,$data_type) {
        if ($data_type == self::typeBoolean and !filter_var($method_data,FILTER_VALIDATE_BOOL)) {
            return false;
        } elseif ($data_type == self::typeINT and !filter_var($method_data,FILTER_VALIDATE_INT)) {
            return false;
        } elseif ($data_type == self::typeString and !is_string($method_data)) {
            return false;
        } elseif ($data_type == self::typeJson) {
            json_decode($method_data);
            if(json_last_error() !== JSON_ERROR_NONE) {
                return false;
            }
        } elseif ($data_type == self::typeFloat and !filter_var($method_data,FILTER_VALIDATE_FLOAT)) {
            return false;
        }
        return true;
    }
}