<?php
namespace Meven\Exchange;

use \Russvet\Api\Services;

class Stock
{
	protected $service;
	
    public function __construct()
    {
        $this->service = new Services\Stock();        
    }
	
	public function getList()
	{
		$stocks = $this->service->send();
		$stocks = json_decode($stocks, true);

        return $stocks['Stocks'];
	}
	
	public function getDropDownList()
	{
		$stocks = $this->getList();

        return array_combine(array_column($stocks, 'ORGANIZATION_ID'), array_column($stocks, 'NAME'));
	}
}