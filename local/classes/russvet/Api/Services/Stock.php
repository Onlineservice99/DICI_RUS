<?php namespace Russvet\Api\Services;

/**
 * Данный класс используется для получения актуального справочника складов
 * Class Stock
 * @package Russvet\Api\Services
 */
class Stock extends \Russvet\Api\Api
{
    public function __construct()
    {
        parent::__construct();
        $this->data["uriApi"] = "/rs/stocks";
    }
}