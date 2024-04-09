<?php

class Calculate extends Lanous\APIMaker\RestAPI\Structure {
    public function __construct() {
        $Xparameter = self::Allowed(
            description: 'Parameter X',   field: 'X',
            data_type: parent::typeFloat,   method: parent::GET,
            requirement: true
        );
        $Yparameter = self::Allowed(
            description: 'Parameter Y',   field: 'Y',
            data_type: parent::typeFloat,   method: parent::GET,
            requirement: true
        );
        $this->__RequestSetting("sum",$Xparameter,$Yparameter);
        $this->__RequestSetting("division",$Xparameter,$Yparameter);
    }
    public function sum($X,$Y) {
        $return['calculate'] = $X + $Y;
        return $this->__returnTrue($return);
    }
    public function division($X,$Y) {
        $return['calculate'] = $X / $Y;
        return $this->__returnTrue($return);
    }
}