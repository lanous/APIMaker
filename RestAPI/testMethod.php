<?php

class testMethod extends Lanous\APIMaker\RestAPI\Structure {
    public function info() {
        $data['name'] = "mohammad";
        $data['lastname'] = "azad";
        $data['get'] = $_GET;
        return $this->__returnTrue($data);
    }
}