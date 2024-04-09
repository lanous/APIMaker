<?php
namespace Lanous\APIMaker\RestAPI;
class Structure {
    use Results;
    use MethodManagment;
    public $__document;
    final protected function __returnFalse(string $error_message,int $error_code) {
        return $this->ReturnJson($this->falseResult($error_message,$error_code));
    }
    final protected function __returnTrue(array $data) {
        return $this->ReturnJson($this->trueResult($data));
    }
    final protected function __RequestSetting($method,...$index) {
        $this->__document[$method] = $index;
    }

}