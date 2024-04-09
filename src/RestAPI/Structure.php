<?php
namespace Lanous\APIMaker\RestAPI;
class Structure {
    use \Lanous\APIMaker\RestAPI\Results;
    final protected function __returnFalse(string $error_message,int $error_code) {
        return $this->ReturnJson($this->falseResult($error_message,$error_code));
    }
    final protected function __returnTrue(array $data) {
        return $this->ReturnJson($this->trueResult($data));
    }
}