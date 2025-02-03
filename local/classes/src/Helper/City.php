<?php
namespace Meven\Helper;
use \Bitrix\Main\Service\GeoIp\Manager;
use \Bitrix\Main\Web\Cookie;
use \Bitrix\Main\Application;

class City
{
    public $city = null;
    private static $instance = null;

    public function __construct()
    {
        $geoData = $this->getFromIp();
        $city = ( !is_null($geoData) ) ? $geoData->cityName : "Череповец";
        $this->city = $city;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function getNameCity()
    {
        return $this->city;
    }

    public function getFromIp()
    {
        $ipAddress = Manager::getRealIp();
        $result = Manager::getDataResult($ipAddress, "ru");
        return ( !is_null($result) ) ? $result->getGeoData() : null;
    }
}