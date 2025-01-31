<?php

namespace Sotbit\Seometa\Helper;

use Bitrix\Main\Config\Option;

class Settings {
    private static $instance = false;
    private $data = [];

    private function __construct($siteID){
        $this->data = Option::getForModule('sotbit.seometa', $siteID);

        $this->setDefaultSettings();
    }

    private function setDefaultSettings() {
        if(!isset($this->data['FILTER_TYPE']))
            $this->data['FILTER_TYPE'] = 'bitrix_chpu';
    }

    public static function getInstance($siteID) {
        if(self::$instance == false) {
            self::$instance = new Settings($siteID);
        }

        return self::$instance;
    }

    public function __get($name) {
        return (isset($this->data[$name])) ? $this->data[$name] : null;
    }
}